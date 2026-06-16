<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(Role::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomRole' => 'required|string|max:255|unique:roles,nomRole',
            'description' => 'nullable|string',
        ]);

        $role = Role::create($request->only('nomRole', 'description'));

        return response()->json([
            'message' => 'Rôle ajouté avec succès',
            'role' => $role
        ], 201);
    }

    public function show(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rôle introuvable'], 404);
        }

        return response()->json($role);
    }

    public function update(Request $request, string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rôle introuvable'], 404);
        }

        $request->validate([
            'nomRole' => 'required|string|max:255|unique:roles,nomRole,' . $id . ',idRole',
            'description' => 'nullable|string',
        ]);

        $role->update($request->only('nomRole', 'description'));

        return response()->json([
            'message' => 'Rôle modifié avec succès',
            'role' => $role
        ]);
    }

    public function destroy(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rôle introuvable'], 404);
        }

        $role->delete();

        return response()->json([
            'message' => 'Rôle supprimé avec succès'
        ]);
    }
}