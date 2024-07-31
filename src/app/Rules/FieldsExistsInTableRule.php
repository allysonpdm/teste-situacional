<?php

namespace App\Rules;

use Illuminate\Support\Facades\Schema;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FieldsExistsInTableRule implements ValidationRule
{
    public function __construct(protected string $table, protected string $connection)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $columns = $this->getTableColumns();

        foreach ($value as $field) {
            if (!in_array($field, $columns)) {
                $fail('One or more fields do not exist in the specified table.');
            }
        }
    }

    protected function getTableColumns(): array
    {
        $schema = Schema::connection($this->connection);
        $columns = [];

        if ($schema->hasTable($this->table)) {
            $tableColumns = $schema->getColumnListing($this->table);
            $columns = array_map(function ($column) {
                return $column;
            }, $tableColumns);
        }

        return $columns;
    }
}
