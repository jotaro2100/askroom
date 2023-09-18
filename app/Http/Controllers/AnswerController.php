<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $answers = Auth::user()->answers();
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
                'title' => '回答した質問',
                'search' => $search,
                'controller' => 'answers',
                'method' => 'index',
            ]);
    }

    public function store(Request $request, Query $query)
    {
        $answer = new Answer();
        $answer->user_id = Auth::id();
        $answer->query_id = $query->id;
        $answer->content = $request->content;
        $answer->save();

        return redirect()
            ->route('queries.show', $query);
    }

    public function edit(Query $query, Answer $answer)
    {
        $editing = true;
        $edit_answer_id = $answer->id;

        return view('queries.show')
            ->with([
                'query' => $query,
                'answer' => $answer,
                'answer_editing' => $editing,
                'addition_editing' => false,
                'edit_answer_id' => $edit_answer_id,
                'answer_id' => 0,
            ]);
    }

    public function update(Request $request, Query $query, Answer $answer)
    {
        $answer->content = $request->content;
        $answer->save();

        return redirect()
            ->route('queries.show', $query);
    }

    public function destroy(Query $query, Answer $answer)
    {
        $answer->delete();

        return redirect()
            ->route('queries.show', $query);
    }
}
