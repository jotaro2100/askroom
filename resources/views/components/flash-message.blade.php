@if (session('flash_message'))
    <div id="flash" class="!bg-green-500 absolute w-full text-center text-white py-1 text-sm font-bold z-10">
        {{ session('flash_message') }}
    </div>
@endif
@if ($errors->any())
    <div id="flash" class="!bg-red-500 absolute w-full text-center text-white py-1 text-sm font-bold z-10">
        投稿に失敗しました
    </div>
@endif
