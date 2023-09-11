<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TaskFilterService
{

    static function build(Request $request, Builder $query): Builder
    {
        if ($request->has('filters.status')) $query->ofStatus($request->get('filters.status'));
        if ($request->has('filters.priority')) $query->priority($request->get('filters.priority.from', 1), $request->get('filters.priority.to', 5));
        if ($request->has('filters.title')) $query->title($request->get('filters.title'));
        if ($request->has('sort')) collect($request->get('sort'))->each(function ($el) use ($query) {
            list($name, $order) = explode(':', $el) + ['created_at', 'asc'];
            $query->orderBy($name, $order);
        });
        return $query;
    }
}
