<?php

use Illuminate\Support\Facades\Route;

it('returns ok for the simple route', function () {
    // Register a simple route for testing.
    Route::get('/login', fn() => response()->json(['message' => 'ok']));

    // Send a GET request and assert the response.
    $response = $this->get('/login');
    $response->assertStatus(200);
});
