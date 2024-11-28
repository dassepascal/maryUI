<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;



// Users will be redirected to this route if not logged in
Volt::route('/login', 'login')->name('login');
 
// Define the logout
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
 
    return redirect('/');
});

Volt::route('/', 'index');                          // Home 
Volt::route('/users', 'users.index');               // User (list) 
Volt::route('/users/create', 'users.create');       // User (create) 
Volt::route('/users/{user}/edit', 'users.edit');    // User (edit) 
