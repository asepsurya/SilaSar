<div
    class="right-sidebar fixed right-0 bg-white dark:bg-black bottom-0  w-[280px] border-l border-black/10 dark:border-white/10 transition-all duration-300">
    <div class="flex flex-col gap-6 px-6 py-[22px] h-screen overflow-y-auto overflow-x-hidden" id="rigtcontent">
        <h4 class="font-semibold text-black dark:text-white mb-5">Log Aktivitas</h4>
        @foreach($logs as $log)
        <div class="flex gap-3 items-start mb-3 text-sm text-gray-700 dark:text-gray-300">
            <div class="h-6 w-6 flex-none p-1 text-black bg-lightblue-100 rounded-lg">
                <x-icon name="users" class="text-gray-600" />
            </div>
            <div class="flex-1">
                <p class="mb-2 text-sm text-gray-900 dark:text-white">
                    {{ $log->description }} oleh <strong>{{ $log->causer->name ?? 'Sistem' }}</strong>
                </p>
                <p class="text-xs dark:text-gray-400 text-gray-500">
                    {{ $log->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
        @endforeach
        <div class="text-center dark:text-gray-400 text-sm">
            @if($logs->count() === 0)
            <p class="text-gray-500 dark:text-gray-400">Tidak ada aktivitas terbaru.</p>
            @endif
        </div>
    </div>
</div>
</div>
</div>