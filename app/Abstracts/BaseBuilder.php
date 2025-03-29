<?php

namespace App\Foundation\Abstracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

abstract class BaseBuilder extends Builder
{
    public function filterEqual(string $column, string $value): static
    {
        $this->where($column, '=', $value);

        return $this;
    }

    public function filterNull(string $column): static
    {
        $this->where($column, '=', null);

        return $this;
    }

    public function filterArray(string $column, array $values)
    {
        $this->whereIn($column, $values);

        return $this;
    }

    public function filterFromDate(string $column, string $value): static
    {
        //todo check if timestamp in model not Y-m-D
        $this->where($column, '>=', $value . ' 00:00:00');

        return $this;
    }

    public function filterToDate(string $column, string $value): static
    {
        $this->where($column, '<=', $value . ' 23:59:59');

        return $this;
    }

    public function filterHasRelationEqual(string $relation, string $column, string $value): static
    {
        $this->whereHas($relation, function ($query) use ($column, $value) {
            $query->where($column, '=', $value);
        });

        return $this;
    }

    public function filterHasRelationEqualKeyValue(string $relation,
                                                   string $key,
                                                   string $value,
                                                   string $field = 'field',
                                                   string $value_column = 'value',
                                                   string $operation = "="): static
    {
            $this->where(function($query) use ($relation, $field, $operation, $value_column, $value,$key) {
                // Apply filter based on condition

                $query->whereHas($relation, function ($query) use ($field, $operation, $value_column, $value,$key) {
                    $query->where($field, $key);
                    $query->where($value_column, $operation, $value);
                })
                    // Include demands without the specific field in conditions
                    ->orWhereDoesntHave($relation, function ($query) use ($field,$key) {
                        $query->where($field, $key);
                    });
        }) ;

        return $this;
    }

    public function filterHasRelationEqualNumericKeyValue(string $relation,
                                                   string $key,
                                                   string $value,
                                                   string $field = 'field',
                                                   string $value_column = 'value',
                                                   string $operation = "="): static
    {
            $this->where(function($query) use ($relation, $field, $operation, $value_column, $value,$key) {
                // Apply filter based on condition

                $query->whereHas($relation, function ($query) use ($field, $operation, $value_column, $value,$key) {
                    $query->where($field, $key);
                    $query->whereRaw("CAST({$value_column} AS DECIMAL) {$operation} ?", $value);
                })
                    // Include demands without the specific field in conditions
                    ->orWhereDoesntHave($relation, function ($query) use ($field,$key) {
                        $query->where($field, $key);
                    });
        }) ;

        return $this;
    }

    public function filterDoesntHaveRelationEqual(string $relation, string $column, string $value): static
    {
        $this->whereDoesntHave($relation, function ($query) use ($column, $value) {
            $query->where($column, '=', $value);
        });

        return $this;
    }

    public function filterHasRelationArray(string $relation, string $column, array $values)
    {
        $this->whereHas($relation, function ($query) use ($column, $values) {
            $query->whereIn($column, $values);
        });

        return $this;
    }

    public function filterGenderRelationArray(string $relation, array $values)
    {
        $this->whereHas($relation, function ($query) use ($values) {
            $query->where('field', 'gender')
            ->whereRaw('JSON_CONTAINS(`value`, ?)', [json_encode($values)]);
        })
        ->orWhereDoesntHave($relation, function ($query) {
            $query->where('field', 'gender');
        });

        return $this;
    }

    public function filterLike(string $column, string $value)
    {
        $this->where($column, 'like', '%' . $value . '%');

        return $this;
    }

    public function filterLikeEndWith(string $column, string $value)
    {
        $this->where($column, 'like', '%' . $value);

        return $this;
    }

    public function filterMultiLike(array $columns, string $value)
    {
        //whereAny //https://techvblogs.com/blog/laravel-10-47-whereany-whereall
        $this->where(function ($query) use ($columns, $value) {
            foreach ($columns as $column) {
                $query->orWhere($this->getTable() . ".{$column}", 'LIKE', '%' . $value . '%');
            }
        });

        return $this;
    }

    public function filterMultiEqual(array $columns, string $value)
    {
        //whereAny //https://techvblogs.com/blog/laravel-10-47-whereany-whereall
        $this->where(function ($query) use ($columns, $value) {
            foreach ($columns as $column) {
                $query->orWhere($this->getTable() . ".{$column}",  $value );
            }
        });

        return $this;
    }

    public function filterGraterThanEqual(string $column, string $value)
    {
        $this->where($column, '>=', $value);

        return $this;
    }

    public function filterGraterThan(string $column, string $value)
    {
        $this->where($column, '>', $value);

        return $this;
    }

    public function filterLessThanEqual(string $column, string $value)
    {
        $this->where($column, '<=', $value);

        return $this;
    }

    public function filterHasRelationLike(string $relation, string $column, string $value)
    {
        $this->whereHas($relation, function ($query) use ($column, $value) {
            $query->where($column, 'like', '%' . $value . '%');
        });

        return $this;
    }

    public function filterOrHasRelationsLike(string $relation, array $columns, string $value)
    {
        $this->where(function ($q1) use ($relation, $columns, $value) {
            foreach ($columns as $column)
                $q1->orWhereHas($relation, function ($query) use ($column, $value, $relation) {
                    $query->where($column, 'like', '%' . $value . '%');
                });
        });

        return $this;
    }

