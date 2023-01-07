<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::query();
        if (request()->has('name') || request()->has('username') || request()->has('email'))
        {
            $users->where('name', 'like', '%'.request('name').'%')
                ->where('username', 'like', '%'.request('username').'%')
                ->where('email', 'like', '%'.request('email').'%');
        }
        return $users->orderBy('id', 'DESC')->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
            ]);
        }
        catch(\Throwable $error)
        {
            DB::rollBack();
            return response()->json(
                [
                    'message' => "Failed to add user",
                    'type' => 'error',
                    'error' => $error
                ],
                500
            );
        }
        DB::commit();
        return response()->json(
            [
                'message' => "{$user->name} has been successfully created",
                'type' => 'success',
            ],
            200
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        DB::beginTransaction();
        try
        {
            $user = Product::findOrFail($id)->update($request->all());
        }
        catch(\Throwable $error)
        {
            DB::rollBack();
            return response()->json(
                [
                    'message' => "Failed to update product",
                    'type' => 'error',
                    'error' => $error
                ],
                500
            );
        }
        DB::commit();
        return response()->json(
            [
                'message' => "{$user->name} has been successfully updated",
                'type' => 'success',
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try
        {
            $user->delete();
        }
        catch(\Throwable $th)
        {
            DB::rollBack();
            return response()->json(
                [
                    'message' => "Failed to delete product",
                    'type' => 'error',
                    'error' => $error
                ],
                500
            );
        }
        DB::commit();
        return response()->json(
            [
                'message' => "{$user->name} has been successfully deleted",
                'type' => 'success',
            ],
            200
        );
    }
}
