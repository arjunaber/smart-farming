@extends('layouts.app')

@section('content')
<div x-data="chatbotComponent" class="h-[calc(100vh-120px)] max-w-4xl mx-auto flex flex-col bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
    
    <!-- Header Chat -->
    <div class="px-8 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-white dark:bg-slate-900 z-10 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="relative">
                <div class="w-12 h-12 bg-gradient-to-tr from-green-500 to-green-600 rounded-2xl flex items-center justify-center text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white dark:border-slate-900 rounded-full"></div>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800 dark:text-white">AGA Assistant</h2>
                <p class="text-xs text-green-600 dark:text-green-400 font-bold">Powered by LLM</p>
            </div>
        </div>
    </div>

    <!-- Chat Area -->
    <div id="chatScrollArea" class="flex-1 overflow-y-auto p-6 md:p-8 space-y-6 bg-slate-50 dark:bg-slate-950/50 scroll-smooth">
        <template x-for="(msg, index) in messages" :key="index">
            <div class="flex items-start gap-4" :class="msg.role === 'user' ? 'justify-end' : ''">
                <!-- Bot Avatar -->
                <div x-show="msg.role === 'bot'" class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 text-white mt-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <!-- Bubble -->
                <div :class="msg.role === 'user' ? 'bg-green-600 text-white rounded-tr-none' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-tl-none border border-slate-100 dark:border-slate-700'" class="p-4 rounded-2xl shadow-sm max-w-[85%] md:max-w-[75%]">
                    <p class="text-sm leading-relaxed" x-text="msg.text"></p>
                    <span :class="msg.role === 'user' ? 'text-green-200 text-right' : 'text-slate-400'" class="text-[10px] mt-2 block" x-text="msg.time"></span>
                </div>
                <!-- User Avatar -->
                <div x-show="msg.role === 'user'" class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center flex-shrink-0 text-slate-500 dark:text-slate-300 mt-1 font-bold text-xs">
                    SA
                </div>
            </div>
        </template>

        <!-- Typing Indicator -->
        <div x-show="isTyping" style="display: none;" class="flex items-start gap-4">
            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 text-white mt-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 dark:border-slate-700 flex items-center gap-1.5 h-12">
                <div class="w-2 h-2 bg-slate-300 dark:bg-slate-600 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                <div class="w-2 h-2 bg-slate-300 dark:bg-slate-600 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                <div class="w-2 h-2 bg-slate-300 dark:bg-slate-600 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
            </div>
        </div>
    </div>

    <!-- Input Area -->
    <div class="p-6 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 relative z-10">
        <div class="relative flex items-center">
            <input type="text" x-model="newMessage" @keydown.enter="sendChat()" placeholder="Tanya LLM tentang jadwal irigasi atau cuaca..." class="w-full bg-slate-50 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-2xl py-4 pl-5 pr-16 focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:border-green-500/30 transition-all text-sm shadow-inner placeholder-slate-400">
            <button @click="sendChat()" :disabled="newMessage.trim() === '' || isTyping" class="absolute right-3 bg-green-600 hover:bg-green-700 disabled:bg-slate-300 disabled:dark:bg-slate-700 text-white p-2.5 rounded-xl transition-colors shadow-sm outline-none">
                <svg class="w-4 h-4 transform -rotate-45 ml-0.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chatbotComponent', () => ({
            newMessage: '',
            isTyping: false,
            messages: [
                { role: 'bot', text: 'Halo Samuel! Saya AGA (Asisten Generatif Agrikultur). Saya memantau sensor IoT tanah Anda dan memadukannya dengan data cuaca BMKG. Ada yang ingin didiskusikan hari ini?', time: '10:00 AM' }
            ],

            scrollToBottom() {
                const container = document.getElementById('chatScrollArea');
                if(container) {
                    setTimeout(() => { container.scrollTop = container.scrollHeight; }, 50);
                }
            },

            sendChat() {
                if (this.newMessage.trim() === '') return;
                
                // Masukkan pesan pengguna
                this.messages.push({ 
                    role: 'user', 
                    text: this.newMessage, 
                    time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) 
                });
                this.newMessage = '';
                this.scrollToBottom();
                
                this.isTyping = true;
                this.scrollToBottom();
                
                // Simulasi delay Backend/LLM 2.5 Detik
                setTimeout(() => {
                    this.isTyping = false;
                    this.messages.push({ 
                        role: 'bot', 
                        text: 'Berdasarkan sensor IoT, kelembapan tanah lahan Anda memang turun menjadi 42%. Namun, menurut prediksi BMKG, akan turun HUJAN RINGAN sore nanti sekitar pukul 16:00 WIB. Jadi, Bapak TIDAK PERLU menyiram lahan hari ini.', 
                        time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) 
                    });
                    this.scrollToBottom();
                }, 2500);
            }
        }));
    });
</script>
@endsection