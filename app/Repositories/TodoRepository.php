<?php
namespace App\Repositories;

use App\Interfaces\TodoInterface;
use App\Models\Todo;
use Illuminate\Foundation\Mix;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class TodoRepository implements TodoInterface{

    public function fetchAll(int $perPage, string $sort_by, string $order, string $filter): LengthAwarePaginator
    {
        return Todo::when($filter != null ,function ($query) use ($filter) {
            $query->where('status', $filter);
        })
        ->orderBy($sort_by, $order)
        ->paginate(10);
    }

    public function fetch(int $id): mixed
    {
        $Todo = Todo::findOrFail($id);
        if($Todo->user->id == auth()->user()->id){
            return $Todo;
        } 
        return null;
    }

    public function create($request): Todo
    {
        return Todo::create($request);
    }

    public function update($request, int $id): mixed
    {
        $Todo = Todo::findOrFail($id);
        if($Todo->user->id == auth()->user()->id){
            $Todo->update($request->all());
            return $Todo;
        } 
        
        return null;
    }

    public function delete(int $id): bool
    {
        $Todo = Todo::findOrFail($id);
        if($Todo->user->id == auth()->user()->id){
            $Todo->delete();
            return true;
        } 
        return false;
    }
}