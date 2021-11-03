<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
	public function index() {
		return view('login.login', [
			'title' => 'Login',
			'active' => 'login'
		]);
	}

	private function api($url, $method, $content = array()){
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

	public function authenticate(Request $request){
		$request->validate([
			'email' => 'required|email:dns',
			'password' => 'required|min:5|max:255',
		]);

		$authPost = array(
			"email" => $request->email,
			"password" => hash('sha256', $request->password)
		);

		$url = env('BACKEND_API') . 'v1/auth/validate';

		$result = $this->api($url, 'POST', $authPost);
		
		if($result->httpStatus != 200 || isset($result->error) ){
			return back()->with('loginError', 'Login failed!');
		}

		$request->session()->put('user_id', $result->user_id);
		$request->session()->put('user_isAdmin', $result->user_isAdmin);
		$request->session()->put('user_name', $result->user_name);

		return redirect('/dashboard');
	}
}
