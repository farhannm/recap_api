<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $data = Role::all();
        return response()->json($data);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Role $role)
    {
        //
    }


    public function edit(Role $role)
    {
        //
    }


    public function update(Request $request, Role $role)
    {
        //
    }


    public function destroy(Role $role)
    {
        //
    }


    public function success($data, $message = "Login Berhasil"){
        return response()->json([
            'code' => 200,
            'message' => $message,
            'data' => $data
        ]);
    }


    public function error($message){
        return response()->json([
            'code' => 400,
            'message' => $message
        ], 400);
    }
}
