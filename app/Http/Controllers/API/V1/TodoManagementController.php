<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\BulkDeleteTodoRequest;
use App\Http\Requests\API\V1\TodoRequest;
use App\Http\Resources\API\V1\TodoCollection;
use App\Http\Resources\API\V1\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TodoManagementController extends Controller
{
    public function todos(Request $request)
    {
        try {
            $user = request()->user();
            if (!$user) {
                return sendResponse(false, 'Unauthorized', null, Response::HTTP_UNAUTHORIZED);
            }
            $query = Todo::where('user_id', $user->id);
            if ($request->has('search')) {
                $query->whereLike($request->input('search'));
            }
            if ($request->has('completed')) {
                $query->completed();
            }
            if ($request->has('incompleted')) {
                $query->notCompleted();
            }
            $todos = $query->paginate($request->input('per_page', 10));
            $todos->setPageName('page');

            return sendResponse(true, 'Todos fetched successfully.', new TodoCollection($todos), Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Get Todos Error: ' . $e->getMessage());
            return sendResponse(false, 'Something went wrong.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function todo($id)
    {
        try {
            $user = request()->user();
            if (!$user) {
                return sendResponse(false, 'Unauthorized', null, Response::HTTP_UNAUTHORIZED);
            }
            $todo = Todo::where('user_id', $user->id)->where('id', $id)->first();
            if (!$todo) {
                return sendResponse(false, 'Todo not found.', null, Response::HTTP_NOT_FOUND);
            }
            return sendResponse(true, 'Todo fetched successfully.', new TodoResource($todo), Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Get Todo Error: ' . $e->getMessage());
            return sendResponse(false, 'Something went wrong.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createTodo(TodoRequest $request)
    {
        try {
            $user = request()->user();
            $validated = $request->validated();
            if (!$user) {
                return sendResponse(false, 'Unauthorized', null, Response::HTTP_UNAUTHORIZED);
            }
            $validated['user_id'] = $user->id;
            $todo = Todo::create($validated);
            return sendResponse(true, 'Todo created successfully.', new TodoResource($todo), Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            Log::error('Create Todo Error: ' . $e->getMessage());
            return sendResponse(false, 'Something went wrong.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function updateTodo(TodoRequest $request, $id)
    {
        try {
            $user = request()->user();
            $validated = $request->validated();
            if (!$user) {
                return sendResponse(false, 'Unauthorized', null, Response::HTTP_UNAUTHORIZED);
            }
            $todo = Todo::where('user_id', $user->id)->where('id', $id)->first();
            if (!$todo) {
                return sendResponse(false, 'Todo not found.', null, Response::HTTP_NOT_FOUND);
            }
            $todo->update($validated);
            return sendResponse(true, 'Todo updated successfully.', new TodoResource($todo), Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Update Todo Error: ' . $e->getMessage());
            return sendResponse(false, 'Something went wrong.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function deleteTodo($id)
    {
        try {
            $user = request()->user();
            if (!$user) {
                return sendResponse(false, 'Unauthorized', null, Response::HTTP_UNAUTHORIZED);
            }
            $todo = Todo::where('user_id', $user->id)->where('id', $id)->first();
            if (!$todo) {
                return sendResponse(false, 'Todo not found.', null, Response::HTTP_NOT_FOUND);
            }
            $todo->delete();
            return sendResponse(true, 'Todo deleted successfully.', null, Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Delete Todo Error: ' . $e->getMessage());
            return sendResponse(false, 'Something went wrong.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function bulkDeleteTodo(BulkDeleteTodoRequest $request)
    {
        try {
            $user = request()->user();
            if (!$user) {
                return sendResponse(false, 'Unauthorized', null, Response::HTTP_UNAUTHORIZED);
            }
            $ids = $request->validated('ids');
            $query = Todo::where('user_id', $user->id)->whereIn('id', $ids);
            $query->delete();
            return sendResponse(true, 'Todos deleted successfully.', null, Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Bulk Delete Todo Error: ' . $e->getMessage());
            return sendResponse(false, 'Something went wrong.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function completeTodo($id)
    {
        try {
            $user = request()->user();
            if (!$user) {
                return sendResponse(false, 'Unauthorized', null, Response::HTTP_UNAUTHORIZED);
            }
            $todo = Todo::where('user_id', $user->id)->where('id', $id)->first();
            if (!$todo) {
                return sendResponse(false, 'Todo not found.', null, Response::HTTP_NOT_FOUND);
            }
            $todo->update(['is_completed' => true, 'completed_at' => now()]);
            return sendResponse(true, 'Todo completed successfully.', new TodoResource($todo), Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Complete Todo Error: ' . $e->getMessage());
            return sendResponse(false, 'Something went wrong.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function incompleteTodo($id)
    {
        try {
            $user = request()->user();
            if (!$user) {
                return sendResponse(false, 'Unauthorized', null, Response::HTTP_UNAUTHORIZED);
            }
            $todo = Todo::where('user_id', $user->id)->where('id', $id)->first();
            if (!$todo) {
                return sendResponse(false, 'Todo not found.', null, Response::HTTP_NOT_FOUND);
            }
            $todo->update(['is_completed' => false, 'completed_at' => null]);
            return sendResponse(true, 'Todo incomplete successfully.', new TodoResource($todo), Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Incomplete Todo Error: ' . $e->getMessage());
            return sendResponse(false, 'Something went wrong.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function dueTodo($id)
    {
        try {
            $user = request()->user();
            if (!$user) {
                return sendResponse(false, 'Unauthorized', null, Response::HTTP_UNAUTHORIZED);
            }
            $todo = Todo::where('user_id', $user->id)->where('id', $id)->first();
            if (!$todo) {
                return sendResponse(false, 'Todo not found.', null, Response::HTTP_NOT_FOUND);
            }
            $todo->update(['due_date' => now()]);
            return sendResponse(true, 'Todo due successfully.', new TodoResource($todo), Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Due Todo Error: ' . $e->getMessage());
            return sendResponse(false, 'Something went wrong.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
