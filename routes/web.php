<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProfileController;

Route::get('', [PostController::class, 'index'])->name('home');
Route::get('/search', [PostController::class, 'search'])->name('posts.search');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'adminLogin']);
    Route::get('/register', [AuthController::class, 'showAdminRegisterForm'])->name('admin.register');
    Route::post('/register', [AuthController::class, 'adminRegister']);
    Route::post('/logout', [AuthController::class, 'adminLogout'])->name('admin.logout');
});

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('customers', [AdminController::class, 'manageCustomers'])->name('admin.customers');
    Route::put('customers/{id}/approve', [AdminController::class, 'approveCustomer'])->name('admin.customers.approve');
    Route::put('customers/{id}/disapprove', [AdminController::class, 'disapproveCustomer'])->name('admin.customers.disapprove');
    Route::delete('customers/{id}', [AdminController::class, 'deleteCustomer'])->name('admin.customers.delete');
    Route::get('search', [AdminController::class, 'searchcustomer'])->name('customer.search');

    Route::get('admin/posts', [AdminController::class, 'adminindex'])->name('admin.posts');
    Route::get('admin/posts/create', [AdminController::class, 'admincreate'])->name('admin.posts.create');
    Route::post('admin/posts', [AdminController::class, 'adminstore'])->name('admin.posts.store');
    Route::get('admin/posts/{id}', [AdminController::class, 'admintitle'])->name('admin.posts.title');
    Route::get('admin/posts/{id}/edit', [AdminController::class, 'adminshow'])->name('admin.posts.show');
    Route::put('admin/posts/{post}', [AdminController::class, 'adminupdate'])->name('admin.posts.update');
    Route::delete('admin/posts/{post}', [AdminController::class, 'admindestroy'])->name('admin.posts.destroy');


    Route::get('admin/categories/{id}/posts', [AdminController::class, 'getPostsByCategory'])->name('admin.categories.posts');

    Route::get('comments', [AdminController::class, 'manageComments'])->name('admin.comments');
    Route::delete('comments/{id}', [AdminController::class, 'deleteComment'])->name('admin.comments.destroy');

    Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('scategories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

});


Route::middleware('auth:customer')->group(function () {
    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('posts/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::post('posts/search', [PostController::class, 'search'])->name('posts.search');
    Route::post('/posts/{id}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('comments/{id}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::get('/categories/{category}/posts', [CategoryController::class, 'showPosts'])->name('categories.posts');

    Route::put('comments/{id}', [CommentController::class, 'update'])->name('comments.update');

    Route::post('/posts/{id}/ratings', [RatingController::class, 'store'])->name('ratings.store');

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
