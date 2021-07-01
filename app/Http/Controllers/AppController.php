<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class AppController extends Controller
{
    public function index(): string
    {
        $now = Carbon::now()->format('d/m/Y H:i:s');

        return config('app.name') . '<br />' . app()->version() . '<br />' . config('app.timezone') . '<br />' . $now ;
    }
}
