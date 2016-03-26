<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\User;

class AuthController extends BaseController
{

    public function login()
    {
        $validator = \Validator::make($this->request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $this->request->only('email', 'password');

        $user = User::where('email', $this->request->get('email'))->first();

        if (!$token = \Auth::attempt($credentials)) {
            $validator->after(function ($validator) {
                $validator->errors()->add('password', 'Wrong Password');
            });
        }

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }

        return $this->response->array(['token' => $token]);
    }

    public function refreshToken()
    {
        $newToken = \auth::parseToken()->refresh();

        return $this->response->array(['token' => $newToken]);
    }


    public function signup()
    {
        $validator = \Validator::make($this->request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ], [
            'email.unique' => 'This email already registered',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }

        $email = $this->request->get('email');
        $password = $this->request->get('password');

        $user = new User();
        $user->email = $email;
        $user->password = app('hash')->make($password);
        $user->save();

        // Registered Event
        $token = \Auth::fromUser($user);

        return $this->response->array(['token' => $token]);
    }
}
