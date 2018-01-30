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

	/**
	 * @param Request $request
	 *
	 * @return User
	 *
	 */
	public function postUserById( Request $request)
	{
<<<<<<< HEAD
		return  User::find($request->get('id'));
=======
		return  ['user_id' => $request];
>>>>>>> commit put REQUEsT
	}
}
