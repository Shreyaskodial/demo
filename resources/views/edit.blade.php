@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit User</h2>

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name"
                   value="{{ old('name', $user->name) }}"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email"
                   value="{{ old('email', $user->email) }}"
                   class="form-control" required>
        </div>

        <button class="btn btn-primary">Save</button>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection
