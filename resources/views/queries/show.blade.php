<x-app-layout>
    <div class="py-12">
        <div class="px-2 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- トップメニュー --}}
            <div class="flex justify-between items-center">
                <div class="text-lg text-blue-500">
                    &lsaquo;&lsaquo; <a href="{{ route('queries.index') }}" class="hover:underline">一覧に戻る</a>
                </div>
                @if($query->user == Auth::user())
                    <div class="flex items-center">
                        <div class="mr-5">
                            <a href="{{ route('queries.edit', $query) }}" type="button" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm font-bold px-2 py-2 text-center dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                質問を編集
                            </a>
                        </div>
                        <form onsubmit="return confirm('本当に削除しますか？')" action="{{ route('queries.destroy', $query) }}" method="post">
                            @method('DELETE')
                            @csrf

                            <button class="text-red-500 hover:underline text-sm">
                                質問を削除
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- 質問本文 --}}
            <div class="block min-h-[400px] p-6 bg-white border-[2px] border-gray-200 rounded-lg shadow dark:bg-gray-900 dark:border-gray-700">
                <h2 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $query->title }}</h2>
                <div class="flex justify-between mb-6">
                    <div class="">
                        <p class="font-bold text-gray-700 dark:text-gray-300">
                            投稿者： {{ $query->user->name }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-mono text-gray-700 dark:text-gray-300">
                            作成日時： {{ $query->created_at }}
                        </p>
                        <p class="text-sm font-mono text-gray-700 dark:text-gray-300">
                            更新日時： {{ $query->updated_at }}
                        </p>
                    </div>
                </div>
                <p class="font-normal text-gray-100 dark:text-gray-100">
                    {!! nl2br(e($query->content)) !!}
                </p>
            </div>
            <div>
                <p class="font-bold text-gray-700 dark:text-gray-300 ml-1">{{ $query->answers->count() }} 件の回答が見つかりました</p>
            </div>

            {{-- 回答一覧 --}}
            @foreach ($query->answers()->latest()->get() as $answer)
                <div class="block p-6 bg-white rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between mb-4">
                        <div>
                            <p class="mb-4 font-bold text-gray-700 dark:text-gray-300">
                                回答者： {{ $answer->user->name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-mono text-gray-700 dark:text-gray-300">
                                回答日時： {{ $answer->created_at }}
                            </p>
                            @if ($answer->created_at != $answer->updated_at)
                                <p class="text-sm font-mono text-gray-700 dark:text-gray-300">
                                    更新日時： {{ $answer->updated_at }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if ($answer->user == Auth::user() && !$answer_editing && !$addition_editing)
                                <div class="flex justify-end">
                                    <div class="text-blue-500 mr-4">
                                        <a href="{{ route('answers.edit', [$query, $answer]) }}" class="hover:underline">編集</a>
                                    </div>
                                    <div class="text-red-500">
                                        <form onsubmit="return confirm('本当に削除しますか？')" action="{{ route('answers.destroy', [$query, $answer]) }}" method="post">
                                            @method('DELETE')
                                            @csrf
                                            <button class="hover:underline">削除</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 認証ユーザーの回答の場合 --}}
                    @if ($answer->user == Auth::user())
                        {{-- 回答編集メソッド発火時 --}}
                        @if ($answer_editing && ($answer->id == $edit_answer_id))
                            {{-- 回答編集フォームを表示 --}}
                            <form action="{{ route('answers.update', [$query, $answer]) }}" method="post">
                                @csrf
                                @method('PATCH')

                                <x-text-area id="content" class="mt-1 w-full h-[180px] dark:!bg-gray-900" type="text" name="content" autofocus required>{{ old('content', $answer->content) }}</x-text-area>
                                <div class="flex justify-end items-center mt-4">
                                    <div class="text-blue-500 mr-4">
                                        <button type="submit">更新</button>
                                    </div>
                                    <div class="text-red-500">
                                        <a href="{{ route('queries.show', $query) }}">キャンセル</a>
                                    </div>
                                </div>
                            </form>
                        {{-- 通常時 --}}
                        @else
                            <p class="mb-4 text-gray-700 dark:text-gray-300">{!! nl2br(e($answer->content)) !!}</p>
                            <div class="flex justify-end">
                                <div class="text-blue-500 mr-4">
                                    <a href="{{ route('answers.edit', [$query, $answer]) }}" class="hover:underline">編集</a>
                                </div>
                                <div class="text-red-500">
                                    <form onsubmit="return confirm('本当に削除しますか？')" action="{{ route('answers.destroy', [$query, $answer]) }}" method="post">
                                        @method('DELETE')
                                        @csrf

                                        <button class="text-red-500 hover:underline">
                                            削除
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @else
                        <p class="mb-4 text-gray-700 dark:text-gray-300">{!! nl2br(e($answer->content)) !!}</p>
                    @endif

                    @if ($answer->additions->count() == 0)
                        @if (!$addition_editing)
                        <div class="text-white text-center cursor-pointer text-xs leading-3 hover:text-blue-500 w-fit mx-auto mt-3" onclick="toggleAdditions({{ $answer->id }})">
                            <p id="showAdditionsBtn_{{ $answer->id }}">補足を投稿する<br>&or;</p>
                            <p id="hideAdditionsBtn_{{ $answer->id }}" style="display: none;">&and;<br>キャンセル</p>
                        </div>
                        @endif
                    @else
                        @if (!$addition_editing)
                            <div class="text-white text-center cursor-pointer text-xs leading-3 hover:text-blue-500 w-fit mx-auto mt-6" onclick="toggleAdditions({{ $answer->id }})">
                                <p id="showAdditionsBtn_{{ $answer->id }}">この回答の補足を見る<br>&or;</p>
                                <p id="hideAdditionsBtn_{{ $answer->id }}" style="display: none;" class="mb-8">&and;<br>閉じる</p>
                </div>
                        @endif
                    @endif

                {{-- 補足一覧 --}}
                    <div class="additions_{{ $answer->id }}" style="display: none;">
                @foreach ($answer->additions as $addition)
                    <div class="flex justify-center">
                        <div class="p-6 w-11/12 bg-white border-[2px] border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <div class="flex justify-between mb-2">
                                <div>
                                    <p class="mb-4 font-bold text-gray-700 dark:text-gray-300">
                                        補足者： {{ $addition->user->name }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-mono text-gray-700 dark:text-gray-300">
                                        回答日時： {{ $addition->updated_at }}
                                    </p>
                                    @if ($addition->created_at != $addition->updated_at)
                                        <p class="text-sm font-mono text-gray-700 dark:text-gray-300">
                                            更新日時： {{ $addition->updated_at }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- 認証ユーザーの補足の場合 --}}
                            @if ($addition->user == Auth::user())
                                {{-- 補足編集メソッド発火時 --}}
                                @if ($addition_editing && ($addition->id == $edit_addition_id))
                                    <form action="{{ route('additions.update', [$query, $answer, $addition]) }}" method="post">
                                        @csrf
                                        @method('PATCH')

                                        <x-text-area id="content" class="mt-1 w-full h-[120px] dark:!bg-gray-900" type="text" name="content" autofocus required>{{ old('content', $addition->content) }}</x-text-area>
                                        <div class="flex justify-end items-center mt-4">
                                            <div class="text-blue-500 mr-4">
                                                <button type="submit">更新</button>
                                            </div>
                                            <div class="text-red-500">
                                                <a href="{{ route('queries.show', $query) }}">キャンセル</a>
                                            </div>
                                        </div>
                                    </form>
                                @else
                                {{-- 通常時 --}}
                                    <p class="mb-4 text-gray-700 dark:text-gray-300">{!! nl2br(e($addition->content)) !!}</p>
                                    <div class="flex justify-end">
                                        <div class="text-blue-500 mr-4">
                                            <a href="{{ route('additions.edit', [$query, $answer, $addition]) }}" class="hover:underline">編集</a>
                                        </div>
                                        <div class="text-red-500">
                                            <form onsubmit="return confirm('本当に削除しますか？')" action="{{ route('additions.destroy', [$query, $answer, $addition]) }}" method="post">
                                                @method('DELETE')
                                                @csrf

                                                <button class="text-red-500 hover:underline">
                                                    削除
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <p class="mb-4 text-gray-700 dark:text-gray-300">{!! nl2br(e($addition->content)) !!}</p>
                            @endif
                        </div>
                    </div>
                @endforeach

                {{-- 補足の新規投稿フォーム --}}
                <div class="flex justify-center">
                    <div class="block px-8 py-6  w-11/12 bg-white rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <form action="{{ route('additions.store', [$query, $answer]) }}" method="post">
                            @csrf

                            <div class="text-center mb-4">
                                <x-input-label for="content"><p class="text-xl font-bold">補足の投稿</p></x-input-label>
                            </div>
                            <x-text-area id="content" class="mt-1 w-full h-[120px] dark:!bg-gray-900" type="text" name="content" required/>
                            <button class="mt-4 w-full font-bold text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-5 py-3 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                補足を投稿する
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            {{-- 回答の新規投稿フォーム --}}
            <div class="block px-8 py-6 bg-white rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <form action="{{ route('answers.store', $query) }}" method="post">
                    @csrf

                    <div class="text-center mb-4">
                        <x-input-label for="content"><p class="text-xl font-bold">新しい回答の投稿</p></x-input-label>
                    </div>
                    <x-text-area id="content" class="mt-1 w-full h-[180px] dark:!bg-gray-900" type="text" name="content" required/>
                    <button class="mt-4 w-full font-bold text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-5 py-3 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        回答を投稿する
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        'use strict';

        function toggleAdditions(answerId) {
            const showBtn = document.getElementById('showAdditionsBtn_' + answerId);
            const hideBtn = document.getElementById('hideAdditionsBtn_' + answerId);

            // 補足の表示および非表示を切り替える
            const additions = document.querySelectorAll('.additions_' + answerId);
            additions.forEach(function(addition) {
                addition.style.display = (addition.style.display == 'none') ? 'block' : 'none';
            });

            // ボタンのテキストを切り替える
            if (showBtn.style.display == 'none') {
                showBtn.style.display = 'block';
                hideBtn.style.display = 'none';
            } else {
                showBtn.style.display = 'none';
                hideBtn.style.display = 'block';
            }
        }

        function editAddtion() {
            const editingAddition = document.querySelector('.additions_' + <?= $answer_id ?>);

            if (editingAddition !== null) {
                editingAddition.style.display = 'block';
            }
        }

        editAddtion();
    </script>
</x-app-layout>
