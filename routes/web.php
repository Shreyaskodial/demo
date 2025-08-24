     <?php

     use Illuminate\Support\Facades\Route;
     use App\Http\Controllers\LoginController;
     use App\Http\Controllers\RegisterController;
     use App\Http\Controllers\DashboardController;
     use App\Http\Controllers\UserController;


     Route::view('/', 'welcome');

     // Auth (login / logout)
     Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
     Route::post('/login', [LoginController::class, 'login']);
     Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

     // Registration
     Route::get('/register',  [RegisterController::class, 'create'])->name('register');
     Route::post('/register', [RegisterController::class, 'store'])->name('register.store');


     Route::middleware('auth')->group(function () {
          Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
          Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
          Route::delete('/users/bulk-soft-delete', [UserController::class, 'bulkSoftDelete']) ->name('users.bulk-soft-delete');
          Route::patch('/users/{user}/restore', [UserController::class, 'restore'])->withTrashed() ->name('users.restore');
          Route::resource('users', UserController::class) ->only(['index', 'edit', 'update', 'destroy']);
     });
     Route::delete('/users/{user}/softdelete', [UserController::class, 'destroy']) ->name('users.softdelete');
     