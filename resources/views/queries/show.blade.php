<x-app-layout>
    <x-slot name="title">
        {{ $query->title }}
    </x-slot>

    <div class="py-12">
        <div class="px-2 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- トップメニュー --}}
            <div class="flex justify-between items-center">
                <div class="text-lg text-blue-500">
                    &lsaquo;&lsaquo; <a href="{{ route('queries.index') }}" class="hover:underline">一覧に戻る</a>
                </div>
                @can('update', $query)
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
                @endcan
            </div>

            {{-- 質問本文 --}}
            <div class="block min-h-[400px] p-6 bg-white border-[2px] border-gray-200 rounded-lg shadow-md dark:bg-slate-900 dark:border-gray-700">
                <h2 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $query->title }}</h2>
                <div class="flex justify-between mb-4 md:flex-row">
                    <div>
                        <p class="font-bold text-gray-700 dark:text-gray-300 mb-1">
                            投稿者： {{ $query->user->name }}
                        </p>
                        <div>
                            <p class="text-sm font-mono text-gray-700 dark:text-gray-400">
                                投稿： {{ $query->created_at }}
                            </p>
                            @if ($query->created_at != $query->updated_at)
                                <p class="text-sm font-mono text-gray-700 dark:text-gray-400">
                                    更新： {{ $query->updated_at }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="mb-3">
                            @if ($query->resolve)
                                <p class="text-green-500 font-bold border border-green-500 px-2 py-1 rounded-md">解決済</p>
                            @else
                                <p class="text-orange-500 font-bold border border-orange-500 px-2 py-1 rounded-md">未解決</p>
                            @endif
                        </div>
                        @can('update', $query)
                            <div class="text-sm text-blue-500 hover:underline">
                                @if ($query->resolve)
                                    <a href="{{ route('queries.resolve', $query) }}">未解決にする</a>
                                @else
                                    <a href="{{ route('queries.resolve', $query) }}">解決済にする</a>
                                @endif
                            </div>
                        @endcan
                    </div>
                </div>
                <p class="font-normal text-gray-100 dark:text-gray-100">
                    {!! nl2br(e($query->content)) !!}
                </p>
            </div>
            <div class="flex justify-between items-center">
                <p class="font-bold text-gray-700 dark:text-gray-300 ml-1">{{ $query->answers->count() }} 件の回答が見つかりました</p>
                <a href="{{ route('queries.show', $query) }}#answer-form" type="button" class="block px-3 py-2 text-sm font-bold text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">回答を投稿</a>
            </div>

            {{-- 回答一覧 --}}
            @foreach ($answers as $answer)
                <div id="answer_{{ $answer->id }}" class="bg-white rounded-lg shadow-md p-6 mb-6 dark:bg-gray-800 dark:border-slate-800">
                    <div class="flex justify-between">
                        <div class="flex flex-col mb-5">
                            <div>
                                <p class="mb-1 text-lg font-bold text-gray-700 dark:text-gray-300">
                                    回答者： {{ $answer->user->name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-mono text-gray-700 dark:text-gray-400">
                                    投稿: {{ $answer->created_at }}
                                </p>
                                @if ($answer->created_at != $answer->updated_at)
                                    <p class="text-sm font-mono text-gray-700 dark:text-gray-400">
                                        更新: {{ $answer->updated_at }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div>
                            @can('update', $answer)
                                @unless ($answer_editing || $addition_editing)
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
                            @endcan
                        </div>
                    </div>

                    {{-- 認証ユーザーの回答の場合 --}}
                    @can('update', $answer)
                        {{-- 回答編集メソッド発火時 --}}
                        @if ($answer_editing && ($answer->id == $edit_answer_id))
                            {{-- 回答編集フォームを表示 --}}
                            <form action="{{ route('answers.update', [$query, $answer]) }}" method="post">
                                @csrf
                                @method('PATCH')

                                <x-input-error :messages="$errors->get('answer_content')" class="mt-2" />
                                <x-text-area id="answer_content" class="mt-1 w-full !min-h-[200px] dark:!bg-gray-900" type="text" name="answer_content" autofocus required>
                                    {{ old('answer_content', $answer->content) }}
                                </x-text-area>
                                <div class="flex justify-end items-center mt-4">
                                    <div class="text-blue-500 mr-4">
                                        <button type="submit" class="hover:underline">更新</button>
                                    </div>
                                    <div class="text-red-500">
                                        <button>
                                            <a href="{{ route('queries.show', $query) }}" class="hover:underline">キャンセル</a>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        {{-- 通常時 --}}
                        @else
                            <div class="min-h-[180px]">
                                <p class="text-gray-700 dark:text-gray-300 break-words">{!! nl2br(e($answer->content)) !!}</p>
                            </div>
                        @endif
                    @else
                        <div class="min-h-[180px]">
                            <p class="text-gray-700 dark:text-gray-300 break-words">{!! nl2br(e($answer->content)) !!}</p>
                        </div>
                    @endcan

                    @if ($answer->additions->isEmpty())
                        @if (!$addition_editing && !$answer_editing)
                            <div class="text-white text-center cursor-pointer text-xs leading-3 hover:text-blue-500 w-fit mx-auto mt-6" onclick="toggleAdditions({{ $answer->id }})">
                                <p id="showAdditionsBtn_{{ $answer->id }}">補足を投稿する<br>&or;</p>
                                <p id="hideAdditionsBtn_{{ $answer->id }}" style="display: none;">&and;<br>キャンセル</p>
                            </div>
                        @endif
                    @else
                        @unless ($addition_editing || $answer_editing)
                            <div class="text-white text-center cursor-pointer text-xs leading-3 hover:text-blue-500 w-fit mx-auto mt-6" onclick="toggleAdditions({{ $answer->id }})">
                                <p id="showAdditionsBtn_{{ $answer->id }}">この回答の補足を見る<br>&or;</p>
                                <p id="hideAdditionsBtn_{{ $answer->id }}" style="display: none;" class="mb-8">&and;<br>閉じる</p>
                            </div>
                        @endif
                    @endif

                    {{-- 補足一覧 --}}
                    <div class="additions_{{ $answer->id }}" style="display: none;">
                        @foreach ($answer->additions->load('user') as $addition)
                            <div id="addition_{{ $addition->id }}" class="mt-6">
                                <div class="bg-white rounded-lg shadow-md p-4 dark:bg-slate-700">
                                    <div class="flex justify-between mb-4">
                                        <div class="flex flex-col justify-between">
                                            <div class="mb-1">
                                                <p class="font-bold text-gray-700 dark:text-gray-300">
                                                    補足者: {{ $addition->user->name }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs font-mono text-gray-700 dark:text-gray-400">
                                                    投稿: {{ $addition->created_at }}
                                                </p>
                                                @if ($addition->created_at != $addition->updated_at)
                                                    <p class="text-xs font-mono text-gray-700 dark:text-gray-400">
                                                        更新: {{ $addition->updated_at }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            @can('update', $addition)
                                                @unless ($answer_editing || $addition_editing)
                                                    <div class="flex justify-end">
                                                        <div class="text-blue-500 mr-4">
                                                            <a href="{{ route('additions.edit', [$query, $answer, $addition]) }}" class="hover:underline">編集</a>
                                                        </div>
                                                        <div class="text-red-500">
                                                            <form onsubmit="return confirm('本当に削除しますか？')" action="{{ route('additions.destroy', [$query, $answer, $addition]) }}" method="post">
                                                                @method('DELETE')
                                                                @csrf
                                                                <button class="hover:underline">削除</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>

                                    {{-- 認証ユーザーの補足の場合 --}}
                                    @can('update', $addition)
                                        {{-- 補足編集メソッド発火時 --}}
                                        @if ($addition_editing && ($addition->id == $edit_addition_id))
                                            <div>
                                                <form action="{{ route('additions.update', [$query, $answer, $addition]) }}" method="post">
                                                    @csrf
                                                    @method('PATCH')

                                                    <x-input-error :messages="$errors->get('addition_content' . $answer->id)" class="mt-2" />
                                                    <x-text-area id="addition_content{{ $answer->id }}" class="mt-1 w-full !h-[200px] dark:!bg-gray-900" type="text" name="addition_content{{ $answer->id }}" autofocus required>
                                                        {{ old('addition_content' . $answer->id, $addition->content) }}
                                                    </x-text-area>
                                                    <div class="flex justify-end items-center mt-4">
                                                        <div class="text-blue-500 mr-4">
                                                            <button type="submit" class="hover:underline">更新</button>
                                                        </div>
                                                        <div class="text-red-500">
                                                            <button>
                                                                <a href="{{ route('queries.show', $query) }}" class="hover:underline">キャンセル</a>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            {{-- 通常時 --}}
                                            <p class="mb-4 text-gray-700 dark:text-gray-300 break-words">{!! nl2br(e($addition->content)) !!}</p>
                                        @endif
                                    @else
                                        <p class="mb-4 text-gray-700 dark:text-gray-300 break-words">{!! nl2br(e($addition->content)) !!}</p>
                                    @endcan
                                </div>
                            </div>
                        @endforeach

                        {{-- 補足の新規投稿フォーム --}}
                        @unless ($addition_editing)
                            <div class="mt-8">
                                <form action="{{ route('additions.store', [$query, $answer]) }}" method="post">
                                    @csrf

                                    <div class="text-center mb-4">
                                        <x-input-label for="addition_content{{ $answer->id }}"><p class="text-lg font-bold">補足の投稿</p></x-input-label>
                                    </div>
                                    <x-input-error :messages="$errors->get('addition_content' . $answer->id)" class="error mt-2" />
                                    <x-text-area id="addition_content{{ $answer->id }}" class="mt-1 w-full !h-32 dark:!bg-gray-900" type="text" name="addition_content{{ $answer->id }}" required>
                                        {{ old('addition_content' . $answer->id) }}
                                    </x-text-area>
                                    <button class="mt-2 w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-xs px-2 py-2 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                        補足を投稿する
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            {{-- 回答の新規投稿フォーム --}}
            @unless ($answer_editing || $addition_editing)
                <div class="block px-4 py-6 bg-white rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 md:px-8">
                    <form id="answer-form" action="{{ route('answers.store', $query) }}" method="post">
                        @csrf

                        <div class="text-center mb-4">
                            <x-input-label for="answer_content"><p class="text-xl font-bold">新しい回答の投稿</p></x-input-label>
                        </div>
                        <x-input-error :messages="$errors->get('answer_content')" class="error mt-2" />
                        <x-text-area id="answer_content" class="mt-1 w-full !h-[300px] dark:!bg-gray-900" type="text" name="answer_content" required>
                            {{ old('answer_content') }}
                        </x-text-area>
                        <button type="submit" class="mt-4 w-full font-bold text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-5 py-3 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            回答を投稿する
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <script>
        'use strict';

        // 補足表示トグル
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

        // 補足編集時に該当の補足フォームを開いたままにする
        function editAddtion() {
            const editingAddition = document.querySelector('.additions_' + <?= $answer_id ?>);

            if (editingAddition !== null) {
                editingAddition.style.display = 'block';
            }
        }

        editAddtion();
    </script>

    @unless (is_null(session('ansId')))
        <script>
            // 新規補足のバリデーションエラー時と投稿時に該当のフォームを展開
            let ansId = {{ session('ansId') }};
            let expandBtn = document.getElementById('showAdditionsBtn_' + ansId);

            expandBtn.click();
        </script>
    @endif

    @unless (is_null(session('additionId')))
        <script>
            // 補足投稿完了後に該当の補足まで自動でスクロールする
            let additionId = {{ session('additionId') }};
            console.log(additionId);
            const targetAddition = document.getElementById('addition_' + additionId);

            if (targetAddition) {
                const elementPosition = targetAddition.getBoundingClientRect().top + window.pageYOffset-300;

                window.scrollTo({
                    top: elementPosition,
                });
            }
        </script>
    @endif

    <script>
        // バリデーションエラー時に該当のフォームまで自動でスクロールする
        const targetElement = document.querySelector('.error');

        if (targetElement) {
            const elementPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - 300;

            window.scrollTo({
                top: elementPosition,
            });
        }
    </script>
</x-app-layout>
