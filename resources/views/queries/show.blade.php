<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
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
            <div class="block min-h-[400px] p-6 bg-white border-[2px] border-gray-200 rounded-lg shadow dark:bg-gray-950 dark:border-gray-700">
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
                <div class="block p-6 bg-white border-[2px] border-gray-200 rounded-lg shadow dark:bg-gray-900 dark:border-gray-700">
                    <div class="flex justify-between mb-2">
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

                    @if ($answer->user == Auth::user())
                        @if ($editing && ($answer->id == $edit_answer_id))
                            <!-- 編集フォームを表示 -->
                            <form action="{{ route('answers.update', [$query, $answer]) }}" method="post">
                                @csrf
                                @method('PATCH')

                                <x-text-area id="content" class="mt-1 w-full h-[180px]" type="text" name="content" autofocus required>{{ old('content', $answer->content) }}</x-text-area>
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
                    @endif
                </div>

                {{-- 補足一覧 --}}
                @foreach ($answer->additions as $addition)
                    <div class="block p-6 bg-white border-[2px] border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex justify-between">
                            <p class="mb-4 font-bold text-gray-700 dark:text-gray-300">
                                補足者： {{ $addition->user->name }}
                            </p>
                            <p class="mb-4 text-sm font-mono text-gray-700 dark:text-gray-300">
                                回答日： {{ $addition->updated_at }}
                            </p>
                        </div>
                        <p class="mb-4 font-bold text-gray-700 dark:text-gray-300">{{ $addition->content }}</p>
                    </div>
                @endforeach
            @endforeach
            <div class="block px-8 py-6 bg-white border-[2px] border-gray-200 rounded-lg shadow dark:bg-gray-950 dark:border-gray-700">
                <form action="{{ route('answers.store', $query) }}" method="post">
                    @csrf

                    <div class="text-center mb-4">
                        <x-input-label for="content"><p class="text-xl font-bold">新しい回答の投稿</p></x-input-label>
                    </div>
                    <x-text-area id="content" class="mt-1 w-full h-[180px]" type="text" name="content" required/>
                    <button class="mt-4 w-full font-bold text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-5 py-3 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        回答する
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
