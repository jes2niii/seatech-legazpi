<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait Searchable
{
    protected function applySearch($query, Request $request, array $fields, array $relations = [])
    {
        if (! $request->filled('q')) {
            return $query;
        }

        $term = '%' . trim($request->q) . '%';

        return $query->where(function ($q) use ($term, $fields, $relations) {
            foreach ($fields as $field) {
                $q->orWhere($field, 'like', $term);
            }
            foreach ($relations as $relation => $relFields) {
                $q->orWhereHas($relation, function ($rq) use ($term, $relFields) {
                    $rq->where(function ($rqq) use ($term, $relFields) {
                        foreach ($relFields as $field) {
                            $rqq->orWhere($field, 'like', $term);
                        }
                    });
                });
            }
        });
    }
}
