<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserManagementController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function users()
    {
        try {
            $users = $this->userService->getUsers();
            return sendResponse(true, 'Users retrieved successfully.', $users, Response::HTTP_OK);
        } catch (Throwable $e) {
            Log::error('Get Users Error: ' . $e->getMessage());
            return sendResponse(false, 'Something went wrong.', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