    public function filterHasRelationOrColumnLike(array $relations, string $myColumn, string $value)
    {

        $this->where(function ($query1) use ($relations, $value, $myColumn) {
            foreach ($relations as $relation => $column) {
                $query1->OrWhereHas($relation, function ($query) use ($column, $value) {
                    $query->where($column, 'like', '%' . $value . '%');
                });
            }
            $query1->orWhere($myColumn, 'like', '%' . $value . '%');

        });

        return $this;
    }

    public function filterOrHasRelationLike(string $relation, string $column, string $value)
    {
        $this->orWhereHas($relation, function ($query) use ($column, $value) {
            $query->where($column, 'like', '%' . $value . '%');
        });

        return $this;
    }

    public function filterHasRelationGraterThan(string $relation, string $column, string $value)
    {
        $this->whereHas($relation, function ($query) use ($column, $value) {
            $query->where($column, '>', $value);
        });

        return $this;
    }

    public function filterHasRelationGraterThanEqual(string $relation, string $column, string $value)
    {
        $this->whereHas($relation, function ($query) use ($column, $value) {
            $query->where($column, '>=', $value);
        });

        return $this;
    }

    public function filterHasRelationLessThan(string $relation, string $column, string $value)
    {
        $this->whereHas($relation, function ($query) use ($column, $value) {
            $query->where($column, '<', $value);
        });

        return $this;
    }

    public function filterHasRelationLessThanEqual(string $relation, string $column, string $value)
    {
        $this->whereHas($relation, function ($query) use ($column, $value) {
            $query->where($column, '<=', $value);
        });

        return $this;
    }

    public function filterHasRelationHavingSumGraterThanEqual(string $relation, string $column, string $value)
    {
        $this->whereHas($relation, function ($query) use ($column, $value) {
            $query->havingRaw('sum(' . $column . ') >= ' . $value);
        });

        return $this;
    }

    public function filterHasRelationHavingSumLessThanEqual(string $relation, string $column, string $value)
    {
        $this->whereHas($relation, function ($query) use ($column, $value) {
            $query->havingRaw('sum(' . $column . ') <= ' . $value);
        });

        return $this;
    }

    public function filterDoesNotHaveRelationArray(string $relation, string $column, array $values)
    {
        $this->whereHas($relation, function ($query) use ($column, $values) {
            $query->whereNotIn($column, $values);
        });

        return $this;
    }

    public function filterNotEmpty(string $column)
    {
        $this->whereNotNull($column)
            ->where($column, '!=', 0)
            ->where($column, '!=', '');

        return $this;
    }

    public function filterLimit(int $limit)
    {
        $this->limit($limit);

        return $this;
    }

    /*-------------------SORT DEPARTMENT---------------------*/

    public function sortBy(string $column = 'created_at', string $direction = 'desc')
    {
        $this->orderBy($column, $direction);

        return $this;
    }

    public function orderByRelation(string $relation, string $column, string $direction = 'desc')
    {
        $relationTable = $this->getRelation($relation)->getRelated()->getTable();
        $this->joinToRelation($relation);

        $this->orderBy("{$relationTable}.{$column}", $direction);

        return $this;
    }

    public function orderByOwnTable(string $column, string $direction = 'desc')
    {
        $this->orderBy($this->getTable() . '.' . $column, $direction);

        return $this;
    }

    public function bringToTopByColumn(string $column, array $values)
    {
        $idColumn = $this->model->getTable() . ".{$column}";
        $placeholders = implode(',', array_unique($values));
        $this->orderByRaw("FIELD ($idColumn,$placeholders) DESC");

        return $this;
    }

    public function joinToRelation(string $relation)
    {
        $relationClass = $this->getRelation($relation);

        $ownerTable = $this->model->getTable();
        $relationTable = $relationClass->getRelated()->getTable();
        $localKey = $this->model->getKeyName();
        $foreignKey = $relationClass->getForeignKeyName();

        if ($this->isTableJoined($this, $relationTable)) {
            goto skip;
        }

        if ($relationClass instanceof HasOne || $relationClass instanceof HasMany) {
            $this->join(
                $relationTable,
                "{$relationTable}.{$foreignKey}",
                '=',
                $ownerTable . ".{$localKey}"
            );
        }
        if ($relationClass instanceof BelongsTo) {
            $this->join(
                $relationTable,
                "{$relationTable}.{$localKey}",
                '=',
                $ownerTable . ".{$foreignKey}"
            );
        }

        skip:
        $this->select("{$ownerTable}.*");

        return $this;
    }

    public function isTableJoined($query, $table)
    {
        $joins = $query->getQuery()->joins;

        if ($joins == null) {
            return false;
        }

        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }

        return false;
    }

    //return column and direction of $param string (example:price_desc => [price,desc])
    protected function extractSortFromQuery(string $param): object
    {
        return (object)[
            'column' => Str::beforeLast($param, '_'),
            'direction' => Str::afterLast($param, '_'),
        ];
    }

    protected function extractRange(string $param): object
    {
        // Define the suffixes for operations
        $suffixes = ["_le", "_e", "_ge", "_g", "_l"];

        // Extract the operation character from the parameter
        $operation_char = Str::afterLast($param, '_');

        // Remove the suffix from the parameter to get the key
        $key = Str::replace($suffixes, "", $param);

        // Use match to return the corresponding operator
        $operation = match ($operation_char) {
            "le" => "<=",
            "l" => "<",
            "e" => "=",
            "ge" => ">=",
            "g" => ">",
            default => "=",
        };

        // Return the object with key and operation
        return (object)[
            'key' => $key,
            'operation' => $operation
        ];
    }

    public function getTable(): string
    {
        return $this->model->getTable();
    }
}
