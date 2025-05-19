{{-- filepath: /home/rajadwiaqso/Desktop/market4.6/resources/views/users/profile.blade.php --}}
@extends('layout')

@section('konten')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Profil Pengguna</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3 text-center">
                        {{-- <i class="bi bi-person-circle" style="font-size: 4rem; color: #0d6efd;"></i> --}}
                        @if ($user->profile_picture)
                            <img src="{{ asset('storage/images/profile/' . $user->profile_picture) }}" alt="Foto Profil" class="rounded-circle" style="width: 100px; height: 100px;">
                        @else
                            <img src="{{ asset('storage/images/profile/default.jpg') }}" alt="Foto Profil" class="rounded-circle" style="width: 100px; height: 100px;">
                        @endif
                    </div>
                    <table class="table table-borderless">
                        <tr>
                            <th>Nama</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td>{{ '@' . $user->username }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>
                                <span class="badge bg-info text-dark text-capitalize">{{ $user->role }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection