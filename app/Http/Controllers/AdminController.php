<?php

namespace App\Http\Controllers;

use CURLFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminController extends Controller
{
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

	private function api_form($url, $method, $content = array()){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: multipart/form-data', 
			"Authorization: Bearer ".env('BACKEND_TOKEN')
		));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = json_decode(curl_exec($ch));
		$resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$result->httpStatus = $resultCode;
		return $result;
	}

	private function auth(){
		if(!session('user_id') || !session('user_name')){
			return 0;
		}
		return 1;
	}

  public function index(Request $request){
		if(!$this->auth()) return redirect('/');

		$url = env('BACKEND_API') . 'v1/blog/' . session('user_id');
		$result = $this->api($url, 'GET');

		if($result->httpStatus != 200 || isset($result->error) ){
			return redirect('/');
		}

		return view('dashboard.index', [
			'title' => 'Dashboard',
			'posts' => $result->message,
			'admin' => $result
		]);
	}

  public function posts(Request $request){
		if(!$this->auth()) return redirect('/');

		$url = env('BACKEND_API') . 'v1/blog/' . session('user_id').'?perPage=1000';
		$result = $this->api($url, 'GET');
		if($result->httpStatus != 200 || isset($result->error) ){
			return redirect('/');
		}

		return view('dashboard.posts.index', [
			'title' => 'Dashboard',
			'posts' => $result->message
		]);
	}

  public function show(Request $request, $slug){
		if(!$this->auth()) return redirect('/');

		$url = env('BACKEND_API') . 'v1/blog/slug/' . $slug;
		$result = $this->api($url, 'GET');

		if($result->httpStatus != 200 || isset($result->error) ){
			return redirect('/');
		}

		return view('dashboard.posts.show', [
			'title' => 'Dashboard',
			'post' => $result->message[0]
		]);
	}

  public function create_show(Request $request){
		if(!$this->auth()) return redirect('/');

		$url = env('BACKEND_API') . 'v1/category';
		$result = $this->api($url, 'GET');

		if($result->httpStatus != 200 || isset($result->error) ){
			return redirect('/');
		}

		return view('dashboard.posts.create', [
			'title' => 'Dashboard',
			'categories' => $result->message
		]);
	}

  public function create(Request $request){
		if(!$this->auth()) return redirect('/');

		$request->validate([
			'title' => 'required|max:255',
			'category_id' => 'required',
			'image' => 'image|file|max:1024',
			'body' => 'required',
			'image' => 'image|mimes:jpeg,png,jpg|max:1024'
		]);

		$url = env('BACKEND_API') . 'v1/blog';
		$slug = strtolower(trim($request->title));

		if(strpos($slug, " ") !== false){
			$slug = str_replace(" ", "-", $slug) .'-'. strval(rand(10000 , 99999));
		}
		else{
			$slug = $slug .'-'. strval(rand(10000 , 99999));
		}

		if($_FILES['image']['tmp_name']){
			$image = new CURLFile($_FILES['image']['tmp_name'], $_FILES['image']['type'], $_FILES['image']['name']);
		}
		else{
			$image = '';
		}

		$content = array(
			'user_id' => session('user_id'),
			'category_id' => $request->category_id,
			'title' => $request->title,
			'slug' => $slug,
			'excerpt' => Str::limit(strip_tags($request->body), 100),
			'body' => $request->body,
			'image' => $image
		);
		
		$result = $this->api_form($url, 'POST', $content);
		if($result->httpStatus != 201 || isset($result->error) ){
			return redirect('/dashboard/posts')->with('failed', 'Failed to add post');
		}

		return redirect('/dashboard/posts')->with('success', 'New post has been added');
	}

	public function edit_show(Request $request, $slug){
		if(!$this->auth()) return redirect('/');

		$url = env('BACKEND_API') . 'v1/blog/slug/' . $slug;
		$result_post = $this->api($url, 'GET');

		$url = env('BACKEND_API') . 'v1/category';
		$result_categories = $this->api($url, 'GET');

		$result = $result_post;
		if($result->httpStatus != 200 || isset($result->error) ){
			return redirect('/');
		}

		return view('dashboard.posts.edit', [
			'title' => 'Dashboard',
			'post' => $result_post->message[0],
			'categories' => $result_categories->message
		]);
	}

  public function edit(Request $request, $slug){
		if(!$this->auth()) return redirect('/');

		$request->validate([
			'category_id' => 'required',
			'title' => 'required|max:255',
			'image' => 'image|file|max:1024',
			'body' => 'required',
			'image' => 'image|mimes:jpeg,png,jpg|max:1024'
		]);

		$url = env('BACKEND_API') . 'v1/blog/' . $slug;

		if($_FILES['image']['tmp_name']){
			$image = new CURLFile($_FILES['image']['tmp_name'], $_FILES['image']['type'], $_FILES['image']['name']);
		}
		else{
			$image = '';
			
		}

		$content = array(
			'category_id' => $request->category_id,
			'title' => $request->title,
			'excerpt' => Str::limit(strip_tags($request->body), 100),
			'body' => $request->body,
			'image' => $image
		);		
		
		$result = $this->api_form($url, 'PUT', $content);
		if($result->httpStatus != 200 || isset($result->error) ){
			return redirect('/dashboard/posts')->with('failed', 'Failed to update post');
		}

		return redirect('/dashboard/posts')->with('success', 'Successfully updated post');
	}

	public function delete(Request $request, $slug){
		if(!$this->auth()) return redirect('/');
		
		$url = env('BACKEND_API') . 'v1/blog/' . $slug;
		$result = $this->api($url, 'DELETE');
		if($result->httpStatus != 200 || isset($result->error) ){
			return redirect('/dashboard/posts')->with('failed', 'Failed to delete post');
		}
		return redirect('/dashboard/posts')->with('success', 'Successfully deleted post');
	}
}
