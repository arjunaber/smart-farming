@extends('layouts.app')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/4.3.0/marked.min.js"></script>

    <style>
        /* Animasi bouncing untuk loading agar lebih smooth */
        @keyframes bounce-custom {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }

        .dot-loading {
            animation: bounce-custom 0.6s infinite;
        }

        .bot-content-area {
            color: #334155 !important;
            line-height: 1.7;
            font-size: 0.9rem;
        }
        .bot-content-area hr {
            display: none;
        }

        .dark .bot-content-area {
            color: #e2e8f0 !important;
        }

        .bot-content-area strong {
            font-weight: 700;
            color: #059669;
        }

        .bot-content-area p { margin-bottom: 0.75rem; }
        .bot-content-area p:last-child { margin-bottom: 0; }

        .bot-content-area ul, .bot-content-area ol {
            margin: 0.75rem 0;
            padding-left: 1.5rem;
        }
        .bot-content-area ul { list-style-type: disc; }
        .bot-content-area ul li { margin-bottom: 0.4rem; padding-left: 0.25rem; }
        .bot-content-area ul li::marker { color: #059669; }
        .bot-content-area ol { list-style-type: decimal; }
        .bot-content-area ol li { margin-bottom: 0.4rem; padding-left: 0.25rem; }
        .bot-content-area ol li::marker { color: #059669; font-weight: 700; }

        .bot-content-area table {
            width: 100%;
            border-collapse: collapse;
            margin: 0.75rem 0;
            font-size: 0.85rem;
            border-radius: 10px;
            overflow: hidden;
        }

        .bot-content-area th, .bot-content-area td {
            padding: 0.6rem 0.75rem;
            text-align: left;
            border: 1px solid #e2e8f0;
        }
        .dark .bot-content-area th, .dark .bot-content-area td { border-color: #334155; }
        .bot-content-area th { background-color: #059669; color: #fff; font-weight: 600; }
        .bot-content-area tr:nth-child(even) { background-color: #f1f5f9; }
        .dark .bot-content-area tr:nth-child(even) { background-color: #1e293b; }

        .bot-content-area code {
            background: #f1f5f9;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-size: 0.8rem;
            color: #0f172a;
        }
        .dark .bot-content-area code {
            background: #1e293b;
            color: #e2e8f0;
        }

        .bot-content-area pre {
            background: #0f172a;
            color: #e2e8f0;
            padding: 1rem;
            border-radius: 10px;
            overflow-x: auto;
            margin: 0.75rem 0;
        }
        .bot-content-area pre code {
            background: none;
            padding: 0;
            color: inherit;
        }

        .message-bubble { max-width: 88%; }

        .user-bubble {
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
            border-radius: 18px 18px 4px 18px;
        }

        .bot-bubble {
            background: #ffffff;
            border-radius: 18px 18px 18px 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .dark .bot-bubble {
            background: #1e293b;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .chat-input { transition: box-shadow 0.2s, background-color 0.2s; }
        .chat-input:focus-within { background-color: #f8fafc; }
        .dark .chat-input:focus-within { background-color: #1e293b; }

        .suggestion-chip { background: transparent; }
        .suggestion-chip:hover { background: #f1f5f9; }
        .dark .suggestion-chip:hover { background: #1e293b; }
        
        /* Custom Scrollbar untuk Sidebar History */
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .dark .sidebar-scroll::-webkit-scrollbar-thumb { background: #475569; }
    </style>

    <div x-data="chatbotComponent" class="h-[calc(100vh-120px)] max-w-6xl mx-auto flex bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden relative">

        {{-- LAYOUT KIRI: SIDEBAR HISTORY (Seperti ChatGPT) --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-slate-900/50 z-40 md:hidden" style="display: none;"></div>
        
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="absolute md:static inset-y-0 left-0 z-50 w-72 bg-slate-50 dark:bg-slate-950/50 border-r border-slate-200 dark:border-slate-800 flex flex-col transition-transform duration-300 md:translate-x-0">
            
            <div class="p-4">
                <button @click="startNewChat()" class="w-full flex items-center gap-3 px-4 py-3 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 transition-colors shadow-sm">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Obrolan Baru
                </button>
            </div>

            <div class="flex-1 overflow-y-auto sidebar-scroll px-3 pb-4">
                <div class="space-y-6">
                    <div>
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Hari Ini</p>
                        <ul class="space-y-1">
                            <template x-for="history in chatHistories.today" :key="history.id">
                                <li>
                                    <button @click="loadHistory(history.id)" :class="currentSessionId === history.id ? 'bg-slate-200 dark:bg-slate-800 text-green-700 dark:text-green-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200/50 dark:hover:bg-slate-800/50'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-left transition-colors group">
                                        <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                        <span class="truncate flex-1" x-text="history.title"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>

                    <div>
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">7 Hari Terakhir</p>
                        <ul class="space-y-1">
                            <template x-for="history in chatHistories.pastWeek" :key="history.id">
                                <li>
                                    <button @click="loadHistory(history.id)" :class="currentSessionId === history.id ? 'bg-slate-200 dark:bg-slate-800 text-green-700 dark:text-green-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-200/50 dark:hover:bg-slate-800/50'" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-left transition-colors group">
                                        <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                        <span class="truncate flex-1" x-text="history.title"></span>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- LAYOUT KANAN: MAIN CHAT AREA --}}
        <div class="flex-1 flex flex-col h-full bg-white dark:bg-slate-900 relative">
            
            {{-- Header Chat (dengan tombol hamburger untuk mobile) --}}
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-700 rounded-lg flex items-center justify-center text-white shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-800 dark:text-white">AGA Assistant</h2>
                        <p class="text-[10px] text-green-600 font-semibold tracking-wider" x-text="currentSessionId ? 'History Mode' : 'New Chat'"></p>
                    </div>
                </div>
            </div>

            {{-- EMPTY STATE (Hanya tampil saat New Chat) --}}
            <template x-if="!hasStarted">
                <div class="flex-1 flex flex-col items-center justify-center px-4 overflow-y-auto">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-700 rounded-2xl flex items-center justify-center text-white shadow-lg mb-5">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">Ada yang bisa dibantu?</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 text-center max-w-md">Saya adalah AI yang terhubung dengan data IoT kebun Anda. Tanyakan soal irigasi, hama, atau kondisi cuaca.</p>

                    <div class="flex gap-2 mt-4 flex-wrap justify-center max-w-2xl">
                        <template x-for="suggestion in suggestions" :key="suggestion">
                            <button @click="quickAsk(suggestion)"
                                class="suggestion-chip text-sm text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-4 py-2.5 rounded-xl transition-colors shadow-sm">
                                <span x-text="suggestion"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </template>

            {{-- CHAT STATE (Tampil saat ada interaksi/history) --}}
            <template x-if="hasStarted">
                <div id="chatScrollArea" class="flex-1 overflow-y-auto space-y-6 p-6 scroll-smooth">
                    <template x-for="(msg, index) in messages" :key="index">
                        <div class="flex items-start gap-3" :class="msg.role === 'user' ? 'justify-end' : ''">

                            <template x-if="msg.role === 'bot'">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white flex-shrink-0 shadow-sm mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                            </template>

                            <div :class="msg.role === 'user' ? 'user-bubble' : 'bot-bubble'" class="message-bubble px-5 py-4">
                                <div class="bot-content-area" x-init="injectContent($el, msg)"></div>
                                <span class="text-[10px] mt-2 block opacity-50 font-medium" :class="msg.role === 'user' ? 'text-white/70 text-right' : 'text-slate-500'" x-text="msg.time"></span>
                            </div>

                            <template x-if="msg.role === 'user'">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-300 to-slate-400 dark:from-slate-600 dark:to-slate-700 flex items-center justify-center text-slate-800 dark:text-white flex-shrink-0 font-bold text-xs shadow-sm mt-1">
                                    SA
                                </div>
                            </template>
                        </div>
                    </template>

                    <div x-show="isTyping" class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white flex-shrink-0 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <div class="bot-bubble px-5 py-4 flex items-center gap-1.5">
                            <div class="w-2 h-2 bg-green-500 rounded-full dot-loading" style="animation-delay: 0s"></div>
                            <div class="w-2 h-2 bg-green-500 rounded-full dot-loading" style="animation-delay: 0.2s"></div>
                            <div class="w-2 h-2 bg-green-500 rounded-full dot-loading" style="animation-delay: 0.4s"></div>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Input Area (Sticky di bawah) --}}
            <div class="p-4 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
                <div class="relative flex items-center bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm chat-input max-w-4xl mx-auto">
                    <input type="text" x-model="newMessage" @keydown.enter="sendChat()" placeholder="Tanya tentang irigasi, hama, atau kondisi lahan..."
                        class="w-full bg-transparent text-slate-900 dark:text-white rounded-2xl py-4 pl-5 pr-14 focus:outline-none text-sm">
                    <button @click="sendChat()" :disabled="!newMessage.trim() || isTyping"
                        class="absolute right-2 bg-gradient-to-br from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 disabled:from-slate-300 disabled:to-slate-600 disabled:text-slate-400 text-white p-2.5 rounded-xl transition-all">
                        <svg class="w-5 h-5 transform -rotate-45 mb-0.5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatbotComponent', () => ({
                sidebarOpen: false, // Untuk toggle di mobile
                newMessage: '',
                isTyping: false,
                hasStarted: false,
                currentSessionId: null, // Melacak history chat yang sedang dibuka
                messages: [],
                
                suggestions: [
                    'Apakah lahan A butuh disiram?',
                    'Bagaimana prediksi cuaca esok?',
                    'Kapan jadwal pupuk urea?',
                    'Cara atasi wereng coklat'
                ],

                // DUMMY DATA HISTORY (Nanti BE yang akan Fetch data ini dari database)
                chatHistories: {
                    today: [
                        { id: 101, title: 'Kondisi Kelembapan Lahan A' },
                        { id: 102, title: 'Rekomendasi Pupuk Padi' }
                    ],
                    pastWeek: [
                        { id: 103, title: 'Diagnosis Daun Menguning' },
                        { id: 104, title: 'Prediksi Hujan Lebat' },
                        { id: 105, title: 'Jadwal Panen Lahan C' }
                    ]
                },

                // DUMMY DETAIL HISTORY (Nanti Fetch axios/fetch dari BE berdasarkan ID)
                mockDatabase: {
                    101: [
                        { role: 'user', text: 'Apakah lahan A butuh disiram?', time: '10:00' },
                        { role: 'bot', text: 'Kelembapan tanah lahan A saat ini **42%**. Mengingat cuaca cerah, saya sarankan untuk menyiram sore ini.', time: '10:01' }
                    ],
                    103: [
                        { role: 'user', text: 'Daun padi saya menguning, kenapa ya?', time: 'Kemarin' },
                        { role: 'bot', text: 'Berdasarkan data IoT, lahan Anda kekurangan nutrisi Nitrogen. Segera jadwalkan pemupukan Urea.', time: 'Kemarin' }
                    ]
                },

                // Fungsi untuk mulai obrolan kosong baru
                startNewChat() {
                    this.hasStarted = false;
                    this.messages = [];
                    this.currentSessionId = null;
                    this.sidebarOpen = false; // tutup sidebar di hp
                },

                // Fungsi untuk load history lama
                loadHistory(id) {
                    this.currentSessionId = id;
                    this.hasStarted = true;
                    this.sidebarOpen = false; // tutup sidebar di hp
                    
                    // Simulasikan Fetch API loading
                    this.messages = []; 
                    
                    // Nanti BE ganti baris ini dengan Axios Fetch berdasarkan ID
                    const dataHistory = this.mockDatabase[id] || [
                        { role: 'bot', text: 'Maaf, data history tidak ditemukan (Dummy FE).', time: 'Sekarang' }
                    ];

                    // Masukkan data history ke layar
                    this.messages = [...dataHistory];
                    this.$nextTick(() => this.scrollToBottom());
                },

                injectContent(el, msg) {
                    if (msg.role === 'bot') {
                        if (msg.typing) {
                            const fullText = msg.text;
                            let i = 0;
                            const speed = 5;
                            const step = 5;
                            const self = this;

                            const type = () => {
                                if (i < fullText.length) {
                                    i = Math.min(i + step, fullText.length);
                                    const partialText = fullText.substring(0, i);
                                    el.innerHTML = (typeof marked !== 'undefined') ?
                                        marked.parse(partialText) : partialText;
                                    self.scrollToBottom();
                                    if (i < fullText.length) setTimeout(type, speed);
                                    else msg.typing = false;
                                }
                            };
                            type();
                        } else {
                            const htmlContent = (typeof marked !== 'undefined') ?
                                marked.parse(msg.text) : msg.text;
                            el.innerHTML = htmlContent;
                        }
                    } else {
                        el.textContent = msg.text;
                    }
                },

                scrollToBottom() {
                    const container = document.getElementById('chatScrollArea');
                    if (container) {
                        setTimeout(() => {
                            container.scrollTop = container.scrollHeight;
                        }, 100);
                    }
                },

                quickAsk(text) {
                    this.newMessage = text;
                    this.sendChat();
                },

                async sendChat() {
                    if (!this.newMessage.trim() || this.isTyping) return;

                    const userText = this.newMessage;
                    this.hasStarted = true;
                    
                    // Jika ini chat pertama di layar kosong, Anda mungkin ingin create Session Baru ke BE
                    // if(!this.currentSessionId) { ... create session ... }

                    this.$nextTick(() => {
                        this.messages.push({
                            role: 'user',
                            text: userText,
                            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });

                        this.newMessage = '';
                        this.isTyping = true;
                        this.scrollToBottom();

                        setTimeout(() => this.fetchResponse(userText), 100);
                    });
                },

                async fetchResponse(userText) {
                    try {
                        const params = new URLSearchParams();
                        params.append('prompt', userText);

                        const response = await fetch(
                            'https://sq74g607-8000.asse.devtunnels.ms/recommend', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: params
                            });

                        const data = await response.json();

                        this.isTyping = false;

                        this.messages.push({
                            role: 'bot',
                            text: data.recommendationn || data.recommendation || 'Maaf, tidak ada respon.',
                            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                            typing: true
                        });

                        this.scrollToBottom();
                    } catch (e) {
                        this.isTyping = false;
                        this.messages.push({
                            role: 'bot',
                            text: '⚠️ Gagal terhubung ke Server AI.',
                            time: 'Error'
                        });
                        this.scrollToBottom();
                    }
                }
            }));
        });
    </script>
@endsection