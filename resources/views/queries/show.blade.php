<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
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
            <div class="block min-h-[400px] p-6 bg-white border-[2px] border-gray-200 rounded-lg shadow dark:bg-gray-950 dark:border-gray-700">
                <h2 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $query->title }}</h2>
                <div class="flex justify-between mb-6">
                    <div class="">
                        <p class="font-bold text-gray-700 dark:text-gray-300">
                            投稿者： {{ $query->user->name }}
                        </p>
                    </div>
                    <div class="">
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
            <p class="mb-4 font-bold text-gray-700 dark:text-gray-300">・回答数： {{ $query->answers->count() }} 件</p>
            @forelse ($query->answers as $answer)
                <div class="block p-6 bg-white border-[2px] border-gray-200 rounded-lg shadow dark:bg-gray-900 dark:border-gray-700">
                    <div class="flex justify-between">
                        <p class="mb-4 font-bold text-gray-700 dark:text-gray-300">
                            回答者： {{ $answer->user->name }}
                        </p>
                        <p class="mb-4 text-sm font-mono text-gray-700 dark:text-gray-300">
                            回答日： {{ $answer->updated_at }}
                        </p>
                    </div>
                    <p class="mb-4 font-bold text-gray-700 dark:text-gray-300">{{ $answer->content }}</p>
                </div>
                @forelse ($answer->additions as $addition)
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
                @empty
                @endforelse
            @empty
                <div class="pt-2">
                    <p class="text-white text-center">まだ回答はありません...</p>
                </div>
            @endforelse
        </div>
    </div>
    <script>

    </script>
</x-app-layout>
