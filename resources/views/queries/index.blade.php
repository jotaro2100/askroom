<x-app-layout>
    <div class="py-12">
        <div class="px-2 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div>
                <h1 class="flex justify-between mb-8 text-3xl">
                    <span class="text-gray-900 dark:text-white border-b-[1px]">{{ $title }}</span>
                    <a href="{{ route('queries.create') }}" type="button" class="block px-3 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">新しい質問</a>
                </h1>
            </div>
            @forelse ($queries as $query)
                <div>
                    <a href="{{ route('queries.show', $query) }}" class="block h-[200px] mb-4 p-6 bg-white border-[2px] border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-900 dark:border-gray-700 dark:hover:bg-gray-700">
                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ $query->title }}
                        </h5>
                        <p class="mb-2 font-bold text-gray-700 dark:text-gray-300">
                            投稿者： {{ $query->user->name }}
                        </p>
                        <p class="font-normal text-gray-700 dark:text-gray-400">
                            {{ $query->content }}
                        </p>
                    </a>
                </div>
            @empty
                <div class="pt-2">
                    <p class="text-white text-lg">質問が見つかりませんでした...</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
