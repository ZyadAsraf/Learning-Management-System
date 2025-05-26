<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->paginate(10);
        return Inertia::render('Roles/Index', [
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);
        $role = Role::create(['name' => $request->name]);
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Role created', 'role' => $role]);
        }
        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);
        $role->update(['name' => $request->name]);
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Role updated', 'role' => $role]);
        }
        return back()->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Role deleted']);
        }
        return back()->with('success', 'Role deleted successfully.');
    }
} 