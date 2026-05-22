<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — Barca Academy Dormitory</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#004D98] via-[#003060] to-[#7A003C] flex items-center justify-center p-4 py-10">
<div class="w-full max-w-xl">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="bg-[#004D98] px-8 py-6 text-center">
            <div class="flex justify-center mb-3">
                <svg width="50" height="50" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="30" cy="30" rx="30" ry="30" fill="#EDBB00"/>
                    <ellipse cx="30" cy="30" rx="24" ry="24" fill="#004D98"/>
                    <rect x="10" y="10" width="40" height="40" rx="5" fill="#A50044"/>
                    <text x="30" y="38" text-anchor="middle" font-family="Georgia,serif" font-weight="bold" font-size="22" fill="#EDBB00">ND</text>
                </svg>
            </div>
            <h1 class="text-white font-extrabold text-lg">Barca Academy Dormitory</h1>
            <p class="text-blue-300 text-xs mt-1">Create your student account</p>
        </div>
        <div class="px-8 py-8">

            @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5 text-sm text-red-700">
                <p class="font-bold mb-1">Please fix these errors:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Juan dela Cruz"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white
                                  {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white
                                  {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Password *</label>
                        <input type="password" name="password" required placeholder="Min. 8 chars"
                               class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white
                                      {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Confirm Password *</label>
                        <input type="password" name="password_confirmation" required placeholder="Repeat password"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white">
                    </div>
                </div>

                <hr class="border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Student Information</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Student No. *</label>
                        <input type="text" name="student_number" value="{{ old('student_number') }}" required placeholder="2024-0001"
                               class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white
                                      {{ $errors->has('student_number') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('student_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Course *</label>
                        <input type="text" name="course" value="{{ old('course') }}" required placeholder="BS Computer Science"
                               class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white
                                      {{ $errors->has('course') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('course')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Year Level *</label>
                        <select name="year_level" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                            @foreach(range(1,6) as $y)
                                <option value="{{ $y }}" {{ old('year_level')==$y?'selected':'' }}>Year {{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Gender *</label>
                        <select name="gender" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
                            <option value="male"   {{ old('gender')=='male'  ?'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                            <option value="other"  {{ old('gender')=='other' ?'selected':'' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="09XXXXXXXXX"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50 focus:bg-white">
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3.5 bg-[#A50044] text-white font-extrabold rounded-xl hover:bg-[#7A003C] transition text-sm shadow-lg mt-2 active:scale-[.98]">
                    Create My Account
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-5">
                Already have an account? <a href="{{ route('login') }}" class="text-[#004D98] font-bold hover:text-[#A50044]">Sign In</a>
            </p>
        </div>
    </div>
    <p class="text-center text-blue-300 text-xs mt-4">
        <a href="{{ route('home') }}" class="hover:text-white">← Back to Home</a>
    </p>
</div>
</body>
</html>