document.addEventListener('alpine:init', () => {
    Alpine.data('rightSidebarData', () => ({
        activeTab: 'logs',

        // Logs State
        logs: [],
        isLoadingLogs: false,
        logError: null,
        logFilter: 'semua',
        hasFetchedLogs: false,

        // AI Chat State
        chatHistory: [],
        chatThreads: [], // [{id, title, messages, date}]
        activeThreadId: null,
        chatInput: '',
        isAiTyping: false,
        showHistory: false,
        appSummary: null,
        hasFetchedSummary: false,

        init() {
            // Load threads from localStorage
            const savedThreads = localStorage.getItem('silasar_ai_threads');
            if (savedThreads) {
                try {
                    this.chatThreads = JSON.parse(savedThreads);
                    // Load last active thread or first one
                    const lastId = localStorage.getItem('silasar_active_thread');
                    if (lastId && this.chatThreads.find(t => t.id == lastId)) {
                        this.switchThread(lastId);
                    } else if (this.chatThreads.length > 0) {
                        this.switchThread(this.chatThreads[0].id);
                    }
                } catch (e) {
                    this.chatThreads = [];
                }
            }

            // If no threads, create one
            if (this.chatThreads.length === 0) {
                this.createNewThread();
            }

            // Fetch logs immediately on init so they are ready
            this.fetchLogs();
            this.fetchAppSummary();

            // Watch for rightSidebar visibility if it uses Alpine store
            this.$watch('$store.app.rightsidebar', (value) => {
                if (value) {
                    this.fetchLogs(); // Refresh when opened
                }
            });

            this.$watch('activeTab', (value) => {
                if (value === 'ai') {
                    this.$nextTick(() => this.scrollToBottom());
                }
            });

            // Simple markdown parser helper using marked.js if available
            window.marked_parser = function (text) {
                if (!text) return '';
                if (typeof marked !== 'undefined') {
                    // Configure marked for better symbol support
                    marked.setOptions({
                        gfm: true,
                        breaks: true,
                        smartypants: true,
                        headerIds: false,
                        mangle: false
                    });
                    return marked.parse(text);
                }
                // Fallback if marked not loaded
                return text.replace(/\n/g, '<br>');
            };
        },

        // --- Logs Logic ---
        fetchLogs() {
            this.isLoadingLogs = true;
            this.logError = null;
            this.logs = [];

            fetch(`/api/logs/activity?filter=${this.logFilter}`)
                .then(res => {
                    if (!res.ok) throw new Error('Gagal mengambil data log.');
                    return res.json();
                })
                .then(res => {
                    if (res.status === 'success') {
                        this.logs = res.data;
                        this.hasFetchedLogs = true;
                    }
                })
                .catch(err => {
                    this.logError = err.message || 'Terjadi kesalahan sistem.';
                })
                .finally(() => {
                    this.isLoadingLogs = false;
                });
        },

        fetchAppSummary() {
            fetch(`/api/app-summary`)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        this.appSummary = res.data;
                        this.hasFetchedSummary = true;
                    }
                })
                .catch(err => console.error('Gagal mengambil summary:', err));
        },

        createNewThread() {
            const newId = Date.now();
            const newThread = {
                id: newId,
                title: 'Percakapan Baru',
                messages: [],
                date: new Date().toLocaleString('id-ID')
            };
            this.chatThreads.unshift(newThread);
            this.switchThread(newId);
            this.showHistory = false;
        },

        switchThread(id) {
            const thread = this.chatThreads.find(t => t.id == id);
            if (thread) {
                this.activeThreadId = id;
                this.chatHistory = thread.messages;
                this.showHistory = false; // Tutup panel setelah pilih
                localStorage.setItem('silasar_active_thread', id);
                this.$nextTick(() => {
                    this.scrollToBottom();
                    this.saveThreads();
                });
            }
        },

        deleteThread(id) {
            if (!confirm('Hapus percakapan ini?')) return;
            this.chatThreads = this.chatThreads.filter(t => t.id != id);
            if (this.activeThreadId == id) {
                if (this.chatThreads.length > 0) {
                    this.switchThread(this.chatThreads[0].id);
                } else {
                    this.createNewThread();
                }
            }
            this.saveThreads();
        },

        saveThreads() {
            // Update current thread messages
            const thread = this.chatThreads.find(t => t.id == this.activeThreadId);
            if (thread) {
                thread.messages = this.chatHistory;
                // Auto title from first user message
                if ((thread.title === 'Percakapan Baru' || !thread.title) && this.chatHistory.length > 0) {
                    const firstUserMsg = this.chatHistory.find(m => m.role === 'user');
                    if (firstUserMsg) {
                        let title = firstUserMsg.content.substring(0, 25);
                        if (firstUserMsg.content.length > 25) title += '...';
                        thread.title = title;
                    }
                }
            }
            localStorage.setItem('silasar_ai_threads', JSON.stringify(this.chatThreads));
        },


        // --- AI Chat Logic ---
        resizeTextarea(e) {
            e.target.style.height = 'auto';
            e.target.style.height = (e.target.scrollHeight) + 'px';
        },

        handleEnter(e) {
            if (!e.shiftKey) {
                this.sendMessage();
            }
        },

        clearChat() {
            // Replaced by deleteThread
            this.deleteThread(this.activeThreadId);
        },

        sendQuickPrompt(prompt) {
            this.chatInput = prompt;
            this.sendMessage();
        },

        formatMessage(content) {
            if (!content) return '';
            return window.marked_parser(content);
        },

        scrollToBottom() {
            const container = document.getElementById('ai-chat-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },

        async sendMessage() {
            const text = this.chatInput.trim();
            if (!text || this.isAiTyping) return;

            // Add User message
            this.chatHistory.push({ role: 'user', content: text });
            this.chatInput = '';

            // Reset textarea height
            const ta = document.querySelector('#ai-chat-container + div textarea');
            if (ta) {
                ta.style.height = 'auto';
            }

            this.isAiTyping = true;
            this.$nextTick(() => {
                this.scrollToBottom();
                this.saveThreads();
            });

            // Add placeholder for AI response
            this.chatHistory.push({ role: 'ai', content: '' });
            const aiMsgIndex = this.chatHistory.length - 1;

            try {
                const promptContext = this.buildPromptContext(text);

                const response = await fetch(window.OLLAMA_API_URL || 'https://myollama.scrollwebid.com/api/generate', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        model: 'gemini-3-flash-preview',
                        prompt: promptContext,
                        stream: true,
                        options: { temperature: 0.8, top_p: 0.95 }
                    })
                });

                if (!response.ok) throw new Error('Network error');

                const reader = response.body.getReader();
                const decoder = new TextDecoder('utf-8');

                while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;

                    const chunk = decoder.decode(value, { stream: true });
                    const lines = chunk.split('\n').filter(line => line.trim() !== '');

                    for (const line of lines) {
                        try {
                            const data = JSON.parse(line);
                            if (data.response) {
                                this.chatHistory[aiMsgIndex].content += data.response;
                                this.$nextTick(() => this.scrollToBottom());
                            }
                        } catch (e) {
                            console.error('Error parsing stream chunk', e);
                        }
                    }
                }
            } catch (error) {
                console.error("AI Chat Error:", error);
                this.chatHistory[aiMsgIndex].content = "Maaf, sepertinya saya sedang offline atau terjadi masalah koneksi ke server AI.";
            } finally {
                this.isAiTyping = false;
                this.saveThreads();
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        buildPromptContext(userText) {
            // Deskripsi aplikasi untuk konteks AI
            const appDescription = `
            Anda adalah asisten AI bernama "TIDESSA" untuk aplikasi "SilaSar" (Sistem Layanan Administrasi & Stok Barang).
            Fitur Utama Aplikasi:
            1. Dashboard Admin & Peta Pemasaran: Visualisasi data dan peta distribusi.
            2. Manajemen Mitra & People: Kelola data mitra bisnis, karyawan (IKM), dan keaktifan pengguna.
            3. Manajemen Produk: Kelola kategori, satuan, manajemen stok, dan log stok barang.
            4. Transaksi & Retur: Kelola barang masuk, barang keluar, nota manual, dan retur transaksi.
            5. Keuangan: Monitoring history harian, catatan harian, neraca, laba rugi, dan data rekening.
            6. Canvassing Toko: Pemetaan toko, rute pemasaran, dan status rute canvassing.
            7. Laporan Terpadu: Cetak laporan penjualan, stok, neraca, dan keuangan secara otomatis.
            Tugas Anda adalah membantu pengguna memahami data aktivitas mereka dan memberikan insight terkait operasional toko/gudang berdasarkan seluruh menu tersebut.
            `;

            const t = userText.toLowerCase();
            let logContext = '';
            let dataContext = '';

            // Context Data (Mitra, Toko, Stok)
            if (this.appSummary) {
                const s = this.appSummary;
                dataContext = `
                Konteks Data Saat Ini:
                - Total Mitra: ${s.mitra.total}
                - Mitra per Kota: ${s.mitra.by_kota.map(k => `${k.id_kota} (${k.total})`).join(', ')}
                - Summary Canvassing (Toko): Total ${s.canvassing.total_toko}, Sudah dicek ${s.canvassing.checked_this_month}, Belum dicek ${s.canvassing.unchecked_this_month}.
                - Daftar Toko Belum Dicek: ${s.canvassing.toko_belum_cek.join(', ')}
                - Toko di Bandung: ${s.canvassing.toko_bandung.join(', ')}
                - Total Produk: ${s.produk.total}
                - Stok Menipis: ${s.produk.stok_menipis.map(p => `${p.nama_produk} (Sisa ${p.stok})`).join(', ')}
                `;
            }

            if (t.includes('aktivitas') || t.includes('log') || t.includes('hari ini')) {
                const recentLogs = this.logs.slice(0, 5).map(l => `- ${l.description} oleh ${l.causer_name} (${l.created_at})`).join('\n');
                if (recentLogs) {
                    logContext = `\nBerikut 5 aktivitas log terakhir di sistem:\n${recentLogs}\n`;
                }
            }

            return `${appDescription}\n${dataContext}\n${logContext}\nPertanyaan user: ${userText}`;
        }
    }));
});
