@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Edit User: {{ $user->name }}</h2>

    {{-- show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input  name="name"
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name) }}"
                    required>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input  name="email"
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email) }}"
                    required>
        </div>

        {{-- Password (optional) --}}
        <div class="mb-4">
            <label class="form-label">New Password <small>(leave blank to keep current)</small></label>
            <input  name="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror">
        </div>

        <button class="btn btn-primary">Save changes</button>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </form>

    {{-- Soft Delete Checkbox --}}
    <hr>
    <form method="POST" action="{{ route('users.softdelete', $user->id) }}" id="softDeleteForm">
        @csrf
        @method('DELETE')

        <div class="form-check my-3">
            <input class="form-check-input" type="checkbox" value="1" id="softDeleteCheck">
            <label class="form-check-label" for="softDeleteCheck">
                Mark this user for soft delete
            </label>
        </div>

        <button type="submit" class="btn btn-danger" id="softDeleteButton" style="display: none;">
            Delete Selected
        </button>
    </form>
</div>

{{-- Show button only if checkbox is selected --}}
<script>
    document.getElementById('softDeleteCheck').addEventListener('change', function () {
        document.getElementById('softDeleteButton').style.display = this.checked ? 'inline-block' : 'none';
    });
</script>
@endsection
