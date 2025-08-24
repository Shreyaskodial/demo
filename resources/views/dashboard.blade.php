<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    {{-- Top Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand">Dashboard</span>
            <div class="ms-auto">
                <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">

        {{-- Greeting --}}
        <h1 class="mb-4">Welcome, {{ Auth::user()->name }}</h1>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Bulk Delete Form --}}
        <form method="POST" action="{{ route('users.bulk-soft-delete') }}" id="bulkDeleteForm">
            @csrf
            @method('DELETE')

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>All Users</strong>
                    <button type="submit" class="btn btn-sm btn-danger d-none" id="bulkDeleteBtn"
                        onclick="return confirm('Are you sure you want to soft delete selected users?')">
                        Delete Selected
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
@forelse ($users as $user)
    @if ($user->is_deleted === 'N')
        <tr>
            <td>
                <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="select-user">
            </td>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->created_at->format('d M Y') }}</td>
            <td class="text-center">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>

                <form method="POST" action="{{ route('users.destroy', $user) }}"
                      class="d-inline"
                      onsubmit="return confirm('Mark this user as deleted?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </td>
        </tr>
    @endif
@empty
    <tr>
        <td colspan="6" class="text-center py-4">No users found.</td>
    </tr>
@endforelse
</tbody>

                        </table>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <script>
        // Toggle all checkboxes
        document.getElementById('selectAll').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.select-user');
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkDeleteBtn();
        });

        // Toggle delete button visibility
        document.querySelectorAll('.select-user').forEach(cb => {
            cb.addEventListener('change', toggleBulkDeleteBtn);
        });

        function toggleBulkDeleteBtn() {
            const selected = document.querySelectorAll('.select-user:checked').length;
            const btn = document.getElementById('bulkDeleteBtn');
            btn.classList.toggle('d-none', selected === 0);
        }
    </script>

</body>
</html>
