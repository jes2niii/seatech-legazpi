@extends('layouts.admin')

@section('title', 'Inquiry Details')
@section('page-title', 'Inquiry Details')

@section('content')
@php $p = request()->segment(1); @endphp
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Inquiry #{{ $inquiry->id }}</h3>
            @if(!$inquiry->is_read)
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Unread</span>
            @else
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Read</span>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
            <div>
                <span class="text-xs text-gray-500 uppercase">Name</span>
                <p class="font-medium">{{ $inquiry->name }}</p>
            </div>
            <div>
                <span class="text-xs text-gray-500 uppercase">Email</span>
                <p class="font-medium">{{ $inquiry->email }}</p>
            </div>
            @if($inquiry->mobile)
            <div>
                <span class="text-xs text-gray-500 uppercase">Mobile</span>
                <p class="font-medium">{{ $inquiry->mobile }}</p>
            </div>
            @endif
            <div>
                <span class="text-xs text-gray-500 uppercase">Date</span>
                <p class="font-medium">{{ $inquiry->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        <div class="mb-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">Subject</h4>
            <p class="text-gray-800 font-medium">{{ $inquiry->subject }}</p>
        </div>

        <div class="mb-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">Message</h4>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $inquiry->message }}</p>
        </div>

        <div class="flex space-x-3">
            <a href="mailto:{{ $inquiry->email }}" class="bg-[#0077B6] text-white px-4 py-2 rounded-lg text-sm hover:bg-[#005f94] transition">Reply via Email</a>
            <a href="{{ route($p.'.inquiries.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-400 transition">Back to List</a>
            @if(Route::has($p.'.inquiries.destroy'))
            <form action="{{ route($p.'.inquiries.destroy', $inquiry) }}" method="POST" class="inline" x-data @submit.prevent="if(confirm('Delete this inquiry?')) $el.submit()">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">Delete</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
