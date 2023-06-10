<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Repositories\TodoRepository;
use App\Helpers\ResponseHelper;
use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoResource;
use BadMethodCallException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TodoController extends Controller
{

    private $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function index(Request $request)
    {
        try{
            $todos = $this->todoRepository->fetchAll($request);
            return ResponseHelper::successHandler($todos, "Todos fetched successfully", RESPONSE::HTTP_OK);
        }
        catch(ModelNotFoundException){
            return ResponseHelper::errorHandling("No resource found!", Response::HTTP_NOT_FOUND);
        }
        catch(Exception $ex){ 
            return ResponseHelper::errorHandling($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(TodoRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'image' => $request->hasFile('image') ? $request->image->store('images/todos') : null,
                'status' => $request->status,
                'due_date' => $request->due_date,
            ];

            $todo = $this->todoRepository->create($data);

            DB::commit();

            return ResponseHelper::successHandler($data, "Todo created successfully", RESPONSE::HTTP_CREATED);
        }
        catch(BadMethodCallException $badMethodCallException){
            return ResponseHelper::errorHandling($badMethodCallException->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        catch(Exception $ex){
            return ResponseHelper::errorHandling($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try{
            $todo = $this->todoRepository->fetch($id);
            return ResponseHelper::successHandler($todo, "todo fetched successfully", RESPONSE::HTTP_OK);
        }
        catch(ModelNotFoundException){
            return ResponseHelper::errorHandling("No resource found!", Response::HTTP_NOT_FOUND);
        }
        catch(Exception $ex){ 
            return ResponseHelper::errorHandling($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    { 
        try {
            // DB::beginTransaction();
            $validator = Validator::make($request->all(), [ 
                "title"=> "sometimes",
            ]);

            if ($validator->fails()) {
                return ResponseHelper::errorHandling($validator->errors(), RESPONSE::HTTP_UNPROCESSABLE_ENTITY);
            }
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'image' => $request->hasFile('image') ? $request->image->store('images/todos') : null,
                'status' => $request->status,
                'due_date' => $request->due_date,
            ];

            $todo = $this->todoRepository->update($data, $id);
            // DB::commit();  
        
        return ResponseHelper::successHandler($todo, "todo updated successfully", RESPONSE::HTTP_OK);
    }
    catch(ModelNotFoundException){
        return ResponseHelper::errorHandling("Resource not found", Response::HTTP_NOT_FOUND);
    }
    catch(Exception $ex){
        return ResponseHelper::errorHandling($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    }

    public function destroy($id)
    {
        dd(auth()->user());
        try{
            $this->todoRepository->delete($id);
            return ResponseHelper::successHandler($data=[], "Todo deleted successfully", Response::HTTP_OK);
        }
        catch(ModelNotFoundException $modelNotFoundException){
            return ResponseHelper::errorHandling("Resource not found", Response::HTTP_NOT_FOUND);
        }
        catch(Exception $ex){
            return ResponseHelper::errorHandling($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
