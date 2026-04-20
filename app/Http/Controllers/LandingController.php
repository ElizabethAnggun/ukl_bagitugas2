<?php

namespace App\Http\Controllers;

/**
 * LandingController - Controller untuk halaman landing page
 */
class LandingController extends Controller
{
    /**
     * Tampilkan halaman landing page
     */
    public function index()
    {
        return view('landing');
    }
}
