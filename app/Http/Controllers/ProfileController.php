<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user()->load('petani');
        return view('profile', compact('user'));
    }
}
