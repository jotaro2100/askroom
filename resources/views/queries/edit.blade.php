<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h1 class="text-gray-900 dark:text-white text-center my-6 text-3xl">質問を編集</h1>
            <form method="post" action="{{ route('queries.update', $query) }}">
                @method('PATCH')
                @csrf

                <div class="mb-4">
                    <x-input-label for="title">題名</x-input-label>
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" value="{{ old('title', $query->title) }}" required/>
                </div>
                <div>
                    <x-input-label for="content">本文</x-input-label>
                    <x-text-area id="content" class="block mt-1 w-full min-h-max" type="text" name="content" required>{{ old('content', $query->content) }}</x-text-area>
                </div>
                <div>
                    <button class="mt-10 w-full font-bold text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-5 py-3 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        更新する
                    </button>
                    <div class="text-center mt-2">
                        <a href="{{ route('queries.show', $query) }}" class="text-red-600 font-bold">
                            キャンセル
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
