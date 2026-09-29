<?php

namespace App\Http\Controllers;

use App\Services\Api\ApiAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    protected ApiAuthService $apiAuthService;

    public function __construct(ApiAuthService $apiAuthService)
    {
        $this->apiAuthService = $apiAuthService;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $result = $this->apiAuthService->login($request->only('email', 'password'));

        $statusCode = $result['status'] === 'success' ? 200 : 401;
        return response()->json($result, $statusCode);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $result = $this->apiAuthService->register($request->all());

        $statusCode = $result['status'] === 'success' ? 201 : 400;
        return response()->json($result, $statusCode);
    }

    public function googleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Access token required', 'errors' => $validator->errors()], 422);
        }

        $result = $this->apiAuthService->googleLogin($request->access_token);

        $statusCode = $result['status'] === 'success' ? 200 : 401;
        return response()->json($result, $statusCode);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $result = $this->apiAuthService->sendResetLink($request->email);

        $statusCode = $result['status'] === 'success' ? 200 : 400;
        return response()->json($result, $statusCode);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $result = $this->apiAuthService->resetPassword($request->only('token', 'email', 'password', 'password_confirmation'));

        $statusCode = $result['status'] === 'success' ? 200 : 400;
        return response()->json($result, $statusCode);
    }

    public function logout(Request $request)
    {
        $result = $this->apiAuthService->logout();

        return response()->json($result);
    }
}
