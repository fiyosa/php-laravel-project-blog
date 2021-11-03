@extends('dashboard.layouts.main')
@section('container')
  <div class="container">
    <div class="row my-5">
      <div class="col-lg-8">
        <h1 class="mb-3">{{ $post->title }}</h1>

        <a href="/dashboard/posts" class="btn btn-success"><span data-feather="arrow-left"></span> Back to all my posts</a>
        <a href="/dashboard/posts/edit/{{ $post->slug }}" class="btn btn-warning"><span data-feather="edit"></span> Edit</a>
        <form action="/dashboard/posts/delete/{{ $post->slug }}" method="post" class="d-inline">
          @csrf
          <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><span data-feather="x-circle"></span> Delete</button>
        </form>

        @php
          $time = $post->published_at;
          $time_micro_precision = substr($time, 0, -4) . 'Z'; 
          $datetime = new DateTime($time_micro_precision); 
          $published_at = $datetime->add(new DateInterval('PT7H'))->format('j/n/Y g:i A');
        @endphp

        <article class="my-3 fs-5">
          "{{ $post->category_id->name }}"   {{ $published_at }}
        </article>

        @if ($post->image)
          <div style="max-height: 350px; overflow:hidden;">
            <img src="{{ env('BACKEND_API') . $post->image }}" alt="{{ $post->category_id->name }}" class="img-fluid mt-3">                        
          </div>
        @else
          <img src="https://source.unsplash.com/1200x400?{{ $post->category_id->name }}" alt="{{ $post->category_id->name }}" class="img-fluid mt-3">            
        @endif


        <article class="my-3 fs-5">
          {!! $post->body !!}
        </article>

      </div>
    </div>
  </div>
@endsection