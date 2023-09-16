<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex justify-between items-center">
                <div class="text-lg text-white">
                    &lsaquo;&lsaquo; <a href="{{ route('queries.index') }}" class="hover:underline">一覧に戻る</a>
                </div>
            </div>
            <div class="block min-h-[400px] p-6 bg-white border-[2px] border-gray-200 rounded-lg shadow dark:bg-gray-950 dark:border-gray-700">
                <h2 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $query->title }}</h2>
                <div class="flex justify-between">
                    <p class="mb-4 font-bold text-gray-700 dark:text-gray-300">
                        投稿者： {{ $query->user->name }}
                    </p>
                    <p class="mb-4 text-sm font-mono text-gray-700 dark:text-gray-300">
                        作成日： {{ $query->created_at }}
                    </p>
                </div>
                <p class="font-normal text-gray-100 dark:text-gray-400">
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
                <p>回答はありません</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
