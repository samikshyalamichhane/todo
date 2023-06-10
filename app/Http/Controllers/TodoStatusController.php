<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoStatusController extends Controller
{
    public function store(Request $request, Todo $todo)
    {
        $todo->update(['status' => "completed"]);
        return response()->json([
            "status" => "true",
            "message" => "Todo status changed to completed!!"
          ], 200);
    }
}
