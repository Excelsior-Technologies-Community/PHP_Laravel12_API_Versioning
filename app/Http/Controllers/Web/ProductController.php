<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function create()
    {
        return view('products.create');
    }

    public function edit($version, $id)
    {
        return view('products.edit', ['version' => $version, 'id' => $id]);
    }

    public function show($version, $id)
    {
        return view('products.show', ['version' => $version, 'id' => $id]);
    }
}
