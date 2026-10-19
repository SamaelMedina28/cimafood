@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-200 focus:border-green-600 focus:ring-green-600 rounded-lg shadow-sm placeholder:text-gray-400']) !!}>
