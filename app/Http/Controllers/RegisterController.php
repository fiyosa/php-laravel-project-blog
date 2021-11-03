<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
  public function index() {
		return view('login.login', [
			'title' => 'Register',
			'active' => 'login'
		]);
	}

	private function api($url, $method, $content){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method );
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type:application/json', 
			"Authorization: Bearer ".env('BACKEND_TOKEN')
		));
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = json_decode(curl_exec($ch));
		$resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$result->httpStatus = $resultCode;
		return $result;
	}


	public function store(Request $request){
		if($request->password !== $request->Cpassword) {
			return redirect('/register')->with('failed-register', 'Password does not match');
		}
		$request->validate([
			'username' => 'required|max:255',
			'email' => 'required|email:dns',
			'password' => 'required|min:5|max:255',
			'Cpassword' => 'required|min:5|max:255'
		]);

		$createPost = array(
			"name" => $request->username,
			"email" => $request->email,
			"password" => hash('sha256', $request->password)
		);

		$url = env('BACKEND_API') . 'v1/auth';

		$result = $this->api($url, 'POST', $createPost);
		
		if($result->httpStatus == 201 && !isset($result->error) ){
			return redirect('/login')->with('success', 'Create account successfull');
		}

		return redirect('/register')->with('failed-register', 'Failed create account');
	}
}
