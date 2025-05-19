@extends('layout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
@endsection
@section('konten')
    <div class="container mt-5">
        <h2>Dashboard Admin</h2>
        <hr class="mb-4">

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm mb-5">
            <div class="card-header bg-primary text-white">
                Pengajuan Penjual Baru
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
    <tr>
        <th>Nama Lengkap</th>
        <th>Nama Toko</th>
        <th>Nomor Telepon</th>
        <th>KTP</th>
        <th>NIK</th>
        <th>Pesan</th>
        <th>Email Pengirim</th>
        <th class="text-center">Aksi</th>
    </tr>
</thead>
<tbody>
    @forelse ($forms as $form)
        <tr>
            <td>{{ $form->fullname }}</td>
            <td>{{ $form->name }}</td>
            <td>{{ $form->phone }}</td>
            <td>
                @if($form->img)
                    <img src="{{ asset('storage/images/form/' . $form->img) }}" alt="{{ $form->img }}" style="max-height: 80px;">
                @else
                    -
                @endif
            </td>
            <td>{{ $form->nik ?? '-' }}</td>
            <td>{{ $form->message }}</td>
            <td>{{ $form->from }}</td>
            <td class="text-center">
                <form action="{{ route('form.accept', $form->id) }}" method="post" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">Terima</button>
                </form>
                <form action="{{ route('form.decline', $form->id) }}" method="post" class="d-inline-block ms-1">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="8" class="text-center">Tidak ada pengajuan baru.</td></tr>
    @endforelse
</tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Bagian Statistik atau Informasi Lain Bisa Ditambahkan Di Sini --}}

    </div>
@endsection