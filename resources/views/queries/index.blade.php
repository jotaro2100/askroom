<x-app-layout>
    <div class="py-12">
        <div class="px-2 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div>
                <h1 class="flex justify-between mb-8 text-3xl items-center">
                    <span class="text-gray-900 dark:text-white border-b-[1px]">{{ $title }}</span>
                    <a href="{{ route('queries.create') }}" type="button" class="block px-3 py-2 text-sm font-bold text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">新しい質問</a>
                </h1>

                {{-- 検索バー --}}
                <div class="mb-8">
                    <form method="GET" action="{{ route($controller . '.' . $method) }}">
                        <div class="flex items-center">
                            <div class="bg-gray-900 flex-grow">
                                <div class="container mx-auto">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fa-solid fa-magnifying-glass fa-lg mr-3" style="color: #bbb;"></i>
                                        <input type="search" placeholder="{{$title}}を検索" name="search" value="@if (isset($search)) {{ $search }} @endif" class="bg-gray-800 text-white border-2 border-gray-700 focus:outline-none focus:bg-gray-700 rounded-lg py-2 pl-4 pr-4 w-full leading-5 transition-colors duration-300">
                                    </div>
                                </div>
                            </div>
                            <div class="text-white">
                                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 rounded-md text-sm px-4 py-2 mx-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">検索</button>
                                <button class="align-middle">
                                    <a href="{{ route($controller . '.' . $method) }}" class="text-red-500">クリア</a>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    {{ $queries->appends(request()->input())->links() }}
                </div>
            </div>
            @forelse ($queries as $query)
                <div>
                    <a href="{{ route('queries.show', $query) }}" class="block !h-[224px] mb-4 px-6 py-4 bg-white border-[2px] border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-slate-900 dark:border-gray-700 dark:hover:bg-gray-700">
                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ $query->title }}
                        </h5>
                        <p class="mb-2 font-bold text-gray-700 dark:text-gray-300">
                            投稿者： {{ $query->user->name }}
                        </p>
                        <div class="max-h-[120px] font-normal text-gray-700 dark:text-gray-400 overflow-hidden">
                            {!! nl2br(e($query->content)) !!}
                        </div>
                    </a>
                </div>
            @empty
                <div class="pt-2">
                    <p class="text-white text-lg">質問が見つかりませんでした...</p>
                </div>
            @endforelse
            <div>
                {{ $queries->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
