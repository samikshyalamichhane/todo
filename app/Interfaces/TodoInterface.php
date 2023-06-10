<?php

namespace App\Interfaces;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface TodoInterface{
    public function fetchAll(): Collection;

    public function fetch(int $todo):Todo;

    public function create(Request $request): Todo;

    public function update(Request $request, int $todo): Todo;

    public function delete(int $todo): bool;
}