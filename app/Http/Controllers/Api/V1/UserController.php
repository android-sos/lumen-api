<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Transformers\UserTransformer;
use App\User;

class UserController extends BaseController
{

    public function editPassword()
    {
        $user = $this->user();

        $validator = \Validator::make($this->request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|different:old_password',
            'password_confirmation' => 'required|same:password',
        ], [
            'password.confirmed' => 'Passwords do not match',
            'password_confirmation.same' => 'Passwords do not match',
            'password.different' => 'The old and new password can not be the same',
        ]);

        $auth = \Auth::once([
            'email' => $user->email,
            'password' => $this->request->get('old_password'),
        ]);

        if (!$auth) {
            $validator->after(function ($validator) {
                $validator->errors()->add('old_password', 'Wrong Pass');
            });
        }

        if ($validator->fails()) {
            return $this->errorBadRequest($validator->messages());
        }

        $user->password = bcrypt($this->request->get('password'));
        $user->password = app('hash')->make($this->request->get('password'));

        $user->save();

        return $this->response->noContent();
    }


    public function show()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }


    public function update()
    {
        $user = $this->user();

        $user->fill($this->request->input());

        $user->save();

        return $this->response->item($user, new UserTransformer());
    }
}
