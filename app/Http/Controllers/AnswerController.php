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
        $title = [
            'title' => '回答した質問',
        ];

        return view('queries.index', $title)
            ->with(['queries' => $queries]);
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
                'editing' => $editing,
                'edit_answer_id' => $edit_answer_id,
            ]);
    }

    public function update(Request $request, Query $query, Answer $answer)
    {
        $editing = false;
        $answer->content = $request->content;
        $answer->save();

        return redirect()
            ->route('queries.show', $query)
            ->with(['editing' => $editing]);
    }

    public function destroy(Query $query, Answer $answer)
    {
        $answer->delete();

        return redirect()
            ->route('queries.show', $query);
    }
}
