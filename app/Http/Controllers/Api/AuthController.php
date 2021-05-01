<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    /**
     * Display a token of the login.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $fields = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

       
        if ( ! Auth::attempt($fields)) {
            return $this->responseError(['message' => 'Cridentials Failed !']);
        }

        $response = [
            'token' => Auth::user()->createToken('My-Apps')->plainTextToken
        ];

        return $this->responseOk($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $this->validate($request, [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $fields['id'] = Str::uuid();
        $fields['password'] = Hash::make($fields['password']);
        $User = User::create($fields);

        $response = [
            'user' => $User,
            'personal_access_token' => $User->createToken('My-Apps')->plainTextToken
        ];

        return $this->responseOk($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return $this->responseOk(Auth::user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $fields = $this->validate($request, [
            'name' => 'string|min:3',
            'email' => 'email|unique:users,email,' . Auth::id(),
            'password' => 'min:6|confirmed'
        ]);

        if (isset($fields['password']) && ! empty($fields['password'])) {
            $fields['password'] = Hash::make($fields['password']);
        }

        $User  = User::find(Auth::id())->update($fields);

        $response = [
            'user' => $User
        ];

        return $this->responseOk($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $response = [
            'delete' => Auth::user()->currentAccessToken()->delete()
        ];

        return $this->responseOk($response, 201);
    }
}
