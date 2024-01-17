<?php

namespace App\Actions\Database;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\DB;
use Doctrine\DBAL\Schema\Column;

class GetTableDetails
{
    use AsAction;

    public function handle($table_name)
    {
        $rc = [];
        $schema = DB::getDoctrineSchemaManager();
        if (!$schema->tablesExist([$table_name])) {
            throw new \BadMethodCallException("table '{$table_name}' does not exists");
        }

        $rc['table_details'] = $schema->listTableDetails($table_name);

        $table = $schema->listTableDetails($table_name);
        $rc['columns'] = collect($table->getColumns())->map(fn (Column $column) => [
            'column' => $column->getName(),
            'attributes' => $this->getAttributesForColumn($column),
            'default' => $column->getDefault(),
            'type' => $column->getType()->getName(),
        ]);

        return $rc;
    }

    protected function getAttributesForColumn(Column $column)
    {
        return collect([
            $column->getAutoincrement() ? 'autoincrement' : null,
            'type' => $column->getType()->getName(),
            $column->getUnsigned() ? 'unsigned' : null,
            !$column->getNotNull() ? 'nullable' : null,
        ])->filter();
    }
}
