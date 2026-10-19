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
    x-transition:enter="ease-out duration-200"
    x-transition:enter-start="translate-y-2 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="ease-in duration-150"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-2 opacity-0"
    class="fixed bottom-24 sm:bottom-6 left-1/2 -translate-x-1/2 z-50"
    role="status"
    aria-live="polite"
    style="display: none;"
>
    <div class="bg-gray-900 text-white pl-3 pr-4 py-2.5 rounded-full shadow-lg flex items-center gap-2 text-sm font-medium whitespace-nowrap">
        <span class="flex size-5 items-center justify-center rounded-full bg-green-600">
            <x-icon name="check" class="size-3.5" />
        </span>
        <span x-text="message"></span>
    </div>
</div>
