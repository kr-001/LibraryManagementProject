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
    <form action="{{route('login')}}" method="POST" id="login-form">
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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('login-form');

        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            axios.post('/api/loginUser', {
                email: email,
                password: password
            })
            .then(function (response) {
                const { access_token} = response.data
                const role = response.data.user.role;
                localStorage.setItem('token' , access_token);
                console.log("Access Token: ", access_token);
                console.log("Role: ", role);
                if (role === 'Librarian') {
                    window.location.href = '/adminPanel';
                } else if (role === 'Student') {
                    window.location.href = '/dashboard?token='+access_token;
                } else {
                    console.log("Error");
                }
            })
            .catch(function (error) {
                console.error('Login error:', error);
            });
        });
    });
</script>

@endsection