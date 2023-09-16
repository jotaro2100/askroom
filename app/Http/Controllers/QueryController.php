<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueryController extends Controller
{
    public function index()
    {
        $queries = Query::latest()->get();
        $title = [
            'title' => '質問一覧',
        ];

        return view('queries.index', $title)
            ->with(['queries' => $queries]);
    }

    public function myQueries()
    {
        $queries = Auth::user()->queries->sortByDesc('updated_at');;
        $title = [
            'title' => '自分の質問',
        ];

        return view('queries.index', $title)
            ->with(['queries' => $queries]);
    }

    public function show(Query $query)
    {
        return view('queries.show')
            ->with(['query' => $query]);
    }

    public function create()
    {
        return view('queries.create');
    }

    public function store(Request $request)
    {
        $query = new Query();
        $query->user_id = Auth::id();
        $query->title = $request->title;
        $query->content = $request->content;
        $query->save();

        return redirect()
            ->route('queries.index');
    }
}