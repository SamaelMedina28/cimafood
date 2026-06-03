<div 
    x-data="{ 
        show: false, 
        message: '', 
        timeout: null 
    }"
    x-on:toast-show.window="
        message = event.detail.message;
        show = true;
        if (timeout) clearTimeout(timeout);
        timeout = setTimeout(() => show = false, 2500);
    "
    x-show="show"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="translate-y-4 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-4 opacity-0"
    class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50"
    style="display: none;"
>
    <div class="bg-green-600/90 backdrop-blur-sm text-white px-4 py-2.5 rounded-full shadow-lg flex items-center gap-2 text-sm">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span x-text="message"></span>
    </div>
</div>
