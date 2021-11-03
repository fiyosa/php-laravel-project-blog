@extends('partials.main')
@section('main')
  <h1 class="mb-3 text-center">{{ $title_tag }}</h1>

  <div class="row mt-2 mb-3 justify-content-center">
    <div class="col-md6">
      <form action="/blog" autocomplete="off">
        <div class="input-group mb-3">
          @if (request('category_id'))
            <input type="hidden" name="category" value="{{ request('category_id') }}">
          @endif
          @if (request('user_id'))
            <input type="hidden" name="category" value="{{ request('user_id') }}">
          @endif
          <input type="text" class="form-control" placeholder="Search..." name="search" value="{{ request('search') }}">
          <button class="btn btn-danger" type="submit" id="button-addon2">Search</button>
        </div>
      </form>
    </div>
  </div>

  @if ($posts->count())
    @php
        $time = $posts[0]->published_at;
        $time_micro_precision = substr($time, 0, -4) . 'Z'; 
        $datetime = new DateTime($time_micro_precision); 
        $published_at = $datetime->add(new DateInterval('PT7H'))->format('j/n/Y g:i A');
    @endphp

    <div class="card mb-3">
      @if ($posts[0]->image)
        <div style="max-height: 400px; overflow:hidden;">
          <img src="{{ env('BACKEND_API') . $posts[0]->image }}" alt="{{ $posts[0]->category_id->name }}" class="img-fluid">                        
        </div>
      @else
        <img src="https://source.unsplash.com/1200x400?{{ $posts[0]->category_id->name }}" class="card-img-top" alt="{{ $posts[0]->category_id->name }}">
      @endif
      <div class="card-body text-center">
        <h3 class="card-title"><a href="/posts/{{ $posts[0]->slug }}" class="text-decoration-none text-dark">{{ $posts[0]->title }}</a></h3>
        <p>
          <small class="text-muted">
            By. <a href="/blog?user_id={{ $posts[0]->user_id->_id }}" class="text-decoration-none">{{ $posts[0]->user_id->name }}</a> in <a href="/blog?category_id={{ $posts[0]->category_id->_id }}" class="text-decoration-none">{{ $posts[0]->category_id->name }}</a> {{ $published_at }}
          </small>
        </p>
        <p class="card-text">{{ $posts[0]->excerpt }}</p>
        <a href="/blog/{{ $posts[0]->slug }}" class="text-decoration-none btn btn-primary">Read more</a>
      </div>
    </div>    

    <div class="container">
      <div class="row">
        @foreach ($posts->skip(1) as $post)
          @php
            $time = $post->published_at;
            $time_micro_precision = substr($time, 0, -4) . 'Z'; 
            $datetime = new DateTime($time_micro_precision); 
            $published_at = $datetime->add(new DateInterval('PT7H'))->format('j/n/Y g:i A');
          @endphp
          <div class="col-md-4 mb-3">
            <div class="card">
              <div class="position-absolute px-3 px-2" style="background-color: rgba(0, 0, 0, 0.7)"><a href="/blog?category_id={{ $posts[0]->category_id->slug }}" class="text-decoration-none text-white">{{ $post->category_id->name }}</a></div>
              @if ($post->image)
                <div style="max-height: 350px; overflow:hidden;">
                  <img src="{{ env('BACKEND_API') . $post->image }}" alt="{{ $post->category_id->name }}" class="img-fluid">                        
                </div>
              @else
                <img src="https://source.unsplash.com/1200x400?{{ $post->category_id->name }}" alt="{{ $post->category_id->name }}" class="img-fluid">            
              @endif
              <div class="card-body">
                <h5 class="card-title">{{ $post->title }}</h5>
                <p>By. <a href="/blog?user_id={{ $post->user_id->_id }}" class="text-decoration-none">{{ $post->user_id->name }}</a> in <a href="/blog?category_id={{ $post->category_id->_id }}" class="text-decoration-none">{{ $post->category_id->name }}</a> {{ $published_at }}</p>
                <p class="card-text">{{ $post->excerpt }}</p>
                <a href="/blog/{{ $post->slug }}" class="btn btn-primary">Read more</a>
              </div>
            </div>
          </div>        
        @endforeach
      </div>
    </div> 

    @if (!request('search') && !request('user_id') && !request('category_id'))
      @include('partials.pagination')        
    @endif

  @else
    <p class="text-center fs-4">No post found.</p>
  @endif 
@endsection