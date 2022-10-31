<?php
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\User\UserController;

Route::resource('posts', PostController::class)->middleware(['auth']); //resource route for post controller
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [PostController::class, 'index'])->name('posts.index')->middleware(['auth']); //home page
Route::get('deleted/posts', [PostController::class,'deletedPosts'])->name('posts.deleted')->middleware(['auth']); // deleted posts page
Route::delete('deleted/posts/{id}', [PostController::class,'deleteForEver'])->name('posts.deletePermently')->middleware(['auth']); // delete post forever
Route::post('restore/posts/{id}', [PostController::class,'restorePost'])->name('posts.restore')->middleware(['auth']); // restore post
Route::resource('users', UserController::class)->middleware('auth');
 
Route::get('/auth/github/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('auth.github');
 
// Sign in with github
Route::get('/auth/github/callback', function () {
    $githubUser = Socialite::driver('github')->user();
    // dd($githubUser);
    $user = User::updateOrCreate([
        'email' => $githubUser->email,
    ], [
        'name' => $githubUser->nickname,
        'email' => $githubUser->email,
    ]);
    Auth::login($user);
    return redirect('/');
    // $user->token
});


// Sign in with google
Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('auth.google');
 
Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();
    // dd($googleUser);
    $user = User::updateOrCreate([
        'email' => $googleUser->email,
    ], [
        'name' => $googleUser->name,
        'email' => $googleUser->email,
    ]);
    Auth::login($user);
    return redirect('/');
    // $user->token
});