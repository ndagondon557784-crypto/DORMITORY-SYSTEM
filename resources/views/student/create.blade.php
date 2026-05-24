<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <x-card>
            <h2 class="text-2xl font-bold text-navy mb-6">Add New Student</h2>

            <form method="POST" action="{{ route('students.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input
                        name="name"
                        label="Full Name"
                        placeholder="John Doe"
                        required
                    />

                    <x-input
                        name="email"
                        label="Email Address"
                        type="email"
                        placeholder="john@example.com"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input
                        name="student_id"
                        label="Student ID"
                        placeholder="STU-2024-001"
                        required
                    />

                    <x-input
                        name="phone"
                        label="Phone Number"
                        placeholder="+63 9XX XXX XXXX"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input
                        name="password"
                        label="Password"
                        type="password"
                        placeholder="••••••••"
                        required
                    />

                    <x-input
                        name="password_confirmation"
                        label="Confirm Password"
                        type="password"
                        placeholder="••••••••"
                        required
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input
                        name="course"
                        label="Course"
                        placeholder="Bachelor of Science in Information Technology"
                        required
                    />

                    <x-input
                        name="year"
                        label="Year"
                        placeholder="2024"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input
                        name="date_of_birth"
                        label="Date of Birth"
                        type="date"
                    />

                    <x-input
                        name="address"
                        label="Address"
                        placeholder="123 Main St, City"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input
                        name="guardian_name"
                        label="Guardian Name"
                        placeholder="Jane Doe"
                    />

                    <x-input
                        name="guardian_contact"
                        label="Guardian Contact"
                        placeholder="+63 9XX XXX XXXX"
                    />
                </div>

                <div class="flex gap-4 pt-6 border-t border-slate-200">
                    <x-button type="submit">
                        Create Student
                    </x-button>
                    <a href="{{ route('students.index') }}" class="px-6 py-3 rounded-lg bg-slate-200 text-slate-800 font-semibold hover:bg-slate-300 transition-smooth">
                        Cancel
                    </a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>