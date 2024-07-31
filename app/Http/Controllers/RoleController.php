<?php

// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use App\Models\Role;
// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;

// class RoleController extends Controller
// {
//     public function create(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'name' => 'required|string|max:255|unique:roles',
//         ]);

//         if ($validator->fails()) {
//             return response()->json($validator->errors(), 422);
//         }

//         $role = Role::create([
//             'name' => $request->name,
//         ]);

//         return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
//     }

//     public function assign(Request $request, $userId)
//     {
//         $validator = Validator::make($request->all(), [
//             'roles' => 'required|array',
//             'roles.*' => 'exists:roles,id'
//         ]);

//         if ($validator->fails()) {
//             return response()->json($validator->errors(), 422);
//         }

//         $user = User::findOrFail($userId);
//         $roleIds = $request->input('roles'); // Array of role IDs

//         $user->roles()->sync($roleIds);

//         return response()->json(['message' => 'Roles assigned successfully']);
//     }
    
// }

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Role;
// use App\Models\User;

// class RoleController extends Controller
// {
//     public function createRole(Request $request)
//     {
//         $request->validate(['name' => 'required|string|unique:roles']);

//         $role = Role::create(['name' => $request->name]);

//         return response()->json($role);
//     }

//     public function assignRole(Request $request, $userId)
//     {
//         $request->validate(['role_id' => 'required|integer|exists:roles,id']);

//         $user = User::findOrFail($userId);
//         $role = Role::findOrFail($request->role_id);

//         $user->roles()->attach($role);

//         return response()->json($user->load('roles'));
//     }

//     public function getUserRoles($userId)
//     {
//         $user = User::with('roles')->findOrFail($userId);

//         return response()->json($user);
//     }
// }


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function createRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string|max:255|unique:roles',
        ]);

        $role = Role::create([
            'role' => $request->role,
        ]);

        return response()->json(['message' => 'Role created successfully'], 201);
    }

    public function assignRoles(Request $request)
    {
        $request->validate([
            'roles' => 'required',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = $request->user();
        $user->roles()->syncWithoutDetaching($request->roles);

        return response()->json(['message' => 'Roles assigned successfully']);
    }

    public function userRoles(Request $request)
    {
        $user = $request->user();
        $roles = $user->roles;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name
            ],
            'roles' => $roles
        ]);
        
    }
}
