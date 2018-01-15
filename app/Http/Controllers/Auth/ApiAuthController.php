<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Carbon\Carbon;
use JWTAuth;
use JWTAuthException;
use App\User;

class ApiAuthController extends Controller
{

	public function __construct()
	{
		$this->user = new User;
	}

	public function login(Request $request){
		try {
			$token = JWTAuth::attempt($request->only('email', 'password'), [
				'exp' => Carbon::now()->addWeek()->timestamp,
			]);
		} catch (JWTException $e) {
			return response()->json([
				'error' => 'Could not authenticate',
			], 500);
		}

		if (!$token) {
			return response()->json([
				'error' => 'Could not authenticate',
			], 401);
		} else {
			$data = [];
			$meta = [];

			$data['name'] = $request->user()->name;
			$meta['token'] = $token;

			return response()->json([
				'data' => $data,
				'meta' => $meta
			]);
		}
	}


	public function getAuthUser(Request $request){
		$user = JWTAuth::toUser($request->token);
		return response()->json(['result' => $user]);
	}
}
