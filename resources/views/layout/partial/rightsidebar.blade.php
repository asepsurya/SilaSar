<div class="right-sidebar fixed right-0 bg-white dark:bg-black bottom-0 w-[280px] border-l border-black/10 dark:border-white/10 transition-all duration-300 shadow-2xl z-50 h-screen flex flex-col"
    x-data="rightSidebarData()">

    <!-- Tab Headers (Gemini Style - Icon Version) -->
    <div
        class="flex items-center justify-between border-b border-black/5 dark:border-white/5 p-2 bg-gray-50/50 dark:bg-white/5 gap-1">
        <!-- Log Aktivitas Tab -->
        <button @click="activeTab = 'logs'" title="Log Aktivitas"
            class="flex-1 flex items-center justify-center py-2 rounded-lg transition-all"
            :class="activeTab === 'logs' ? 'bg-white dark:bg-white/10 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-white/70'">
            <i class="ph ph-list-bullets text-lg"></i>
        </button>

        <!-- Notifikasi Tab -->
        <button @click="activeTab = 'notifications'" title="Notifikasi"
            class="flex-1 flex items-center justify-center py-2 rounded-lg transition-all relative"
            :class="activeTab === 'notifications' ? 'bg-orange-100 dark:bg-orange-500/20 shadow-sm text-orange-600 dark:text-orange-300' : 'bg-orange-50 dark:bg-orange-500/10 text-orange-500 dark:text-orange-400 hover:bg-orange-100 dark:hover:bg-orange-500/20'">
            <i class="ph ph-bell text-lg"></i>
            <span x-show="unreadNotifCount > 0"
                class="absolute -top-1 -right-2 flex h-4 w-4 items-center justify-center text-[9px] font-bold rounded-full animate-bounce"
                style="background: rgba(252, 165, 165, 0.25); color: #7f1d1d; border: 1px solid rgba(252, 165, 165, 0.5)"
                x-text="unreadNotifCount > 99 ? '99+' : unreadNotifCount"></span>
        </button>

        <!-- Chat AI Tab -->
        <button @click="activeTab = 'ai'" title="Chat AI"
            class="flex-1 flex items-center justify-center py-2 rounded-lg transition-all"
            :class="activeTab === 'ai' ? 'bg-white dark:bg-white/10 shadow-sm text-purple-600 dark:text-purple-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-white/70'">
            <i class="ph ph-chat-teardrop-dots text-lg"></i>
        </button>

        <!-- Close Button -->
        <button type="button" class="ml-1 p-1.5 text-gray-400 hover:text-red-500 transition-colors" title="Tutup"
            @click="$store.app.rightSidebar()">
            <i class="ph ph-x-circle text-xl"></i>
        </button>
    </div>

    <!-- Tab Content: Logs -->
    <div x-show="activeTab === 'logs'" class="flex-1 flex flex-col overflow-hidden" x-transition.opacity>
        <div class="px-5 py-4 border-b border-black/5 dark:border-white/5 flex items-center justify-between">
            <h4 class="font-semibold text-black dark:text-white text-sm">Aktivitas Terakhir</h4>
            <div class="relative">
                <select x-model="logFilter" @change="fetchLogs()"
                    class="appearance-none text-[10px] font-bold bg-gray-100 dark:bg-white/5 border-0 rounded-full pl-3 pr-6 py-1 focus:ring-1 focus:ring-blue-500 dark:text-gray-300 cursor-pointer">
                    <option value="semua">Semua</option>
                    <option value="login">Login</option>
                    <option value="transaksi">Transaksi</option>
                    <option value="sistem">Sistem</option>
                </select>
                <i
                    class="ph ph-caret-down absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-5 py-5 space-y-6 custom-scrollbar" id="activity-log-container">
            <template x-if="isLoadingLogs">
                <div class="space-y-5 animate-pulse">
                    <template x-for="i in 5">
                        <div class="flex gap-3">
                            <div class="h-6 w-6 bg-gray-200 dark:bg-gray-800 rounded-lg"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-full"></div>
                                <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded w-1/3"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <template x-for="log in logs" :key="log.id">
                <div class="flex gap-3 items-start text-sm">
                    <!-- Icon Log dengan Class CSS Murni -->
                    <div class="log-icon-box" :class="{
                        'login': log.type === 'login',
                        'transaksi': log.type === 'transaksi',
                        'sistem': log.type === 'sistem',
                        'default': !['login', 'transaksi', 'sistem'].includes(log.type)
                    }">
                        <i class="ph text-base" :class="'ph-' + log.icon"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[12px] text-gray-900 dark:text-white leading-tight">
                            <span x-text="log.description"></span> oleh <strong x-text="log.causer_name"></strong>
                        </p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-1.5 font-medium"
                            x-text="log.created_at"></p>
                    </div>
                </div>
            </template>

            <template x-if="!isLoadingLogs && logs.length === 0">
                <p class="text-center text-xs text-gray-500 dark:text-gray-400 py-10">Tidak ada aktivitas ditemukan.</p>
            </template>
        </div>
    </div>

    <!-- Tab Content: Notifikasi Pembayaran -->
    <div x-show="activeTab === 'notifications'" class="flex-1 flex flex-col overflow-hidden" x-transition.opacity
        style="display:none;">
        <div class="px-5 py-4 border-b border-black/5 dark:border-white/5 flex items-center justify-between">
            <h4 class="font-semibold text-black dark:text-white text-sm">Notifikasi Pembayaran</h4>
            <div class="flex items-center gap-2">
                <span x-text="unreadNotifCount"
                    class="text-[10px] bg-orange-100 dark:bg-orange-500/20 text-orange-700 dark:text-orange-300 px-2 py-0.5 rounded-full font-bold"></span>
                <button @click="markAllNotifRead()"
                    class="text-[10px] text-orange-600 dark:text-orange-400 hover:underline font-medium"
                    x-show="unreadNotifCount > 0">Tandai Dibaca</button>
            </div>
        </div>

        <div class="px-4 py-3 border-b border-black/5 dark:border-white/5">
            <select x-model="notifFilter" @change="fetchNotifications()"
                class="appearance-none text-[10px] font-bold bg-gray-100 dark:bg-white/5 border-0 rounded-full pl-3 pr-6 py-1 focus:ring-1 focus:ring-orange-500 dark:text-gray-300 cursor-pointer w-full">
                <option value="all">Semua</option>
                <option value="unread">Belum Dibaca</option>
                <option value="payment_due">Akan Jatuh Tempo</option>
                <option value="payment_overdue">Jatuh Tempo</option>
                <option value="payment_received">Diterima</option>
                <option value="payment_partial">Sebagian</option>
            </select>
        </div>

        <!-- Single Scroll Container: Jatuh Tempo + Notifications -->
        <div class="flex-1 overflow-y-auto px-2 py-2 custom-scrollbar" id="notification-scroll">
            <!-- Section Pembayaran Jatuh Tempo -->
            <div x-show="jatuhTempo.length > 0" x-cloak class="border-b border-black/5 dark:border-white/5">
                <div class="px-2 py-2 bg-amber-50/50 dark:bg-amber-500/5 flex items-center justify-between">
                    <h5 class="flex items-center gap-2 text-sm font-semibold text-amber-800 dark:text-amber-300">
                        <i class="ph ph-warning-circle text-base"></i>
                        <span>Pembayaran Jatuh Tempo</span>
                    </h5>
                    <span
                        class="text-[10px] font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 px-2 py-0.5 rounded-full"
                        x-text="jatuhTempo.length"></span>
                </div>

                {{-- <div class="px-2 py-2 space-y-1">
                    <template x-if="isLoadingJatuhTempo">
                        <div class="space-y-2 animate-pulse">
                            <template x-for="i in 3">
                                <div class="flex gap-3 p-3 bg-gray-50 dark:bg-white/5 rounded-xl">
                                    <div class="h-8 w-8 bg-gray-200 dark:bg-gray-800 rounded-lg"></div>
                                    <div class="flex-1 space-y-2">
                                        <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-full"></div>
                                        <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded w-2/3"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="!isLoadingJatuhTempo">
                        <template x-for="item in jatuhTempo" :key="item.id">
                            <a :href="item.transaksi_url"
                                class="flex gap-3 p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition">
                                <div class="notif-icon-box" :class="item.days_diff < 0 ? 'overdue' : 'due'">
                                    <i class="ph text-base"
                                        :class="item.days_diff < 0 ? 'ph-warning-circle' : 'ph-clock'"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[12px] font-bold text-gray-900 dark:text-white truncate"
                                        x-text="item.kode_transaksi"></p>
                                    <p class="text-[11px] text-gray-600 dark:text-gray-400 truncate"
                                        x-text="item.mitra"></p>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5"
                                        x-text="item.due_date_formatted"></p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-[11px] font-bold text-gray-900 dark:text-white"
                                        x-text="'Rp ' + (item.total ? Number(item.total).toLocaleString('id-ID') : '0')">
                                    </p>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[2px] font-bold"
                                        :class="item.days_diff < 0 ? 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' : 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400'"
                                        x-text="item.days_diff < 0 ? 'Lew. ' + Math.abs(item.days_diff) + 'h' : (item.days_diff === 0 ? 'Hari ini' : item.days_diff + ' hari')"></span>
                                </div>
                            </a>
                        </template>
                    </template>
                </div> --}}
            </div>

            <div id="notification-list" class="space-y-4 py-2">
                <template x-if="isLoadingNotifs">
                    <div class="space-y-2 animate-pulse">
                        <template x-for="i in 5">
                            <div class="flex gap-3 p-2 bg-gray-50 dark:bg-white/5 rounded-xl">
                                <div class="h-8 w-8 bg-gray-200 dark:bg-gray-800 rounded-lg"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-3 bg-gray-100 dark:bg-gray-800 rounded w-full"></div>
                                    <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded w-2/3"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="!isLoadingNotifs">
                    <template x-for="item in notifications" :key="item.id">
                        <a :href="item.transaksi ? '/transaksi/' + item.transaksi.id : 'javascript:;'"
                            @click.prevent="markNotifRead(item.id)"
                            class="flex gap-3 p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <div class="notif-icon-box" :class="{
                                'overdue': item.type === 'payment_overdue',
                                'due': item.type === 'payment_due',
                                'received': item.type === 'payment_received',
                                'partial': item.type === 'payment_partial',
                                'default': !['payment_overdue', 'payment_due', 'payment_received', 'payment_partial'].includes(item.type)
                            }">
                                <i class="ph text-base" :class="{
                                        'ph-warning-circle': item.type === 'payment_overdue',
                                        'ph-clock': item.type === 'payment_due',
                                        'ph-check-circle': item.type === 'payment_received',
                                        'ph-percent': item.type === 'payment_partial',
                                        'ph-bell': !['payment_overdue', 'payment_due', 'payment_received', 'payment_partial'].includes(item.type)
                                    }"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-[12px] font-bold text-gray-900 dark:text-white truncate"
                                        x-text="item.title"></p>
                                    <span x-show="!item.is_read"
                                        class="flex-none h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                                </div>
                                <p class="text-[11px] text-gray-600 dark:text-gray-400 truncate" x-text="item.message">
                                </p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">
                                    <span x-text="item.created_at"></span>
                                    <template x-if="item.transaksi">
                                        <span> · <span x-text="item.transaksi.kode"></span> — <span
                                                x-text="item.transaksi.mitra"></span> — Rp <span
                                                x-text="Number(item.transaksi.total || 0).toLocaleString('id-ID')"></span></span>
                                    </template>
                                </p>
                            </div>
                        </a>
                    </template>
                </template>

                <template x-if="!isLoadingNotifs && notifications.length === 0">
                    <div class="text-center py-10">
                        <i class="ph ph-bell-simple-slash text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tidak ada notifikasi pembayaran</p>
                    </div>
                </template>
            </div>
        </div>
        <!-- End Single Scroll Container -->
    </div>

    <!-- Tab Content: AI Chat -->
    <div x-show="activeTab === 'ai'" class="flex flex-col h-full relative" x-transition.opacity style="display:none;">
        <!-- Header Chat: New Chat & History -->
        <div
            class="px-4 py-3 border-b border-black/5 dark:border-white/5 flex items-center justify-between z-10 shadow-sm">
            <button @click="showHistory = !showHistory"
                class="flex items-center gap-2 p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-all relative group">
                <i class="ph ph-clock-counter-clockwise text-xl text-gray-500 group-hover:text-blue-500"></i>
                <span
                    class="text-[10px] font-bold uppercase tracking-wider text-gray-400 group-hover:text-blue-500">Riwayat</span>
                <span x-show="chatThreads.length > 1"
                    class="absolute top-1 right-1 w-2 h-2 bg-blue-500 rounded-full border-2 border-white dark:border-black"></span>
            </button>
            <button @click="createNewThread()"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 shadow-md shadow-blue-500/20 transition-all active:scale-95">
                <i class="ph ph-plus-circle text-base"></i> Chat Baru
            </button>
        </div>

        <!-- History Sidebar (Overlay) -->
        <div x-show="showHistory" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full" @click.away="showHistory = false"
            class="absolute inset-0 bg-white dark:bg-black z-20 flex flex-col border-r border-black/10 dark:border-white/10 shadow-2xl"
            style="display: none;">

            <div class="p-5 border-b border-black/5 dark:border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph ph-clock-counter-clockwise text-blue-500"></i>
                    <h5 class="text-[11px] font-bold uppercase tracking-widest text-gray-500">Riwayat Percakapan
                    </h5>
                </div>
                <button @click="showHistory = false"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                    <i class="ph ph-x text-lg"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-3 space-y-2 custom-scrollbar">
                <template x-for="thread in chatThreads" :key="thread.id">
                    <div class="group relative flex flex-col gap-1 p-4 rounded-2xl cursor-pointer transition-all border"
                        :class="activeThreadId == thread.id ? 'bg-blue-50 dark:bg-blue-500/10 border-blue-500/30' : 'hover:bg-gray-50 dark:hover:bg-white/5 border-transparent'"
                        @click="switchThread(thread.id)">
                        <div class="flex justify-between items-start gap-2">
                            <p class="text-[12px] font-bold truncate leading-tight flex-1"
                                :class="activeThreadId == thread.id ? 'text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200'"
                                x-text="thread.title"></p>
                            <button @click.stop="deleteThread(thread.id)"
                                class="p-1.5 text-gray-300 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-all">
                                <i class="ph ph-trash text-sm"></i>
                            </button>
                        </div>
                        <p class="text-[10px] text-gray-400 font-medium" x-text="thread.date"></p>
                    </div>
                </template>

                <template x-if="chatThreads.length === 0">
                    <div class="text-center py-10 opacity-50">
                        <i class="ph ph-chat-centered-dots text-3xl mb-2"></i>
                        <p class="text-xs">Belum ada riwayat</p>
                    </div>
                </template>
            </div>
        </div>

        <!-- CHAT AREA -->
        <div id="ai-chat-container" class="flex-1 overflow-y-auto custom-scrollbar px-4 py-5 space-y-6">
            <!-- Welcome -->
            <template x-if="chatHistory.length === 0">
                <div class="pt-6 space-y-6">
                    <div class="mb-5">
                        <h1 class="text-xl font-semibold leading-snug">
                            <span class="text-blue-600 dark:text-blue-400">
                                Halo, {{ explode(' ', auth()->user()->name)[0] }}
                            </span>
                        </h1>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Ada yang bisa saya bantu hari ini?
                        </p>
                    </div>

                    <div class="space-y-3 mb-5">
                        <button @click="sendQuickPrompt('Ringkaskan aktivitas hari ini')"
                            class="w-full flex items-center gap-3 text-left px-4 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/5 hover:border-blue-400/30 transition mb-3">
                            <i class="ph ph-sparkle text-blue-500 text-lg"></i>
                            <span class="text-xs text-gray-700 dark:text-white">Ringkaskan aktivitas hari ini</span>
                        </button>

                        <button @click="sendQuickPrompt('Cari info penting dari log')"
                            class="w-full flex items-center gap-3 text-left px-4 py-3 rounded-2xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/5 hover:border-blue-400/30 transition">
                            <i class="ph ph-magnifying-glass text-emerald-500 text-lg"></i>
                            <span class="text-xs text-gray-700 dark:text-white">Cari info penting dari log</span>
                        </button>
                    </div>
                </div>
            </template>

            <!-- Messages -->
            <template x-for="(msg, index) in chatHistory" :key="index">
                <div class="flex mb-5" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                    <!-- AI -->
                    <template x-if="msg.role === 'ai'">
                        <div class="w-full">
                            <div class="w-full min-w-0 overflow-x-auto">
                                <div class="text-[13px] leading-relaxed text-gray-800 dark:text-gray-200 ai-prose"
                                    x-html="formatMessage(msg.content)"></div>
                            </div>
                        </div>
                    </template>

                    <!-- USER -->
                    <template x-if="msg.role === 'user'">
                        <div class="max-w-[80%] px-4 py-3 rounded-2xl bg-blue-600 text-white shadow-md">
                            <p class="text-[13px] leading-relaxed whitespace-pre-wrap" x-text="msg.content"></p>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Thinking -->
            <template x-if="isAiTyping">
                <div class="w-full">
                    <div class="flex gap-2 items-center">
                        <span class="text-[13px] text-gray-500 dark:text-gray-400 font-medium whitespace-nowrap">Sedang
                            memikirkan...</span>
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce ml-1"></span>
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce"
                            style="animation-delay:.2s"></span>
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce"
                            style="animation-delay:.4s"></span>
                    </div>
                </div>
            </template>
        </div>

        <!-- INPUT -->
        <div class="px-4 pb-4 pt-3 border-t border-black/5 dark:border-white/5">
            <div
                class="relative rounded-2xl bg-gray-50 dark:bg-white/5 border border-black/10 dark:border-white/10 transition-all duration-200 focus-within:border-blue-500/40 focus-within:ring-4 focus-within:ring-blue-500/10">
                <textarea x-model="chatInput" rows="1" @keydown.enter="handleEnter" @input="resizeTextarea"
                    :disabled="isAiTyping" placeholder="Tanya asisten TIDESSA..."
                    class="w-full bg-transparent py-3 px-4 pr-12 text-[13px] leading-relaxed text-black dark:text-white placeholder-gray-400 dark:placeholder-gray-500 resize-none min-h-[48px] max-h-32 focus:outline-none custom-scrollbar"></textarea>

                <button @click="sendMessage()" :disabled="chatInput.trim()==='' || isAiTyping" type="button"
                    title="Kirim pesan"
                    class="absolute right-3 bottom-3 w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-200"
                    :class="(chatInput.trim()==='' || isAiTyping) ? 'bg-white dark:bg-white/10 text-gray-300 dark:text-gray-600 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-700 shadow-md shadow-blue-500/25 active:scale-95'">
                    <i class="ph ph-paper-plane-tilt text-base"></i>
                </button>
            </div>

            <div class="flex items-center justify-between mt-2 px-1">
                <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500">
                    <kbd
                        class="px-1.5 py-0.5 rounded bg-white dark:bg-white/10 border border-black/5 dark:border-white/10 font-sans">Enter</kbd>
                    Kirim
                    <span class="mx-1">·</span>
                    <kbd
                        class="px-1.5 py-0.5 rounded bg-white dark:bg-white/10 border border-black/5 dark:border-white/10 font-sans">Shift
                        + Enter</kbd>
                    Baris baru
                </p>
                <span x-show="chatInput.length > 0" x-text="chatInput.length"
                    class="text-[10px] font-medium text-gray-400 dark:text-gray-500 tabular-nums"></span>
            </div>
        </div>
    </div>

    <style>
        /* ========================================================
           1. Custom Scrollbar Styles
           ======================================================== */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
        }

        /* ========================================================
           2. Activity Log Icon Box Styles (CSS Murni)
           ======================================================== */
        .log-icon-box {
            height: 1.75rem;
            width: 1.75rem;
            flex: none;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Login (Biru) */
        .log-icon-box.login {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .dark .log-icon-box.login {
            background-color: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        /* Transaksi (Hijau/Emerald) */
        .log-icon-box.transaksi {
            background-color: #d1fae5;
            color: #059669;
        }

        .dark .log-icon-box.transaksi {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        /* Sistem (Ungu) */
        .log-icon-box.sistem {
            background-color: #f3e8ff;
            color: #9333ea;
        }

        .dark .log-icon-box.sistem {
            background-color: rgba(168, 85, 247, 0.2);
            color: #c084fc;
        }

        /* Default (Abu-abu) */
        .log-icon-box.default {
            background-color: #f3f4f6;
            color: #4b5563;
        }

        .dark .log-icon-box.default {
            background-color: rgba(255, 255, 255, 0.05);
            color: #9ca3af;
        }

        /* ========================================================
           3. Notification Icon Box Styles (CSS Murni)
           ======================================================== */
        .notif-icon-box {
            height: 2rem;
            width: 2rem;
            flex: none;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Overdue (Merah) */
        .notif-icon-box.overdue {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .dark .notif-icon-box.overdue {
            background-color: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        /* Due / Akan Jatuh Tempo (Kuning/Amber) */
        .notif-icon-box.due {
            background-color: #fef3c7;
            color: #d97706;
        }

        .dark .notif-icon-box.due {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }

        /* Received / Diterima (Hijau/Emerald) */
        .notif-icon-box.received {
            background-color: #d1fae5;
            color: #059669;
        }

        .dark .notif-icon-box.received {
            background-color: rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        /* Partial / Sebagian (Biru) */
        .notif-icon-box.partial {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .dark .notif-icon-box.partial {
            background-color: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        /* Default / Info Lainnya (Ungu) */
        .notif-icon-box.default {
            background-color: #f3e8ff;
            color: #9333ea;
        }

        .dark .notif-icon-box.default {
            background-color: rgba(168, 85, 247, 0.2);
            color: #c084fc;
        }

        /* ========================================================
           4. Markdown AI Prose Styles
           ======================================================== */
        .ai-prose {
            max-width: 100%;
            overflow-x: hidden;
        }

        .ai-prose p {
            margin-bottom: 0.75rem;
            line-height: 1.6;
        }

        .ai-prose p:last-child {
            margin-bottom: 0;
        }

        .ai-prose strong,
        .ai-prose b {
            font-weight: 800 !important;
            color: inherit;
        }

        .ai-prose em,
        .ai-prose i {
            font-style: italic !important;
        }

        .ai-prose h3 {
            margin-top: 1.25rem !important;
            font-weight: 700;
            color: #2563eb;
        }

        .dark .ai-prose h3 {
            color: #60a5fa;
        }

        .ai-prose ul {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin-bottom: 0.75rem;
        }

        .ai-prose li {
            margin-bottom: 0.25rem;
        }

        .ai-prose code {
            background: rgba(0, 0, 0, 0.05);
            padding: 0.1rem 0.3rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.85em;
        }

        .dark .ai-prose code {
            background: rgba(255, 255, 255, 0.1);
        }

        .ai-prose pre {
            margin: 1rem 0;
            background: #1e1e1e;
            color: #fff;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
        }

        .ai-prose hr {
            border: 0;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin: 1.5rem 0;
        }

        .dark .ai-prose hr {
            border-top-color: rgba(255, 255, 255, 0.1);
        }

        /* ========================================================
           5. Responsive Table inside AI Prose
           ======================================================== */
        .ai-prose table {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-collapse: collapse;
            margin: 1rem 0;
            font-size: 12px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }

        .dark .ai-prose table {
            border-color: rgba(255, 255, 255, 0.05);
        }

        .ai-prose th {
            background: rgba(0, 0, 0, 0.02);
            font-weight: 700;
            text-align: left;
            padding: 10px 12px;
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
        }

        .dark .ai-prose th {
            background: rgba(255, 255, 255, 0.02);
            border-bottom-color: rgba(255, 255, 255, 0.05);
        }

        .ai-prose td {
            padding: 8px 12px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            min-width: 100px;
        }

        .dark .ai-prose td {
            border-bottom-color: rgba(255, 255, 255, 0.05);
        }

        .ai-prose tr:last-child td {
            border-bottom: none;
        }
    </style>
</div>