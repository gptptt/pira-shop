<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExampleResourceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'This is a protected resource',
            'data' => [
                'example' => 'This endpoint is protected with Sanctum',
                'user' => auth()->user()->name,
                'roles' => auth()->user()->getRoleNames(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Example of role-based access control
        if (!auth()->user()->hasPermissionTo('create-resources')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have permission to create resources'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Resource created successfully',
            'data' => [
                'name' => $request->name,
                'created_by' => auth()->user()->name
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $id,
                'name' => 'Example Resource ' . $id,
                'description' => 'This is an example of a protected resource with ID: ' . $id
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Example of role-based access control
        if (!auth()->user()->hasRole(['admin', 'editor'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only admins and editors can update resources'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Resource updated successfully',
            'data' => [
                'id' => $id,
                'name' => $request->name,
                'updated_by' => auth()->user()->name
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Example of role-based access control
        if (!auth()->user()->hasRole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Only admins can delete resources'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Resource deleted successfully',
            'data' => [
                'id' => $id
            ]
        ]);
    }
} 