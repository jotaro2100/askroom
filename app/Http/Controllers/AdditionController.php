<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdditionController extends Controller
{
    public function index()
    {
        $additions = Auth::user()->additions;
        $queries = array();

        foreach ($additions as $addition) {
            $queries[] = $addition->answer->rootQuery;
        };

        $queries = array_unique($queries);
        $title = [
            'title' => '補足した質問',
        ];

        return view('queries.index', $title)
            ->with(['queries' => $queries]);
    }
}
