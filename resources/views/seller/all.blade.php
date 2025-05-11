@extends('layout')

@section('konten')
    <div class="container py-5">
        <h2>Semua Pesanan</h2>
        <hr class="mb-4">

        @forelse ($confirmed as $confirm)
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <h6 class="mb-0">Pembeli:</h6>
                            <p class="mb-0">{{ $confirm->name }}</p>
                        </div>
                        <div class="col-md-2">
                            <h6 class="mb-0">Produk:</h6>
                            <p class="mb-0">{{ $confirm->product }}</p>
                        </div>
                        <div class="col-md-2">
                            <h6 class="mb-0">Harga:</h6>
                            <p class="mb-0">Rp. {{ number_format($confirm->price) }}</p>
                        </div>
                        <div class="col-md-1">
                            <h6 class="mb-0">Kategori:</h6>
                            <p class="mb-0">{{ $confirm->category }}</p>
                        </div>
                        <div class="col-md-2">
                            <h6 class="mb-0">Status:</h6>
                            <span class="badge bg-info">{{ ucfirst($confirm->status) }}</span>
                        </div>

                        <div class="modal fade" id="detailconfirm{{ $confirm->id }}" tabindex="-1" aria-labelledby="detailconfirmLabel{{ $confirm->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailconfirmLabel{{ $confirm->id }}">Detail Pesanan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Pembeli:</strong> {{ $confirm->name }}</p>
                                        <p><strong>Produk:</strong> {{ $confirm->product }}</p>
                                        <p><strong>Harga:</strong> Rp. {{ number_format($confirm->price) }}</p>
                                        <p><strong>Kategori:</strong> {{ $confirm->category }}</p>
                                        <p><strong>ID Pesanan:</strong> {{ $confirm->trx_id }}</p>
                                        <p><strong>ID Product:</strong> {{ $confirm->id_product }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($confirm->status) }}</p>
                                        <p><strong>Tanggal Pemesanan:</strong> {{ $confirm->created_at }}</p>
                                        @if(!isset($confirm->status_date['reject']))
                                            <p><strong>Tanggal Dikonfirmasi:</strong> {{ $confirm->status_date['confirm'] ?? "Belum Dikonfirmasi" }}</p>
                                            <p><strong>Tanggal Penyelesaian:</strong> {{ $confirm->status_date['done'] ?? "Belum Diselesaikan"}}</p>
                                            @else
                                            <p><strong>Tanggal Penolakan:</strong> {{ $confirm->status_date['reject']}}</p>
                                        @endif
                                       
                                        
                                       
                                        
                                        {{-- Tambahkan detail lain di sini --}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-md-3 text-end">
                            <span class="btn btn-sm btn-info text-light" data-bs-toggle="modal" data-bs-target="#detailconfirm{{ $confirm->id }}">
                                Details
                            </span>
                            <a href="{{ route('seller.chat', $confirm->trx_id) }}" class="btn btn-sm btn-primary"><i class="bi bi-chat-dots-fill me-1"></i> Chat</a>
                            {{-- Tambahkan tombol lain jika diperlukan (misalnya, untuk menandai sudah dikirim) --}}
                            {{-- <button class="btn btn-sm btn-warning ms-2">Kirim</button> --}}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Tidak ada pesanan yang dikonfirmasi.</p>
        @endforelse

        <div class="d-flex justify-content-between align-items-center mt-3">
            <p class="text-muted">Menampilkan {{ $confirmed->firstItem() }} - {{ $confirmed->lastItem() }} dari total {{ $confirmed->total() }} data</p>
            <div>
                <label for="perPage" class="form-label me-2">Tampilkan per halaman:</label>
                <select id="perPage" class="form-select form-select-sm" onchange="window.location.href = '{{ request()->url() }}?perPage=' + this.value">
                    <option value="" selected disabled >Tampilkan per halaman</option>
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </div>
        @if ($confirmed->hasPages())
            <div class="mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item {{ $confirmed->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $confirmed->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        @foreach ($confirmed->getUrlRange(1, $confirmed->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $confirmed->currentPage() ? 'active' : '' }}" aria-current="page">
                                <a class="page-link" href="{{ $url }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        <li class="page-item {{ $confirmed->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $confirmed->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif
    </div>
@endsection