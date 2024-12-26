<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header text-center">
          <h4>Login</h4>
        </div>
        <div class="card-body">
          <form action="{{route('login-post')}}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" required>
              @error('email')
              <div class="alert alert-danger">{{ $message }}</div> 
              @enderror
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
              @error('password')
              <div class="alert alert-danger">{{ $message }}</div> 
              @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>
        </div>
        <div class="card-footer text-center">
          <p>Don't have an account? <a href="{{route('sign-up')}}">Sign Up</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
