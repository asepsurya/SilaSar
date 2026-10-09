<!-- Search Menu Input & Overlay -->
<div x-data="menuSearch()" x-init="initMenus()" @keydown.window.ctrl.k.prevent="openOverlay()" class="relative">

    <!-- Inline Search -->
    <div>
        <div class="relative">
            <input type="text" placeholder="Cari Menu..."
                class="form-input py-2.5 pl-4 pr-20 w-full text-black dark:text-white border border-gray-300 dark:border-zinc-700 rounded-lg placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10 transition-all bg-white dark:bg-zinc-900 shadow-sm hover:border-gray-400 dark:hover:border-zinc-600"
                maxlength="50" x-model="search" @input="filterMenus" @focus="filterMenus"
                @keydown.escape="showResults = false" autocomplete="off" readonly>
            <span
                class="absolute inset-y-0 right-2 flex items-center pointer-events-none text-[10px] text-gray-400 px-1.5 rounded">
                <kbd class="font-mono">Ctrl</kbd>+<kbd class="font-mono">K</kbd>
            </span>
        </div>

        <template x-if="showResults">
            <ul
                class="absolute z-50 mt-2 w-full bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-lg shadow-lg max-h-[340px] overflow-y-auto custom-scrollbar py-1">
                <template x-for="item in results" :key="item.url">
                    <li>
                        <a :href="item.url"
                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-zinc-800 text-black dark:text-white transition-colors"
                            x-text="item.name"></a>
                    </li>
                </template>
            </ul>
        </template>
    </div>

    <!-- Overlay Search Box (Ctrl+K) -->
    <div x-show="showOverlay" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" style="display: none; z-index: 9999;"
        class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4 py-8"
        @keydown.window.escape="closeOverlay()">

        <!-- Modal Container (h-auto digunakan agar tinggi menyesuaikan konten & max-h-[85vh] sebagai batas aman layar) -->
        <div
            class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-200 dark:border-zinc-800 relative flex flex-col h-auto max-h-[85vh]">

            <!-- Search Header Input -->
            <div class="px-4 py-3 border-b border-gray-100 dark:border-zinc-800 flex-none">
                <div
                    class="relative flex items-center rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-zinc-700 transition-all duration-200 focus-within:border-blue-500/50 focus-within:ring-4 focus-within:ring-blue-500/10">
                    <x-icon name="search" class="h-4 w-4 text-gray-400 ml-3 flex-none" />
                    <input x-ref="overlayInput" id="overlayInput" type="text" placeholder="Cari Menu..."
                        class="w-full border-none bg-transparent pl-2.5 pr-9 py-2.5 text-sm text-black placeholder-gray-400 focus:outline-none focus:ring-0 dark:text-white"
                        x-model="overlaySearch" @input="filterOverlayMenus" @keydown.escape="closeOverlay()" />
                    <button @click="closeOverlay()"
                        class="absolute right-2.5 flex h-6 w-6 items-center justify-center rounded-md text-sm leading-none text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">&times;</button>
                </div>
            </div>

            <!-- Search Results Body -->
            <div class="flex-1 min-h-0 overflow-y-auto p-2 custom-scrollbar">
                <template x-if="overlaySearch.length > 0">
                    <div class="space-y-1">
                        <template x-for="item in overlayResults" :key="item.url">
                            <a :href="item.url"
                                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 transition-all hover:bg-blue-50 hover:text-blue-600 dark:text-gray-300 dark:hover:bg-white/5 dark:hover:text-white group"
                                @click="closeOverlay()">
                                <div
                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 group-hover:bg-blue-100 dark:bg-white/5 dark:group-hover:bg-blue-500/20 transition-colors">
                                    <x-icon name="layer" class="h-4 w-4" />
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium" x-text="item.name"></span>
                                    <span class="text-[11px] text-gray-400 group-hover:text-blue-400"
                                        x-text="item.url"></span>
                                </div>
                            </a>
                        </template>
                    </div>
                </template>

                <!-- Transaction Results -->
                <template x-if="transactions.length > 0">
                    <div class="mt-2 pt-2 border-t border-gray-100 dark:border-zinc-800">
                        <p class="px-3 pb-1.5 text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                            Transaksi
                        </p>
                        <template x-for="tx in transactions" :key="tx.id">
                            <a :href="tx.url"
                                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-gray-700 transition-all hover:bg-emerald-50 hover:text-emerald-700 dark:text-gray-300 dark:hover:bg-white/5 dark:hover:text-white group"
                                @click="closeOverlay()">
                                <div
                                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:group-hover:bg-emerald-500/20 transition-colors">
                                    <x-icon name="document" class="h-4 w-4" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium truncate" x-text="tx.kode_transaksi"></span>
                                        <span class="flex-none text-[10px] font-bold px-1.5 py-0.5 rounded"
                                            :class="tx.status_bayar === 'Sudah Bayar' ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400'"
                                            x-text="tx.status_bayar"></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[11px] text-gray-400 group-hover:text-emerald-500">
                                        <span class="truncate" x-text="tx.nama_mitra"></span>
                                        <span x-show="tx.tanggal">&middot;</span>
                                        <span x-text="tx.tanggal"></span>
                                    </div>
                                </div>
                                <span class="flex-none text-xs font-bold text-gray-700 dark:text-gray-200 tabular-nums"
                                    x-text="'Rp ' + Number(tx.total || 0).toLocaleString('id-ID')"></span>
                            </a>
                        </template>
                    </div>
                </template>

                <!-- Loading Transactions Skeleton -->
                <template x-if="isLoadingTransactions">
                    <div class="mt-2 pt-2 border-t border-gray-100 dark:border-zinc-800">
                        <div class="space-y-2 animate-pulse px-1">
                            <template x-for="i in 3">
                                <div class="flex gap-3 items-center">
                                    <div class="h-9 w-9 rounded-lg bg-gray-100 dark:bg-white/5"></div>
                                    <div class="flex-1 space-y-1.5">
                                        <div class="h-2.5 bg-gray-100 dark:bg-white/5 rounded w-1/3"></div>
                                        <div class="h-2 bg-gray-100 dark:bg-white/5 rounded w-1/2"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <template x-if="overlaySearch.length > 0 && overlayResults.length === 0 && transactions.length === 0 && !isLoadingTransactions">
                    <div class="flex h-40 flex-col items-center justify-center py-6 text-center">
                        <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-full mb-3">
                            <x-icon name="search" class="h-8 w-8 text-gray-300" />
                        </div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Tidak ditemukan</p>
                        <p class="text-xs text-gray-400 mt-1">Coba kata kunci lain</p>
                    </div>
                </template>

                <template x-if="overlaySearch.length === 0">
                    <div class="flex h-40 flex-col items-center justify-center text-center">
                        <div class="bg-gray-50 dark:bg-white/5 p-4 rounded-full mb-3">
                            <x-icon name="keyboard" class="h-8 w-8 text-gray-300" />
                        </div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-widest">Ketik untuk mencari...
                        </p>
                    </div>
                </template>
            </div>

            <!-- Search Footer -->
            <div
                class="flex items-center justify-between border-t border-gray-100 bg-gray-50/50 px-4 py-3 dark:border-zinc-800 dark:bg-black/20 flex-none">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5">
                        <kbd
                            class="min-w-[20px] px-1.5 py-0.5 text-[10px] font-sans font-semibold text-gray-500 border border-gray-300 rounded bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-400">ESC</kbd>
                        <span class="text-[10px] text-gray-400 uppercase font-medium">Tutup</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <kbd
                            class="min-w-[20px] px-1.5 py-0.5 text-[10px] font-sans font-semibold text-gray-500 border border-gray-300 rounded bg-white dark:bg-zinc-800 dark:border-zinc-700 dark:text-gray-400">↵</kbd>
                        <span class="text-[10px] text-gray-400 uppercase font-medium">Pilih</span>
                    </div>
                </div>
                <div class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">
                    Quick Search
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function menuSearch() {
        return {
            search: '',
            showResults: false,
            results: [],
            overlaySearch: '',
            showOverlay: false,
            overlayResults: [],
            transactions: [],
            isLoadingTransactions: false,
            txDebounceTimer: null,
            txRequestSeq: 0,
            menus: [],

            initMenus() {
                let menuEls = document.querySelectorAll('#menu a');
                let tempMenus = [];
                menuEls.forEach(a => {
                    let name = a.textContent.trim();
                    let url = a.getAttribute('href');
                    if (name && url && url !== 'javascript:;' && url !== 'javaScript:;') {
                        tempMenus.push({ name, url });
                    }
                });
                this.menus = tempMenus;
            },

            filterMenus() {
                if (this.search.length > 0) {
                    this.results = this.menus.filter(m => m.name.toLowerCase().includes(this.search.toLowerCase()));
                    this.showResults = this.results.length > 0;
                } else {
                    this.showResults = false;
                }
            },

            openOverlay() {
                this.showOverlay = true;
                this.overlaySearch = '';
                this.overlayResults = [];
                this.transactions = [];
                this.isLoadingTransactions = false;
                this.$nextTick(() => {
                    this.$refs.overlayInput.focus();
                });
            },

            closeOverlay() {
                this.showOverlay = false;
                this.overlaySearch = '';
                this.overlayResults = [];
                this.transactions = [];
                this.isLoadingTransactions = false;
                if (this.txDebounceTimer) {
                    clearTimeout(this.txDebounceTimer);
                    this.txDebounceTimer = null;
                }
                this.txRequestSeq++;
            },

            filterOverlayMenus() {
                if (this.overlaySearch.length > 0) {
                    this.overlayResults = this.menus.filter(m => m.name.toLowerCase().includes(this.overlaySearch.toLowerCase()));
                } else {
                    this.overlayResults = [];
                }
                this.fetchTransactions();
            },

            fetchTransactions() {
                if (this.txDebounceTimer) {
                    clearTimeout(this.txDebounceTimer);
                }
                const q = this.overlaySearch.trim();
                if (q.length < 1) {
                    this.transactions = [];
                    this.isLoadingTransactions = false;
                    return;
                }
                this.txDebounceTimer = setTimeout(() => {
                    const seq = ++this.txRequestSeq;
                    this.isLoadingTransactions = true;
                    fetch(`/api/transaksi/search?q=${encodeURIComponent(q)}`)
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal mengambil data transaksi.');
                            return res.json();
                        })
                        .then(res => {
                            if (seq === this.txRequestSeq && res.status === 'success') {
                                this.transactions = res.data;
                            }
                        })
                        .catch(() => {})
                        .finally(() => {
                            if (seq === this.txRequestSeq) {
                                this.isLoadingTransactions = false;
                            }
                        });
                }, 300);
            }
        }
    }
</script>