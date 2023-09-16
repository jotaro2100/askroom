<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function index()
    {
        $answers = Auth::user()->answers;
        $queries = array();

        foreach ($answers as $answer) {
            $queries[] = $answer->rootQuery;
        };

        $title = [
            'title' => '回答した質問',
        ];

        return view('queries.index', $title)
            ->with(['queries' => $queries]);
    }
}
