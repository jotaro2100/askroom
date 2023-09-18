<?php

namespace App\Http\Controllers;

use App\Models\Query;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueryController extends Controller
{
    public function index(Request $request)
    {
        $queries = Query::orderBy('updated_at', 'DESC');
        $search = $request->input('search');
        $query = Query::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
            $queries = $query->orderBy('updated_at', 'DESC');
        }

        $queries = $queries->paginate(5);

        return view('queries.index')
            ->with([
                'queries' => $queries,
                'title' => '質問一覧',
                'search' => $search,
                'controller' => 'queries',
                'method' => 'index',
            ]);
    }

    public function myQueries(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();
        $queries = $user->queries()->orderBy('updated_at', 'DESC');

        if ($search) {
            $queries->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $queries = $queries->orderBy('updated_at', 'DESC')->paginate(5);

        return view('queries.index')
            ->with([
                'queries' => $queries,
                'title' => '自分の質問',
                'search' => $search,
                'controller' => 'queries',
                'method' => 'my_queries',
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
                'answer_id' => 0,
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
