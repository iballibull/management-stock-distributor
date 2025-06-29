<?php

namespace App\Http\Controllers\User;

use App\Models\User\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::select('name', 'description')->get();
        return view('user.role', compact('roles'));
    }
}
