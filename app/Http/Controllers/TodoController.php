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
            // $todos = $this->todoRepository->fetchAll($request);
            $filter = $request->filter ?? '';
            $sort_by = $request->sortby ?? "id";
            $order = $request->order ?? "ASC";
            $per_page = $request->per_page ?? 10;
            
            $todos = $this->todoRepository->fetchAll($per_page, $sort_by, $order, $filter);
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
            $validator = Validator::make($request->all(), [ 
                "title"=> "sometimes",
                "description"=> "sometimes",
                "status"=> 'in:open,completed','progress',
                "image" => 'sometimes|mimes:jpeg,png,jpg,gif',
                "due_date"=> 'date_format:Y-m-d H:i:s'
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
                'user_id' => auth()->id()
            ];

            $todo = $this->todoRepository->create($data);


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
            if(!$todo){
                return ResponseHelper::errorHandling("You are not authorized to View this todo!!", Response::HTTP_FORBIDDEN);
               }
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
            $validator = Validator::make($request->all(), [ 
                "title"=> "sometimes",
                "description"=> "sometimes",
                "status"=> 'in:open,completed','progress',
                "image" => 'sometimes|mimes:jpeg,png,jpg,gif',
                "due_date"=> 'date_format:Y-m-d H:i:s'
            ]);

            if ($validator->fails()) {
                return ResponseHelper::errorHandling($validator->errors(), RESPONSE::HTTP_UNPROCESSABLE_ENTITY);
            }
            if($request->image != null){
                $request->request->add(['image' => $request->image->store('images/todos')]);
            }
            $request->request->add(['user_id' => auth()->id()]);


            $todo = $this->todoRepository->update($request, $id);
            if(!$todo){
                return ResponseHelper::errorHandling("You are not authorized to edit this todo!!", Response::HTTP_FORBIDDEN);
               }
        
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
        try{
            $deletedTodo = $this->todoRepository->delete($id);
           if(!$deletedTodo){
            return ResponseHelper::errorHandling("You are not authorized to delete!!", Response::HTTP_FORBIDDEN);
           }
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
