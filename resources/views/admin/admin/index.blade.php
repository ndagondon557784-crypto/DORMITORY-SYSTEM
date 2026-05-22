@extends('layouts.app')
@section('title','Administrators')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div><h1 class="text-2xl font-extrabold text-gray-800">Administrators</h1><p class="text-gray-400 text-sm">Manage admin accounts</p></div>
    <a href="{{ route('admin.admins.create') }}" class="px-5 py-2.5 bg-[#A50044] text-white font-bold rounded-xl hover:bg-[#7A003C] transition text-sm shadow">+ Add Admin</a>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-[#004D98] text-white"><tr>
            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Name</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Email</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Joined</th>
            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Actions</th>
        </tr></thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($admins as $admin)
            <tr class="hover:bg-blue-50/30 transition">
                <td class="px-5 py-4 font-bold text-gray-800">{{ $admin->name }} @if($admin->id===auth()->id())<span class="ml-1 text-xs bg-[#EDBB00] text-[#004D98] px-2 py-0.5 rounded-full font-bold">You</span>@endif</td>
                <td class="px-5 py-4 text-gray-600">{{ $admin->email }}</td>
                <td class="px-5 py-4 text-gray-500">{{ $admin->created_at->format('M d, Y') }}</td>
                <td class="px-5 py-4"><div class="flex gap-1.5">
                    <a href="{{ route('admin.admins.edit',$admin) }}" class="px-3 py-1.5 bg-[#EDBB00] text-[#004D98] text-xs rounded-lg hover:bg-yellow-400 transition font-semibold">Edit</a>
                    @if($admin->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.admins.destroy',$admin) }}" onsubmit="return confirm('Delete this admin?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1.5 bg-red-50 text-red-600 text-xs rounded-lg hover:bg-red-100 transition font-semibold">Delete</button>
                    </form>
                    @endif
                </div></td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-5 py-12 text-center text-gray-400">No admins found.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($admins->hasPages())<div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $admins->links() }}</div>@endif
</div>
@endsection