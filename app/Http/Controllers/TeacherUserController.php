<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherUserController extends Controller
{
    /**
     * Display a listing of the teachers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get users with role_id = 2 (teachers)
        $teachers = User::where('role_id', 2)->get();
        return response()->json($teachers);
    }

    /**
     * Store a newly created teacher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $teacher = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2, // Teacher role id
        ]);

        return response()->json($teacher, 201);
    }

    /**
     * Update the specified teacher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Ensure the user is a teacher
        if ($user->role_id !== 2) {
            return response()->json(['message' => 'User is not a teacher'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password if provided
        if ($request->has('password') && !empty($request->password)) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return response()->json($user);
    }

    /**
     * Remove the specified teacher from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Ensure the user is a teacher
        if ($user->role_id !== 2) {
            return response()->json(['message' => 'User is not a teacher'], 403);
        }

        $user->delete();

        return response()->json(null, 204);
    }
}