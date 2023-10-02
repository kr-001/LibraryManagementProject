@extends('layouts.app')
@section('content')
@include('layouts.navbar' , ["title" => "Dashboard" , "dropdown1_route" => "/myBorrowings" , "dropdown1" => "My Borrowings"])
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>{{session('success')}}</strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="container">
     <div class="row">
        <div class="col">
            <h2>Welcome, {{$user->name}}!</h2>
            <p>Role: {{$user->role}}</p>
        </div>
        <div class="col">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Logout</button>
        </form>
        </div>
     </div>
    <hr>
</div>
<div class="container">
    <h2>Book List</h2>
    <ul class="list-group">
        @foreach($books as $book)
        <li class="list-group-item" style="margin-top: 20px;">
            <strong>Title:</strong> {{$book->title}}<br>
            <strong>Author:</strong> {{$book->author}}<br>
            <strong>Price:</strong> {{$book->price}}<br>
            <strong>Quantity:</strong> {{$book->quantity}}<br>
            <a href="{{route('checkout' , $book->id)}}" class="btn btn-primary">Borrow</a>
        </li>
        @endforeach
    </ul>
</div>
<script>
    const token = localStorage.getItem('token');
    const headers = {
        'Authorization' :  `Bearer ${token}`,
        'Content-Type' : 'application/json'
    };
    fetch('/verifyToken',{
        method: 'GET',
        headers: headers
    })
    .then(response=>{
        if(!response.ok){
           logoutUser();
           window.location.href = '/';
        }
        return response.json();
    })
    .then(data=>{
        console.log(data);
    }).catch(error=>{
        console.error('Fetch Error: ' , error);
    })
    function logoutUser(){
        localStorage.removeItem('token');
    }
</script>
@endsection