@extends('layout')

@section('konten')
    <div class="container py-5">
        <h2>Pesanan Baru</h2>
        <hr class="mb-4">

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($orders as $order)
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <h6 class="mb-0">Pembeli:</h6>
                            <p class="mb-0">{{ $order->name }}</p>
                        </div>
                        <div class="col-md-2">
                            <h6 class="mb-0">Produk:</h6>
                            <p class="mb-0">{{ $order->product }}</p>
                        </div>
                        <div class="col-md-2">
                            <h6 class="mb-0">Harga:</h6>
                            <p class="mb-0">Rp. {{ number_format($order->price) }}</p>
                        </div>
                        <div class="col-md-1">
                            <h6 class="mb-0">Kategori:</h6>
                            <p class="mb-0">{{ $order->category }}</p>
                        </div>
                        <div class="col-md-2">
                            <h6 class="mb-0">Status:</h6>
                            <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
                        </div>

                        <div class="modal fade" id="detailOrder{{ $order->id }}" tabindex="-1" aria-labelledby="detailOrderLabel{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailOrderLabel{{ $order->id }}">Detail Pesanan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Pembeli:</strong> {{ $order->name }}</p>
                                        <p><strong>Produk:</strong> {{ $order->product }}</p>
                                        <p><strong>Harga:</strong> Rp. {{ number_format($order->price) }}</p>
                                        <p><strong>Kategori:</strong> {{ $order->category }}</p>
                                        <p><strong>ID Pesanan:</strong> {{ $order->trx_id }}</p>
                                        <p><strong>ID Product:</strong> {{ $order->id_product }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                                        <p><strong>Tanggal Pemesanan:</strong> {{ $order->created_at }}</p>
                                        @if(!isset($order->status_date['reject']))
                                            <p><strong>Tanggal Dikonfirmasi:</strong> {{ $order->status_date['confirm'] ?? "Belum Dikonfirmasi" }}</p>
                                            <p><strong>Tanggal Penyelesaian:</strong> {{ $order->status_date['done'] ?? "Belum Diselesaikan"}}</p>
                                            @else
                                            <p><strong>Tanggal Penolakan:</strong> {{ $order->status_date['reject']}}</p>
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
                            <span class="btn btn-sm btn-info text-light" data-bs-toggle="modal" data-bs-target="#detailOrder{{ $order->id }}">
                                Details
                            </span>
                            <a href="{{ route('seller.chat', $order->trx_id) }}" class="btn btn-sm btn-primary me-2"><i class="bi bi-chat-dots-fill me-1"></i> Chat</a>
                            <form action="{{ route('seller.orders.accept', $order->id) }}" method="post" class="d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-circle-fill me-1"></i> Terima</button>
                            </form>
                            <form action="{{ route('seller.orders.reject', $order->id) }}" method="POST" class="d-inline-block ms-1">
                                @csrf
                               
                                <span class="btn btn-sm btn-danger text-light" data-bs-toggle="modal" data-bs-target="#rejectOrder{{ $order->id }}">
                                    Tolak
                                </span>

                                {{--  --}}
                                <div class="modal fade" id="rejectOrder{{ $order->id }}" tabindex="-1" aria-labelledby="rejectOrderLabel{{ $order->id }}" aria-hidden="true">
                                    <div class="modal-dialog text-start">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rejectOrderLabel{{ $order->id }}">Pembatalan Pesanan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Pembeli:</strong> {{ $order->name }}</p>
                                                <p><strong>Nama Produk:</strong> {{ $order->product }}</p>
                                                <p><strong>Harga:</strong> Rp. {{ number_format($order->price) }}</p>
                                                <p><strong>Kategori:</strong> {{ $order->category }}</p>
                                                <p><strong>ID Pesanan:</strong> {{ $order->trx_id }}</p>
                                                <p><strong>Alasan:</strong> 
                                                    <select class="form-select @error('reason') is-invalid @enderror" id="reason" name="reason" required>
                                                        <option value="" selected disabled>Pilih Alasan</option>
                                                        <option value="Stok telah habis" {{ old('reason') == 'Stok telah habis' ? 'selected' : '' }}>Stok telah habis</option>
                                                        <option value="Produk dalam masalah" {{ old('reason') == 'Produk dalam masalah' ? 'selected' : '' }}>Produk dalam masalah</option>
                                                        <option value="Pembeli tidak merespon" {{ old('reason') == 'Pembeli tidak merespon' ? 'selected' : '' }}>Pembeli tidak merespon</option>
                                                        <option value="Alasan lain" {{ old('reason') == 'Alasan lain' ? 'selected' : '' }}>Alasan lain</option>
                                                        {{-- Tambahkan opsi kategori lain dari database jika ada --}}
                                                    </select>
                                                </p>
                                                <p><strong>Pesan:</strong> <input type="text" name="message" id=""></p>
                                               
                                    
                                                
                                               
                                                
                                                {{-- Tambahkan detail lain di sini --}}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak pesanan ini?')"><i class="bi bi-x-circle-fill me-1"></i> Tolak</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--  --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Tidak ada pesanan baru.</p>
        @endforelse

        {{-- Paginasi (jika ada) --}}
        <div class="d-flex justify-content-between align-items-center mt-3">
            <p class="text-muted">Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari total {{ $orders->total() }} data</p>
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
        @if ($orders->hasPages())
            <div class="mt-4">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item {{ $orders->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $orders->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $orders->currentPage() ? 'active' : '' }}" aria-current="page">
                                <a class="page-link" href="{{ $url }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        <li class="page-item {{ $orders->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $orders->nextPageUrl() }}{{ request('perPage') ? '&perPage=' . request('perPage') : '' }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif
    </div>
@endsection