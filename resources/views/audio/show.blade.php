@extends('layouts.app')

@php
    $title = $audioFile->title;
@endphp

@section('content')
<div class="player-container" style="max-width: 800px; margin: 40px auto; padding: 20px;">
    <div class="title" style="margin-bottom: 30px;">
        <h2 style="color: #1E847D; font-size: 24px;">{{ $audioFile->title }}</h2>
        <p style="color: #666; margin: 5px 0;">{{ $audioFile->original_name }}</p>
    </div>
    
    <div class="player-box" style="background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(30,132,125,0.1);">
        <audio id="audio-player" controls style="width: 100%; margin-bottom: 15px;">
            <source src="{{ $audioFile->file_url }}" type="{{ $audioFile->mime_type }}">
            Tu navegador no soporta el elemento audio.
        </audio>
        
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('audio.editor', $audioFile) }}" 
               style="display: inline-block; padding: 8px 20px; background: #1E847D; color: white;