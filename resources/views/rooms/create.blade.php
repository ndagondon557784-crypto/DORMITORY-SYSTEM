<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <x-card>
            <h2 class="text-2xl font-bold text-navy mb-6">Add New Room</h2>

            <form method="POST" action="{{ route('rooms.store') }}" class="space-y-6">
                @csrf

                <x-select
                    name="building_id"
                    label="Building"
                    :options="$buildings->pluck('name', 'id')->toArray()"
                    required
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input
                        name="room_number"
                        label="Room Number"
                        placeholder="A-101"
                        required
                    />

                    <x-select
                        name="floor"
                        label="Floor"
                        :options="['Ground' => 'Ground', '1st' => '1st', '2nd' => '2nd', '3rd' => '3rd', '4th' => '4th', '5th' => '5th']"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-select
                        name="type"
                        label="Room Type"
                        :options="['single' => 'Single', 'double' => 'Double', 'triple' => 'Triple']"
                        required
                    />

                    <x-input
                        name="capacity"
                        label="Capacity"
                        type="number"
                        placeholder="2"
                        min="1"
                        max="10"
                        required
                    />
                </div>

                <x-input
                    name="monthly_rent"
                    label="Monthly Rent (₱)"
                    type="number"
                    placeholder="5000"
                    step="0.01"
                    min="0"
                    required
                />

                <x-input
                    name="amenities"
                    label="Amenities"
                    placeholder="AC, Bed, Table, Chair"
                />

                <div class="flex gap-4 pt-6 border-t border-slate-200">
                    <x-button type="submit">
                        Create Room
                    </x-button>
                    <a href="{{ route('rooms.index') }}" class="px-6 py-3 rounded-lg bg-slate-200 text-slate-800 font-semibold hover:bg-slate-300 transition-smooth">
                        Cancel
                    </a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>