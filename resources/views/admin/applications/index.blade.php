@extends('layouts.app')
@section('title', 'Applications')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800">Room Applications</h1>
        <p class="text-gray-400 text-sm">Review and manage student room applications</p>
    </div>
</div>

<!-- Status Tabs -->
<div class="flex gap-3 mb-6 flex-wrap">
    @foreach([['','All'],['pending','Pending'],['approved','Approved'],['rejected','Rejected']] as [$val,$label])
    <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
       class="px-4 py-2 rounded-xl text-sm font-bold transition
              {{ request('status','') === $val
                 ? 'bg-[#004D98] text-white shadow'
                 : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
        {{ $label }}
        @if($val !== '')
        <span class="ml-1 px-1.5 py-0.5 rounded-full text-xs
            {{ $val==='pending'  ? 'bg-yellow-100 text-yellow-700' :
               ($val==='approved' ? 'bg-green-100 text-green-700'  : 'bg-red-100 text-red-600') }}">
            {{ $counts[$val] }}
        </span>
        @endif
    </a>
    @endforeach
</div>

<!-- Search -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form method="GET" class="flex gap-3 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search student name or number..."
               class="flex-1 min-w-44 px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#004D98] bg-gray-50">
        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <button type="submit"
                class="px-5 py-2 bg-[#004D98] text-white rounded-xl text-sm font-bold hover:bg-[#003a73] transition">
            Search
        </button>
        <a href="{{ route('admin.applications.index') }}"
           class="px-5 py-2 border border-gray-200 text-gray-500 rounded-xl text-sm hover:bg-gray-50 transition">
            Reset
        </a>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#004D98] text-white">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Student</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Room Applied</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Applied</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($applications as $app)
                <tr class="hover:bg-blue-50/30 transition">
                    <td class="px-5 py-4">
                        <p class="font-bold text-gray-800">{{ $app->student->user->name }}</p>
                        <p class="text-gray-400 text-xs">{{ $app->student->student_number }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-semibold text-gray-700">Room {{ $app->room->room_number }}</p>
                        <p class="text-gray-400 text-xs">{{ $app->room->building }} · {{ ucfirst($app->room->type) }}</p>
                    </td>
                    <td class="px-5 py-4 text-gray-500 text-xs">{{ $app->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold
                            {{ $app->status==='pending'  ? 'bg-yellow-100 text-yellow-700' :
                               ($app->status==='approved' ? 'bg-green-100 text-green-700'  : 'bg-red-100 text-red-600') }}">
                            {{ ucfirst($app->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex gap-1.5 flex-wrap">
                            <a href="{{ route('admin.applications.show',$app) }}"
                               class="px-3 py-1.5 bg-[#004D98] text-white text-xs rounded-lg hover:bg-[#003a73] transition font-semibold">
                                View
                            </a>
                            @if($app->isPending())
                            <form method="POST" action="{{ route('admin.applications.approve',$app) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1.5 bg-green-500 text-white text-xs rounded-lg hover:bg-green-600 transition font-semibold">
                                    Approve
                                </button>
                            </form>
                            <button type="button"
                                    onclick="document.getElementById('reject-{{ $app->id }}').classList.toggle('hidden')"
                                    class="px-3 py-1.5 bg-red-100 text-red-600 text-xs rounded-lg hover:bg-red-200 transition font-semibold">
                                Reject
                            </button>
                            @endif
                        </div>
                        @if($app->isPending())
                        <div id="reject-{{ $app->id }}" class="hidden mt-2">
                            <form method="POST" action="{{ route('admin.applications.reject',$app) }}" class="flex gap-2">
                                @csrf
                                <input type="text" name="admin_notes" required
                                       placeholder="Reason for rejection..."
                                       class="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-red-400">
                                <button type="submit"
                                        class="px-3 py-1.5 bg-red-500 text-white text-xs rounded-lg font-semibold hover:bg-red-600 transition">
                                    Confirm
                                </button>
                                <button type="button"
                                        onclick="document.getElementById('reject-{{ $app->id }}').classList.add('hidden')"
                                        class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-lg hover:bg-gray-200 transition">
                                    ✕
                                </button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-16 text-center text-gray-400">No applications found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($applications->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $applications->links() }}</div>
    @endif
</div>

@endsection