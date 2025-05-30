<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\User;

use Illuminate\Support\Facades\Hash;

class StudentUserController extends Controller
{
    public function index()
    {
        return User::with('role')
            ->whereHas('role', fn($q) => $q->where('name', 'student'))
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        
        ]);

        // Assume role_id for student = 1 (check DB if unsure)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3,
        ]);

        return response()->json($user, 201);
    }

public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'sometimes|string',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
        'password' => 'sometimes|string|min:6',
    ]);
   $user->name = $request->name;
$user->email = $request->email;
    if ($request->has('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return response()->json($user);
}


    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
