@extends('layouts.koordinator-humas')

@section('title', 'Detail Brief Humas')
@section('header', 'Detail Brief Humas')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800">{{ $briefHumas->judul }}</h1>
            <div class="flex gap-2">
                <a href="{{ route('koordinator-humas.brief-humas.edit', $briefHumas) }}" class="px-4 py-2 bg-yellow-500 text-white rounded">Edit</a>
                <a href="{{ route('koordinator-humas.brief-humas.index') }}" class="px-4 py-2 bg-gray-200 rounded">Kembali</a>
            </div>
        </div>
        <div>
            <p class="text-sm text-gray-700 mb-1">Link Drive</p>
            <a href="{{ $briefHumas->link_drive }}" target="_blank" class="text-blue-600 hover:underline">{{ $briefHumas->link_drive }}</a>
        </div>
        <div>
            <p class="text-sm text-gray-700 mb-1">Catatan</p>
            <div class="bg-gray-50 rounded p-4 text-gray-800 whitespace-pre-line">{{ $briefHumas->catatan }}</div>
        </div>
    </div>
@endsection

