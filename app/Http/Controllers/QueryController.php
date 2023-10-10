<?php

namespace App\Http\Controllers;

use App\Models\Query;
use App\Models\Addition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\QueryRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class QueryController extends Controller
{
    private function getQueriesByResolveStatus(Request $request, $resolveStatus)
    {
        $search = $request->input('search');
        $queryBuilder = Query::where('resolve', $resolveStatus)
            ->orderBy('updated_at', 'DESC');

        if ($search) {
            $queryBuilder->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        return $queryBuilder->with('user')->orderBy('updated_at', 'DESC')->paginate(5);
    }

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

        $queries = $queries->with('user')->paginate(5);

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

        $queries = $queries->with('user')
                        ->orderBy('updated_at', 'DESC')
                        ->paginate(5);

        return view('queries.index')
            ->with([
                'queries' => $queries,
                'title' => '自分の質問',
                'search' => $search,
                'controller' => 'queries',
                'method' => 'my_queries',
            ]);
    }

    public function resolvedQueries(Request $request)
    {
        $queries = $this->getQueriesByResolveStatus($request, 1);

        return view('queries.index')
            ->with([
                'queries' => $queries,
                'title' => '解決済の質問',
                'search' => $request->input('search'),
                'controller' => 'queries',
                'method' => 'resolved_queries',
            ]);
    }

    public function unresolvedQueries(Request $request)
    {
        $queries = $this->getQueriesByResolveStatus($request, 0);

        return view('queries.index')
            ->with([
                'queries' => $queries,
                'title' => '未解決の質問',
                'search' => $request->input('search'),
                'controller' => 'queries',
                'method' => 'unresolved_queries',
            ]);
    }

    public function show(Query $query)
    {
        $editing = false;
        $answers = $query->answers
                    ->load('user', 'additions')
                    ->sortByDesc('updated_at');

        return view('queries.show')
            ->with([
                'query' => $query,
                'answers' => $answers,
                'answer_editing' => $editing,
                'addition_editing' => $editing,
                'answer_id' => 0,
            ]);
    }

    public function create()
    {
        return view('queries.create');
    }

    public function store(QueryRequest $request)
    {
        $query = new Query();
        $query->user_id = Auth::id();
        $query->title = $request->title;
        $query->content = $request->content;
        $query->save();

        return redirect()
            ->route('queries.index')
            ->with('flash_message', '質問を投稿しました');
    }

    public function toggleResolve(Query $query)
    {
        $this->authorize('update', $query);

        if ($query->resolve == 1) {
            $query->resolve = 0;
            $flash_message = '質問を未解決に変更しました';
        } else {
            $query->resolve = 1;
            $flash_message = '質問を解決済に変更しました';
        }
        $query->timestamps = false;
        $query->save();

        return redirect()
            ->route('queries.show', $query)
            ->with('flash_message', $flash_message);
    }

    public function edit(Query $query)
    {
        $this->authorize('update', $query);

        return view('queries.edit')
            ->with(['query' => $query]);
    }

    public function update(QueryRequest $request, Query $query)
    {
        $this->authorize('update', $query);

        $query->title = $request->title;
        $query->content = $request->content;
        $query->save();

        return redirect()
            ->route('queries.show', $query)
            ->with('flash_message', '質問を更新しました');
    }

    public function destroy(Query $query)
    {
        $this->authorize('delete', $query);

        $query->delete();

        return redirect()
            ->route('queries.index')
            ->with('flash_message', '質問を削除しました');
    }
}
