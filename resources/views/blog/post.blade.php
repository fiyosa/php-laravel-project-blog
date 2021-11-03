@extends('partials.main')
@section('main')
  @php
    $time = $post->published_at;
    $time_micro_precision = substr($time, 0, -4) . 'Z'; 
    $datetime = new DateTime($time_micro_precision); 
    $published_at = $datetime->add(new DateInterval('PT7H'))->format('j/n/Y g:i A');
  @endphp
  <div class="card mb-3">
    @if ($post->image)
      <div style="max-height: 400px; overflow:hidden;">
        <img src="{{ env('BACKEND_API') . $post->image }}" alt="{{ $post->category_id->name }}" class="img-fluid">                        
      </div>
    @else
      <img src="https://source.unsplash.com/1200x400?{{ $post->category_id->name }}" class="card-img-top" alt="{{ $post->category_id->name }}">
    @endif
    <div class="card-body text-center">
      <h3 class="card-title"><a href="/posts/{{ $post->slug }}" class="text-decoration-none text-dark">{{ $post->title }}</a></h3>
      <p>
        <small class="text-muted">
          By. <a href="/blog?user_id={{ $post->user_id->_id }}" class="text-decoration-none">{{ $post->user_id->name }}</a> in <a href="/blog?category_id={{ $post->category_id->_id }}" class="text-decoration-none">{{ $post->category_id->name }}</a> {{ $published_at }}
        </small>
      </p>
      <p class="card-text">{!! $post->body !!}</p>
    </div>
  </div>  
@endsection