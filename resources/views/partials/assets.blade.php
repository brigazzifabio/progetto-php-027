{{-- Usa Vite se gli asset sono compilati (npm run build / npm run dev), altrimenti Tailwind da CDN. --}}
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@else
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
@endif
