<?php
namespace App\Repositories;

use App\Interfaces\TodoInterface;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TodoRepository implements TodoInterface{

    public function fetchAll($request): Collection
    {
        return Todo::where(function ($query) use ($request) {
            $query->where('status', $request->status);
        })->get();
    }

    public function fetch(int $id): Todo
    {
        return Todo::findOrFail($id);
    }

    public function create($data): Todo
    {
        return Todo::create($data);
    }

    public function update($data, int $id): Todo
    {
        $Todo = Todo::findOrFail($id);
        // dd($data);
        $Todo->update($data);
        return $Todo;
    }

    public function delete(int $id): bool
    {
        $Todo = Todo::findOrFail($id);
        $Todo->delete();
        return true;
    }
}