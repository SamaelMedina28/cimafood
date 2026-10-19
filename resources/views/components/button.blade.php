<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-green-600 border border-transparent rounded-full font-semibold text-sm text-white hover:bg-green-700 active:bg-green-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed transition-colors']) }}>
    {{ $slot }}
</button>
