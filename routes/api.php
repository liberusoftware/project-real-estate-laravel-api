<?php

\Illuminate\Support\Facades\Route::middleware('auth:sanctum')->get('/user', fn (\Illuminate\Http\Request $request) => $request->user());
