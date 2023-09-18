<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Query;
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

        $queries = array_unique($queries);

        return view('queries.index')
            ->with([
                'queries' => $queries,
            'title' => '回答した質問',
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
