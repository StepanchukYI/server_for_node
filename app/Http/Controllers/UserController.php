<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function getUserById( User $user )
	{
		return  $user;
	}

	public function postUserById( Request $request)
	{
		return  ['user_id' => $request->get('id')];
	}
}
