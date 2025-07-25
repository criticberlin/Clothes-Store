@extends('layouts.master')

@section('title', 'Register')

@section('content')
  <div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow m-4 col-sm-6">
      <div class="card-body">
        <h2 class="text-center mb-3">Create Account</h2>
        <p class="text-center text-primary mb-4">Register a new account</p>
        <form action="{{ route('do_register') }}" method="post" aria-label="Register form">
          {{ csrf_field() }}

          <!-- Display General Errors -->
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          
          <!-- Display Info Messages -->
          @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
          @endif

          <!-- Name Input -->
          <div class="form-group mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter your name" name="name" value="{{ old('name') }}" required aria-required="true" aria-describedby="nameHelp" autocomplete="name">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Email Input -->
          <div class="form-group mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email" name="email" value="{{ old('email') }}" required aria-required="true" aria-describedby="emailHelp" autocomplete="email">
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Password Input -->
          <div class="form-group mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" name="password" required aria-required="true" aria-describedby="passwordHelp" autocomplete="new-password">
            <div id="passwordHelp" class="form-text text-muted">Password must be at least 8 characters long</div>
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Password Confirmation Input -->
          <div class="form-group mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password:</label>
            <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm your password" name="password_confirmation" required aria-required="true" aria-describedby="passwordConfirmationHelp" autocomplete="new-password">
            @error('password_confirmation')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Register Button -->
          <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary w-100">Register</button>
          </div>
        </form>
        
        <!-- Social Login Buttons -->
        <div class="my-4 position-relative">
          <hr>
          <div class="position-absolute top-50 start-50 translate-middle bg-white px-3">
            Or Register With
          </div>
        </div>
        <div class="d-grid gap-2 mb-3">
          <a href="{{ route('google.login') }}" class="btn btn-outline-danger">
            <i class="fab fa-google me-2"></i> Google
          </a>
          <a href="{{ route('facebook.login') }}" class="btn btn-outline-primary">
            <i class="fab fa-facebook-f me-2"></i> Facebook
          </a>
          <a href="{{ route('github.redirect') }}" class="btn btn-outline-dark">
            <i class="fab fa-github me-2"></i> GitHub
          </a>
        </div>

        <!-- Login Link -->
        <div class="form-group mt-3 text-center">
          <span>Already have an account?</span>
          <a href="{{ route('login') }}" class="text-sm text-primary text-decoration-underline ms-1">
            Login here
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection