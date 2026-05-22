@extends('layouts.app')
@section('title', 'Admin Detail')
@section('content')

<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.admins.index') }}" class="text-[#004D98] text-sm hover:underline">← Back</a>
        <h1 class="text-2xl font-extrabold text-gray-800 mt-2">Administrator Detail</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-[#004D98] px-6 py-5 text-white flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-[#EDBB00] text-[#004D98] flex items-center justify-center font-black text-2xl">
                {{ strtoupper(substr($admin->name,0,1)) }}
            </div>
            <div>
                <h2 class="text-xl font-black">{{ $admin->name }}</h2>
                <p class="text-blue-200 text-sm">{{ $admin->email }}</p>
                <span class="inline-block mt-1 px-2 py-0.5 bg-[#EDBB00] text-[#004D98] text-xs font-bold rounded-full">ADMIN</span>
            </div>
        </div>
        <div class="p-6 grid grid-cols-2 gap-5">
            @foreach([
                ['Full Name',     $admin->name],
                ['Email',         $admin->email],
                ['Role',          ucfirst($admin->role)],
                ['Account Created',$admin->created_at->format('M d, Y')],
            ] as [$l,$v])
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">{{ $l }}</p>
                <p class="text-gray-800 font-semibold">{{ $v }}</p>
            </div>
            @endforeach
        </div>
        <div class="px-6 pb-6 flex gap-3 border-t border-gray-100 pt-5">
            <a href="{{ route('admin.admins.edit',$admin) }}"
               class="px-5 py-2 bg-[#EDBB00] text-[#004D98] font-bold rounded-xl text-sm hover:bg-yellow-400 transition">
                Edit Admin
            </a>
            @if($admin->id !== auth()->id())
            <form method="POST" action="{{ route('admin.admins.destroy',$admin) }}"
                  onsubmit="return confirm('Delete this admin account?')">
                @csrf @method('DELETE')
                <button class="px-5 py-2 bg-red-100 text-red-700 font-bold rounded-xl text-sm hover:bg-red-200 transition">
                    Delete
                </button>
            </form>
            @endif
        </div>
    </div>
</div>

@endsection