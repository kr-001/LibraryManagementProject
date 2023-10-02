@extends('layouts.app')
@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>{{session('success')}}</strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="container">
    <h2>User Login</h2>
    <form action="{{route('authUser')}}" method="POST" id="login-form">
        @csrf
        <div class="mb-3">
          <label for="email" class="form-label">Email:</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="email" aria-describedby="helpId">
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password:</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="password" aria-describedby="helpId">
        </div>
        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('login-form');
        
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(loginForm);
            fetch("{{ route('authUser') }}", {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    localStorage.setItem('token', data.token);

                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
          
                    console.error('Login failed:', data.error);
                }
            })
            .catch(error => {
                console.error('Network error:', error);
            });
        });
    });
</script>

@endsection