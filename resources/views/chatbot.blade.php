@extends('layouts.app')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/4.3.0/marked.min.js"></script>

    <style>

        /* Animasi bouncing untuk loading agar lebih smooth */
        @keyframes bounce-custom {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-4px);
            }
        }

        .dot-loading {
            animation: bounce-custom 0.6s infinite;
        }

        .bot-content-area {
            color: #ffffff !important;
            line-height: 1.6;
            font-size: 0.875rem;
        }

        .dark .bot-content-area {
            color: #ffffff !important;
        }

        .bot-content-area strong {
            font-weight: bold;
            color: #059669;
        }
    </style>

    <div x-data="chatbotComponent"
        class="h-[calc(100vh-120px)] max-w-4xl mx-auto flex flex-col bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden">

        <div class="px-8 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center bg-white dark:bg-slate-900">
            <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center text-white mr-4 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    </path>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">AGA Assistant</h2>
                <p class="text-[10px] text-green-600 font-bold uppercase tracking-widest">Active Connection</p>
            </div>
        </div>

        <div id="chatScrollArea" class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50 dark:bg-slate-950/50">
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex items-start gap-3" :class="msg.role === 'user' ? 'justify-end' : ''">

                    <div x-show="msg.role === 'bot'"
                        class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </svg>
                    </div>

                    <div :class="msg.role === 'user' ? 'bg-green-600 text-white rounded-tr-none' :
                        'bg-white dark:bg-slate-800 rounded-tl-none border border-slate-200 dark:border-slate-700'"
                        class="p-4 rounded-2xl shadow-sm max-w-[85%]">

                        <div class="bot-content-area" x-init="injectContent($el, msg)"></div>

                        <span class="text-[9px] mt-2 block opacity-70 font-bold" x-text="msg.time"></span>
                    </div>

                    <div x-show="msg.role === 'user'"
                        class="w-8 h-8 rounded-full bg-slate-300 flex items-center justify-center text-slate-800 flex-shrink-0 font-bold text-xs">
                        SA
                    </div>
                </div>
            </template>

            <div x-show="isTyping" class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </div>
                <div
                    class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 h-12">
                    <div class="w-2 h-2 bg-green-500 rounded-full dot-loading" style="animation-delay: 0s"></div>
                    <div class="w-2 h-2 bg-green-500 rounded-full dot-loading" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-green-500 rounded-full dot-loading" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>

        <div class="p-4 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
            <div class="relative flex items-center">
                <input type="text" x-model="newMessage" @keydown.enter="sendChat()" placeholder="Tulis pesan..."
                    class="w-full bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl py-3 pl-4 pr-12 focus:outline-none border-none shadow-inner">
                <button @click="sendChat()" :disabled="!newMessage.trim() || isTyping"
                    class="absolute right-2 bg-green-600 hover:bg-green-700 disabled:bg-slate-400 text-white p-2 rounded-lg transition-all">
                    <svg class="w-5 h-5 transform -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatbotComponent', () => ({
                newMessage: '',
                isTyping: false,
                messages: [{
                    role: 'bot',
                    text: 'Halo! Saya **AGA**. Ada yang bisa saya bantu hari ini?',
                    time: new Date().toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    })
                }],

                injectContent(el, msg) {
                    if (msg.role === 'bot') {
                        const htmlContent = (typeof marked !== 'undefined') ?
                            marked.parse(msg.text) :
                            msg.text;
                        el.innerHTML = htmlContent;
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

                async sendChat() {
                    if (!this.newMessage.trim() || this.isTyping) return;

                    const userText = this.newMessage;
                    this.messages.push({
                        role: 'user',
                        text: userText,
                        time: new Date().toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        })
                    });

                    this.newMessage = '';
                    this.isTyping = true; // Loading muncul
                    this.scrollToBottom();

                    try {
                        const params = new URLSearchParams();
                        params.append('prompt', userText);

                        const response = await fetch(
                            'https://70sj7zdm-8000.asse.devtunnels.ms/recommend', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: params
                            });

                        const data = await response.json();

                        // Tambahkan pesan bot
                        this.messages.push({
                            role: 'bot',
                            text: data.recommendationn || data.recommendation ||
                                'Maaf, tidak ada respon.',
                            time: new Date().toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            })
                        });
                    } catch (e) {
                        this.messages.push({
                            role: 'bot',
                            text: '⚠️ Gagal terhubung.',
                            time: 'Error'
                        });
                    } finally {
                        setTimeout(() => {
                            this.isTyping =
                                false; 
                            this.scrollToBottom();
                        }, 300);
                    }
                }
            }));
        });
    </script>
@endsection
