<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laravel | {{ $title }}</title>

  <link rel = "icon" href = "/img/icons/iconF.png" type = "image/x-icon">
  <link rel="stylesheet" href="/css/main/clearWatermark000webhost.css">
  <link rel="stylesheet" href="/css/login/login.css">
  <link rel="stylesheet" href="/css/main/navbar.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
  @include('partials.header')

  <div class="position-relative">
    <div>
      @if ($errors->any())
        <div class="container mb-5 alert alert-danger alert-dismissible fade show" role="alert">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    
      @if (session('failed-register'))
        <div class="container mb-5 alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('failed-register') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> 
      @endif

      @if (session('loginError'))
        <div class="container mb-5 alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('loginError') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> 
      @endif
      
      @if (session('success'))
        <div class="container mb-5 alert alert-success alert-dismissible fade show" role="alert">
          {{ session('failed-register') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div> 
      @endif   
    </div>
    
    <div class="box">
      <form action="{{ $title=='Login'? '/login' : '/register' }}" method="post">
        @csrf
        @if ($title == 'Register')
          <input type="text" name="username" placeholder="username" id="username" required>      
        @endif
        <input type="text" name="email" placeholder="email" id="email" required>
        <input type="password" name="password" placeholder="password" id="password" required autocomplete="off">
        @if ($title == 'Register')
          <input type="password" name="Cpassword" placeholder=" confirm password" id="password" required autocomplete="off">
        @endif
        <button type="submit" >{{ $title=='Login'? 'Login' : 'Register' }}</button>
      </form>
  
      @if ($title == 'Register')
        <small class="confirm-user">Ready Login? <a href="/login">Login</a></small>
      @else
        <small class="confirm-user">Don't Have an Account? <a href="/register">Register</a></small>
      @endif
    </div>
  </div>


  

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>




