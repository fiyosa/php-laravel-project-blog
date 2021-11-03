<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
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

	public function index(){
		$query = '';
		$title = '';
		
		if(request('user_id') && request('category_id')){
			$query = '/category?user_id='.request('user_id').'&&category_id='.request('category_id');
		}
		elseif (request('user_id')) {
			$query = '/category?user_id='.request('user_id');
			$title = 'user_id';
		}
		elseif (request('category_id')){
			$query = '/category?category_id='.request('category_id');
			$title = 'category_id';
		}

		if(request('search')){
			if($query){
				$query = $query . '&&search=' . request('search');
			}
			else{
				$query = '/category?' . 'search=' . request('search');
			}
		}

		if(request('page')){
			$query = '?page=' . request('page');
		}

		$url = env('BACKEND_API') . 'v1/blog' . $query;
		$options = array(
			'http'=>array(
				'method'=>"GET",
				'header'  => "Authorization: Bearer ". env('BACKEND_TOKEN')
			)
		);

		$total_data = 0;
		$current_page = 0;
		$per_page = 0;

		$context = stream_context_create($options);
		try {
			$response = file_get_contents($url, false, $context);
			$newData = json_decode($response);
			$total_data = $newData->total_data;
			$current_page = $newData->current_page;
			$per_page = $newData->per_page;
			$newData = collect($newData->message) ;
		} catch (\Throwable $th) {
			$newData = collect([]);
		}

		if($title == 'user_id'){
			$title = ' by ' . $newData[0]->user_id->name;
		}
		elseif($title == 'category_id'){
			$title = ' in ' . $newData[0]->category_id->name;
		}
		
		return view('blog.posts', [
			'title' => 'Blog',
			'title_tag' => 'All Posts' . $title,
			'active' => 'blog',
			'posts' => $newData,
			'total_data' => $total_data,
			'current_page' => $current_page,
			'per_page' => $per_page
		]);
	}

	public function show(Request $request, $slug){

		$url = env('BACKEND_API') . 'v1/blog/slug/' . $slug;
		$result = $this->api($url, 'GET');

		if($result->httpStatus != 200 || isset($result->error) ){
			return redirect('/blog');
		}
		return view('blog.post', [
			'title' => 'Blog',
			'active' => 'blog',
			'post' => $result->message[0]
		]);
	}

}
