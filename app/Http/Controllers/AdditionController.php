<?php

namespace App\Http\Controllers;

use App\Models\Query;
use App\Models\Answer;
use App\Models\Addition;
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

        return view('queries.index')
            ->with([
                'queries' => $queries,
                'title' => '補足した質問',
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
