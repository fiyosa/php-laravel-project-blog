<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function index(Request $request){		
		$request->session()->forget('user_id');
		$request->session()->forget('user_isAdmin');
		$request->session()->forget('user_name');
		
		return view('home.home', [
			'title' => 'Home',
			'active' => 'home'
		]);
	}
}
