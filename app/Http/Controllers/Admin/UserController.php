<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserService;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $users = $this->service->userQuery()->where('role', '!=', 'admin')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function toggleStatus(User $user)
    {
        $this->service->toggleUserStatus($user);

        $notify[] = ['success', 'User status updated successfully.'];
        return back()->withNotify($notify);
    }

    public function destroy(User $user)
    {
        $this->service->deleteUser($user);
        $notify[] = ['success', 'User deleted successfully.'];
        return back()->withNotify($notify);
    }
}
