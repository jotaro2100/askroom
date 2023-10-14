@if (session('flash_message'))
    <div id="flash" class="mt-4 !bg-slate-800 border rounded-lg border-green-600 shadow-lg fixed text-center text-green-600 px-3 py-2 text-sm font-bold !z-50">
        <span>
            <i class="fa-solid fa-circle-check mr-1"></i>
            {{ session('flash_message') }}
        </span>
    </div>
@endif

@if ($errors->any())
    <div id="flash" class="mt-4 !bg-slate-800 border rounded-lg border-red-400 shadow-lg fixed text-center text-red-400 px-3 py-2 text-sm font-bold !z-50">
        <span>
            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
            投稿に失敗しました
        </span>
    </div>
@endif
