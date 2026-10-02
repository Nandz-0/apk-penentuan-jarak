@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid px-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                     style="width:56px;height:56px; font-size:1.5rem;">
                    <i class="bi bi-person"></i>
                </div>
                <div>
                    <h1 class="h4 fw-bold mb-0">{{ $user->name }}</h1>
                    <div class="text-muted small">{{ $user->email }}</div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection