<?php

namespace App\Interfaces;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TodoInterface{
    // public function fetchAll($request): Collection;
    public function fetchAll(int $perPage, string $sort_by, string $order, string $filter): LengthAwarePaginator;


    public function fetch(int $todo):mixed;

    public function create(Request $request): Todo;

    public function update(Request $request, int $todo): mixed;

    public function delete(int $todo): bool;
}