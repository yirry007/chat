<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $req = $request->only('fromid', 'toid');

        return view('index', compact('req'));
    }

    public function lists(Request $request)
    {
        $req = $request->only('fromid', 'toid');

        return view('lists', compact('req'));
    }
}
