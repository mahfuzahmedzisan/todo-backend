<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function getUsers(): Collection|User
    {
        return User::all();
    }

    public function getUserByField($value, $field = 'email')
    {
        return User::where($field, $value);
    }
}
