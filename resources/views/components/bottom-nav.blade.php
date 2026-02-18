<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
    <div class="flex justify-around items-center h-16 max-w-lg mx-auto px-2">
        <a href="{{ route('dashboard') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-xs mt-0.5 font-medium">Inicio</span>
        </a>

        <a href="{{ route('transactions.index') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('transactions.*') ? 'text-blue-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="text-xs mt-0.5 font-medium">Transacoes</span>
        </a>

        <a href="{{ route('budgets.index') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('budgets.*') ? 'text-blue-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="text-xs mt-0.5 font-medium">Orcamentos</span>
        </a>

        <a href="{{ route('stash.index') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('stash.*') ? 'text-blue-600' : 'text-gray-400' }}">
            <!-- Caixinha (box) - outline, stroke=currentColor, estilo Heroicons -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <!-- tampa -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 8.5l8-3 8 3" />
                <!-- corpo -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 8.5v9A2.5 2.5 0 006.5 20h11A2.5 2.5 0 0020 17.5v-9" />
                <!-- divisória/aba interna -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 5.5v14.5" />
                <!-- “fenda” da caixinha (slot) -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 11h6" />
                </svg>
            <span class="text-xs mt-0.5 font-medium">Caixinhas</span>
        </a>

        <a href="{{ route('more') }}"
           class="flex flex-col items-center justify-center flex-1 py-1 {{ request()->routeIs('more') ? 'text-blue-600' : 'text-gray-400' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span class="text-xs mt-0.5 font-medium">Mais</span>
        </a>
    </div>
</nav>
