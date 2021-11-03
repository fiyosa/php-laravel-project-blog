@extends('partials.main')
@section('main')
  <div class="container mt-4">
    <div class="row">
      <div class="col-sm-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Front-End</h5>
            <p class="card-text">This website uses the Laravel framework with the programming language PHP.</p>
            <a href="https://github.com/fiyosa/php-laravel-project-blog" class="btn btn-primary" target="_blank">View Program</a>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Back-End</h5>
            <p class="card-text">On this website managed in Back-End with Express.js</p>
            <a href="https://github.com/fiyosa/js-express-mongodb-project-blog" class="btn btn-primary" target="_blank">View Program</a>
          </div>
        </div>  
      </div>
    </div>
  </div>
@endsection