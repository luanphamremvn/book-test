<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class DashboardController extends Controller
{
    /**
     * Display the dashboard index page.
     *
     * @return View|Application|Factory
     */
    public function index(): View|Application|Factory
    {
        return view('pages.dashboard.index');
    }
}
