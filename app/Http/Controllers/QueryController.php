<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueryController extends Controller
{
    public function index()
    {

        return view('queries.index')
            ->with([
                'queries' => $queries,
            'title' => '質問一覧',
            ]);
    }

    public function myQueries()
    {
        $queries = Auth::user()->queries->sortByDesc('updated_at');

        return view('queries.index')
            ->with([
                'queries' => $queries,
            'title' => '自分の質問',
            ]);
    }

    public function show(Query $query)
    {
        $editing = false;

        return view('queries.show')
            ->with([
                'query' => $query,
                'answer_editing' => $editing,
                'addition_editing' => $editing,
            ]);
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

    public function edit(Query $query)
    {
        return view('queries.edit')
            ->with(['query' => $query]);
    }

    public function update(Request $request, Query $query)
    {
        $query->title = $request->title;
        $query->content = $request->content;
        $query->save();

        return redirect()
            ->route('queries.show', $query);
    }

    public function destroy(Query $query)
    {
        $query->delete();

        return redirect()
            ->route('queries.index');
    }
}
