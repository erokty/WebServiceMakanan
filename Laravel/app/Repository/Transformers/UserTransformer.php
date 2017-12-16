<?php

namespace App\Repository\Transformers;

class UserTransformer extends Transformer{
    public function transform($user){
        return [
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address,
            'birthdate' => $user->birthdate,
            'phone_number' => $user->phone_number,
            'is_admin' => $user->is_admin,
            'api_token' => $user->api_token,
        ];
    }
}