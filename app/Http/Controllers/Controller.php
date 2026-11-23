<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function checkAdmin(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }
}
