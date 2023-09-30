<?php

namespace App\Http\Controllers;

use App\Models\Query;
use App\Models\Answer;
use App\Models\Addition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        $queries = $queries->with('user')->groupBy('queries.id')->paginate(5);

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
        $rules = [
            "addition_content{$answer->id}" => 'required|min:5|max:300',
        ];

        $messages = [
            "addition_content{$answer->id}.required" => "補足を入力してください",
            "addition_content{$answer->id}.min" => "補足は :min 文字以上で入力してください",
            "addition_content{$answer->id}.max" => "補足は :max 文字以下で入力してください",
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // バリデーションエラー時に該当のフォームの$answer->idを返却
        if ($validator->fails())
        {
            return redirect()->back()->with([
                'ansid' => $request->answer->id,
            ])->withErrors($validator)->withInput();
        }

        $addition = new Addition();
        $addition->user_id = Auth::id();
        $addition->answer_id = $answer->id;
        $addition->content = $request->input("addition_content"."{$answer->id}");
        $addition->save();

        return redirect()
            ->route('queries.show', $query)
            ->with('flash_message', '補足を投稿しました');
    }

    public function edit(Query $query, Answer $answer, Addition $addition)
    {
        $this->authorize('update', $addition);

        $addition_editing = true;
        $edit_addition_id = $addition->id;
        $answer_id = $addition->answer->id;
        $answers = $query->answers
            ->load('user', 'additions')
            ->sortByDesc('updated_at');

        return view('queries.show')
            ->with([
                'query' => $query,
                'answer' => $answer,
                'answers' => $answers,
                'answer_editing' => false,
                'addition_editing' => $addition_editing,
                'edit_addition_id' => $edit_addition_id,
                'answer_id' => $answer_id,
            ]);
    }

    public function update(Request $request, Query $query, $answer, Addition $addition)
    {
        $this->authorize('update', $addition);

        $request->validate([
            "addition_content{$answer->id}" => 'required|min:5|max:300',
        ], [
            "addition_content{$answer->id}.required" => "補足を入力してください",
            "addition_content{$answer->id}.min" => "補足は :min 文字以上で入力してください",
            "addition_content{$answer->id}.max" => "補足は :max 文字以下で入力してください",
        ]);

        $addition->content = $request->input("addition_content"."{$answer->id}");
        $addition->save();

        return redirect()
            ->route('queries.show', $query)
            ->with('flash_message', '補足を更新しました');
    }

    public function destroy(Query $query, $answer, Addition $addition)
    {
        $this->authorize('delete', $addition);

        $answer;
        $addition->delete();

        return redirect()
            ->route('queries.show', $query)
            ->with('flash_message', '補足を削除しました');
    }
}
