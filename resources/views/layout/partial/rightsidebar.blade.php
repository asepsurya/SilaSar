<div class="right-sidebar fixed right-0 bg-white dark:bg-black bottom-0 w-[280px] border-l border-black/10 dark:border-white/10 transition-all duration-300 shadow-2xl z-50 h-screen flex flex-col"
    x-data="rightSidebarData()">

    <!-- Tab Headers (Gemini Style) -->
    <div class="flex items-center border-b border-black/5 dark:border-white/5 p-2 bg-gray-50/50 dark:bg-white/5">
        <button @click="activeTab = 'logs'"
            class="flex-1 py-2 text-[11px] font-bold uppercase tracking-wider rounded-lg transition-all"
            :class="activeTab === 'logs' ? 'bg-white dark:bg-white/10 shadow-sm text-blue-600 dark:text-blue-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-white/70'">
            Log Aktivitas
        </button>
        <button @click="activeTab = 'ai'"
            class="flex-1 py-2 text-[11px] font-bold uppercase tracking-wider rounded-lg transition-all"
            :class="activeTab === 'ai' ? 'bg-white dark:bg-white/10 shadow-sm text-purple-600 dark:text-purple-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-white/70'">
            Chat AI
        </button>
        <button type="button" class="ml-2 p-1.5 text-gray-400 hover:text-red-500 transition-colors"
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
                    <div class="h-7 w-7 flex-none flex items-center justify-center rounded-lg shadow-sm"
                        :class="log.bg_class">
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

    <!-- Tab Content: AI Chat (Gemini Style Improved) -->
    <div x-show="activeTab === 'ai'" class="flex flex-col h-full relative" x-transition.opacity style="display:none;">

        <!-- Header Chat: New Chat & History -->
        <div
            class="px-4 py-3 border-b border-black/5 dark:border-white/5 flex items-center justify-between  z-10 shadow-sm">
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

            <div class="p-5 border-b border-black/5 dark:border-white/5 flex items-center justify-between ">
                <div class="flex items-center gap-2">
                    <i class="ph ph-clock-counter-clockwise text-blue-500"></i>
                    <h5 class="text-[11px] font-bold uppercase tracking-widest text-gray-500">Riwayat Percakapan</h5>
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

                    <div class="space-y-3 mb-5  ">

                        <button @click="sendQuickPrompt('Ringkaskan aktivitas hari ini')" class="w-full flex items-center gap-3 text-left px-4 py-3 rounded-2xl
                               bg-gray-50 dark:bg-white/5
                               border border-black/5 dark:border-white/5
                               hover:border-blue-400/30
                               transition mb-3 ">

                            <i class="ph ph-sparkle text-blue-500 text-lg"></i>

                            <span class="text-xs text-gray-700 dark:text-white">
                                Ringkaskan aktivitas hari ini
                            </span>

                        </button>

                        <button @click="sendQuickPrompt('Cari info penting dari log')" class="w-full flex items-center gap-3 text-left px-4 py-3 rounded-2xl
                               bg-gray-50 dark:bg-white/5
                               border border-black/5 dark:border-white/5
                               hover:border-blue-400/30
                               transition ">

                            <i class="ph ph-magnifying-glass text-emerald-500 text-lg"></i>

                            <span class="text-xs text-gray-700  dark:text-white">
                                Cari info penting dari log
                            </span>

                        </button>

                    </div>
                </div>
            </template>


            <!-- Messages -->
            <template x-for="(msg,index) in chatHistory" :key="index">

                <div class="flex mb-5" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">

                    <!-- AI -->
                    <template x-if="msg.role === 'ai'">

                        <div class="flex gap-3 max-w-[92%] ">

                            <div class="w-8 h-8 rounded-full
                                    ">

                                <i
                                    class="ph ph-storefront text-[20px] leading-none text-gray-600 dark:text-gray-300 !text-[22px]"></i>

                            </div>

                            <div class="px-4 py-3 rounded-2xl
                                    bg-gray-50 dark:bg-white/5
                                    border border-black/5 dark:border-white/5
                                    shadow-sm min-w-0" style="overflow-x: auto;">

                                <div class="text-[13px]
                                        leading-relaxed
                                        text-gray-800 dark:text-gray-200
                                        ai-prose" x-html="formatMessage(msg.content)">
                                </div>

                            </div>

                        </div>

                    </template>


                    <!-- USER -->
                    <template x-if="msg.role === 'user'">

                        <div class="max-w-[80%]
                                px-4 py-3
                                rounded-2xl
                                bg-blue-600 text-white
                                shadow-md">

                            <p class="text-[13px] leading-relaxed whitespace-pre-wrap" x-text="msg.content">
                            </p>

                        </div>

                    </template>

                </div>

            </template>


            <!-- Thinking -->
            <template x-if="isAiTyping">

                <div class="flex gap-3">

                    <div class="w-8 h-8 rounded-full
                            bg-gradient-to-tr from-blue-600 to-indigo-600
                            flex items-center justify-center
                            animate-pulse">

                        <i class="ph ph-sparkle text-white text-xs"></i>

                    </div>

                    <div class="px-4 py-3 rounded-2xl
                            bg-gray-50 dark:bg-white/5
                            border border-black/5 dark:border-white/5
                            flex gap-2 items-center">

                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium whitespace-nowrap">Sedang memikirkan...</span>
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
        <div class="p-4 border-t border-black/5 dark:border-white/5 mb-5">

            <div class=" 
                    bg-gray-50 dark:bg-black
                    shadow-sm mb-5">

                <div class="relative mb-5">

                    <textarea x-model="chatInput" rows="1" @keydown.enter.prevent="handleEnter" @input="resizeTextarea"
                        :disabled="isAiTyping" placeholder="Tanya asisten TIDESSA..."
                        class="w-full bg-transparent
                           py-3.5 pl-4 pr-14
                           text-[13px]
                           resize-none
                           min-h-[52px]
                           max-h-32
                           focus:outline-none
                           form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full  bg-transparent dark:bg-transparent text-black dark:text-white">
                </textarea>

                    <button @click="sendMessage()" :disabled="chatInput.trim()==='' || isAiTyping" class="absolute right-2 top-2
                           w-9 h-9 rounded-xl
                           flex items-center justify-center
                           transition-all" :class="
                      (chatInput.trim()==='' || isAiTyping)
                      ? 'bg-gray-100 dark:bg-white/5 text-gray-300'
                      : 'bg-blue-600 text-white hover:bg-blue-700'
                    ">

                        <i class="ph ph-paper-plane-tilt "></i>

                    </button>

                </div>

            </div>

        </div>
    </div>

    <style>
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
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

        /* Markdown AI Prose (Improved Spacing & Headers) */
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

        /* Responsive Table AI */
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

        .ai-prose table::-webkit-scrollbar {
            height: 4px;
        }

        .ai-prose table::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .dark .ai-prose table::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
        }
    </style>