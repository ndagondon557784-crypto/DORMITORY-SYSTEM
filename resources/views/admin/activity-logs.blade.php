@extends('layouts.app')
@section('title', 'Activity Logs')
@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-gray-800">Activity Logs</h1>
    <p class="text-gray-400 text-sm mt-1">Complete audit trail of all system actions</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#004D98] text-white">
                <tr>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">User</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Action</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Description</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">IP</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3.5">
                        <p class="font-semibold text-gray-800">{{ $log->user?->name ?? 'System' }}</p>
                        <p class="text-gray-400 text-xs capitalize">{{ $log->user?->role ?? '' }}</p>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold
                            {{ in_array($log->action,['login','register'])  ? 'bg-blue-100 text-blue-700'   :
                               (in_array($log->action,['create','approve']) ? 'bg-green-100 text-green-700' :
                               (in_array($log->action,['delete','reject'])  ? 'bg-red-100 text-red-600'    : 'bg-gray-100 text-gray-600')) }}">
                            {{ strtoupper($log->action) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-600 max-w-xs truncate">{{ $log->description }}</td>
                    <td class="px-5 py-3.5 text-gray-400 text-xs font-mono">{{ $log->ip_address }}</td>
                    <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $log->created_at->format('M d, Y g:i A') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-12 text-center text-gray-400">No activity logs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $logs->links() }}</div>
    @endif
</div>

@endsection