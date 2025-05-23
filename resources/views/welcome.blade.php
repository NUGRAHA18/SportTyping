<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SportTyping – Competitive Typing Platform</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?
    family=Montserrat:wght@400;600;700&
    family=Inter:wght@400;600&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
        rel="stylesheet">   
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

</head>
<body>
  <div class="hero">
    <div class="container">
        <div class="center">
    <h1>Welcome to <span class="highlight">SportTyping</span></h1>
      <p class="lead">
        Transforming typing into a competitive sport since 2025
      </p>
        </div>
      
      <div class="mt-4">
        @if(Route::has('login'))
          @auth
            <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg me-2">
              Dashboard
            </a>
          @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2">
              Log in
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg">
              Register
            </a>
          @endauth
        @endif
      </div>
    </div>
  </div>

  <div class="container my-5">
    <div class="text-center mb-5">
      <h2 class="display-5">
        <span class="highlight">Features</span>
      </h2>
      <p class="lead text-muted">
        Experience the thrill of competitive typing
      </p>
    </div>

    <div class="row">
      <div class="col-md-4" style="background-image: url('{{ asset('images/main.jpg') }}');">
        <div class="feature-card">
          <h3>Device-Specific Arenas</h3>
          <p>Separate arenas for mobile and PC users ensuring fair competition</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <h3>League System</h3>
          <p>Progress through rankings as your typing skills improve</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <h3>Real-time Competitions</h3>
          <p>Challenge other users in exciting typing races</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <h3>Comprehensive Leaderboards</h3>
          <p>Track your performance against global competitors</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <h3>Professional Typing Lessons</h3>
          <p>Learn 10-finger typing techniques with structured courses</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card">
          <h3>Custom Typing Tests</h3>
          <p>Practice with various text categories and difficulty levels</p>
        </div>
      </div>
    </div>

    <div class="text-center mt-5">
      <a href="{{ route('guest.practice') }}" class="btn btn-primary btn-lg">
        Try Without Account
      </a>
    </div>
  </div>

  <footer>
    <div class="container">
      <p>&copy; 2025 SportTyping. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
