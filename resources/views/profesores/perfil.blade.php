@extends('layouts.panel')

@section('title', 'Perfil')

@section('content')
<div class="container">
    <h2 class="text-center" style="color:#1E847D; font-family: Montserrat, Verdana, Helvetica, sans-serif; font-size:2.2rem; margin-bottom: 40px; margin-top: 30px; letter-spacing:1px;">Perfil del profesor</h2>

    <div class="perfil-form-container">
        <div class="perfil-form">
            <div class="perfil-foto">
                <img src="{{ asset('images/user-icon.png') }}" alt="Foto de perfil">
            </div>

            <h3>{{ Auth::user()->name ?? 'Nombre del profesor' }}</h3>

            <div class="perfil-info">
                <div class="info-item">
                    <span class="label">Correo electrónico:</span>
                    <span class="value">{{ Auth::user()->email ?? '---' }}</span>
                </div>

                <div class="info-item">
                    <span class="label">Miembro desde:</span>
                    <span class="value">{{ Auth::user() && Auth::user()->created_at ? Auth::user()->created_at->format('d/m/Y') : '---' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection