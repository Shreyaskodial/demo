@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Employee Dashboard</h1>
    <p>Welcome, {{ $user->name }}</p>
    <p>Email: {{ $user->email }}</p>
</div>
@endsection
