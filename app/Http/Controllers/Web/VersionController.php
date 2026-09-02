<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class VersionController extends Controller
{
    public function index()
    {
        return view('versions.index');
    }
}