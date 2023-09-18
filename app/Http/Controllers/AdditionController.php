<?php

namespace App\Http\Controllers;

use App\Models\Query;
use App\Models\Answer;
use App\Models\Addition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdditionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $additions = Auth::user()->additions();
        $answers = $additions->select('answers.*')
            ->join('answers', 'answers.id', '=', 'additions.answer_id');
        $queries = $answers->select('queries.*')
            ->join('queries', 'queries.id', '=', 'answers.query_id')
            ->distinct()
            ->orderBy('updated_at', 'DESC');

        if ($search) {
            $queries->where(function ($query) use ($search) {
                $query->where('queries.title', 'like', "%{$search}%")
                    ->orWhere('queries.content', 'like', "%{$search}%");
            });
        }

        $queries = $queries->paginate(5);

        return view('queries.index')
            ->with([
                'queries' => $queries,
                'title' => '補足した質問',
                'search' => $search,
                'controller' => 'additions',
                'method' => 'index',
            ]);
    }

    public function store(Request $request, Query $query, Answer $answer)
    {
        $addition = new Addition();
        $addition->user_id = Auth::id();
        $addition->answer_id = $answer->id;
        $addition->content = $request->content;
        $addition->save();

        return redirect()
            ->route('queries.show', $query);
    }

    public function edit(Query $query, Answer $answer, Addition $addition)
    {
        $addition_editing = true;
        $edit_addition_id = $addition->id;
        $answer_id = $addition->answer->id;

        return view('queries.show')
            ->with([
                'query' => $query,
                'answer' => $answer,
                'answer_editing' => false,
                'addition_editing' => $addition_editing,
                'edit_addition_id' => $edit_addition_id,
                'answer_id' => $answer_id,
            ]);
    }

    public function update(Request $request, Query $query, $answer, Addition $addition)
    {
        $answer;
        $addition->content = $request->content;
        $addition->save();

        return redirect()
            ->route('queries.show', $query);
    }

    public function destroy(Query $query, $answer, Addition $addition)
    {
        $answer;
        $addition->delete();

        return redirect()
            ->route('queries.show', $query);
    }
}
