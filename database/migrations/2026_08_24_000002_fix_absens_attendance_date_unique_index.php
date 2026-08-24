<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'absens';

    private const COMPOSITE_INDEX = 'absens_pegawai_date_unique';

    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE) || !Schema::hasColumn(self::TABLE, 'attendance_date')) {
            return;
        }

        foreach ($this->uniqueIndexes() as $name => $columns) {
            if ($columns === ['attendance_date']) {
                $this->dropIndex($name);
            }
        }

        $hasCompositeIndex = collect($this->uniqueIndexes())
            ->contains(fn (array $columns) => $columns === ['pegawai_id', 'attendance_date']);

        if (!$hasCompositeIndex) {
            Schema::table(self::TABLE, function (Blueprint $table) {
                $table->unique(['pegawai_id', 'attendance_date'], self::COMPOSITE_INDEX);
            });
        }
    }

    public function down(): void
    {
        $indexes = $this->uniqueIndexes();

        if (($indexes[self::COMPOSITE_INDEX] ?? null) === ['pegawai_id', 'attendance_date']) {
            $this->dropIndex(self::COMPOSITE_INDEX);
        }
    }

    /**
     * Return unique indexes and their ordered columns from the active database.
     *
     * @return array<string, array<int, string>>
     */
    private function uniqueIndexes(): array
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            $rows = DB::select(
                'SELECT INDEX_NAME AS index_name,
                        GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX) AS columns_list
                   FROM information_schema.STATISTICS
                  WHERE TABLE_SCHEMA = ?
                    AND TABLE_NAME = ?
                    AND NON_UNIQUE = 0
                  GROUP BY INDEX_NAME',
                [DB::connection()->getDatabaseName(), self::TABLE]
            );

            return collect($rows)->mapWithKeys(function ($row) {
                return [(string) $row->index_name => explode(',', (string) $row->columns_list)];
            })->all();
        }

        if ($driver === 'sqlite') {
            return collect(DB::select("PRAGMA index_list('" . self::TABLE . "')"))
                ->filter(fn ($index) => (int) $index->unique === 1)
                ->mapWithKeys(function ($index) {
                    $name = (string) $index->name;
                    $escapedName = str_replace("'", "''", $name);
                    $columns = collect(DB::select("PRAGMA index_info('{$escapedName}')"))
                        ->sortBy('seqno')
                        ->pluck('name')
                        ->map(fn ($column) => (string) $column)
                        ->values()
                        ->all();

                    return [$name => $columns];
                })
                ->all();
        }

        return [];
    }

    private function dropIndex(string $name): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            $escapedName = str_replace('`', '``', $name);
            DB::statement('ALTER TABLE `' . self::TABLE . "` DROP INDEX `{$escapedName}`");

            return;
        }

        if ($driver === 'sqlite' && !str_starts_with($name, 'sqlite_autoindex_')) {
            $escapedName = str_replace('"', '""', $name);
            DB::statement("DROP INDEX IF EXISTS \"{$escapedName}\"");
        }
    }
};
