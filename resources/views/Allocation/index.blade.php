<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-3xl font-bold text-navy">Room Allocations</h2>
                <p class="text-slate-600 mt-1">Manage student room assignments</p>
            </div>
            <a href="{{ route('allocations.create') }}" class="flex items-center gap-2 px-6 py-3 gradient-gold text-white rounded-lg font-semibold shadow-lg-custom hover-lift transition-smooth">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                New Allocation
            </a>
        </div>

        <!-- Search -->
        <x-card>
            <form method="GET" action="{{ route('allocations.index') }}" class="flex gap-2">
                <input
                    type="text"
                    name="q"
                    placeholder="Search by student name, ID, or room..."
                    value="{{ request('q') }}"
                    class="flex-1 px-4 py