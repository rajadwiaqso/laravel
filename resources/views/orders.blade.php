{{-- filepath: /home/rajadwiaqso/Desktop/market 4.0/resources/views/orders.blade.php --}}
@extends('layout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/rating.css')}}">
@endsection
@section('konten')
    <div class="container py-5">
        <h2 class="text-center mb-4">Riwayat Pesanan</h2>
        <hr class="mb-4">

        @forelse ($orders as $order)
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <h6 class="mb-1"><small class="text-muted">Penjual:</small></h6>
                            <p class="mb-0">{{ $order->name }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6 class="mb-1"><small class="text-muted">Produk:</small></h6>
                            <p class="mb-0">{{ $order->product }}</p>
                        </div>
                        <div class="col-md-2">
                            <h6 class="mb-1"><small class="text-muted">Harga:</small></h6>
                            <p class="mb-0">Rp. {{ number_format($order->price) }}</p>
                        </div>
                        <div class="col-md-2">
                            <h6 class="mb-1"><small class="text-muted">Status:</small></h6>
                            @php
                                $statusClass = '';
                                $statusIcon = '';
                                switch ($order->status) {
                                    case 'pending':
                                        $statusClass = 'badge bg-warning text-dark';
                                        $statusIcon = 'bi bi-hourglass-split';
                                        break;
                                    case 'confirm':
                                        $statusClass = 'badge bg-info';
                                        $statusIcon = 'bi bi-check-circle';
                                        break;
                                    case 'shipping':
                                        $statusClass = 'badge bg-primary';
                                        $statusIcon = 'bi bi-truck';
                                        break;
                                    case 'done':
                                        $statusClass = 'badge bg-success';
                                        $statusIcon = 'bi bi-check-circle-fill';
                                        break;
                                    case 'reject':
                                        $statusClass = 'badge bg-danger';
                                        $statusIcon = 'bi bi-x-circle';
                                        break;
                                    default:
                                        $statusClass = 'badge bg-secondary';
                                        $statusIcon = 'bi bi-question-circle';
                                        break;
                                }
                            @endphp
                            <span class="{{ $statusClass }}">
                                <i class="{{ $statusIcon }} me-1"></i>{{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="col-md-3 text-end">
                            @if ($order->status == 'pending')
                                <button class="btn btn-sm btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectOrder{{ $order->id }}">
                                    <i class="bi bi-x-circle-fill me-1"></i> Batalkan
                                </button>
                            @endif
                            @if ($order->status == 'confirm')
                            <form action="{{ route('buyer.confirm', $order->id) }}" method="post" class="d-inline-block me-2">
                                @csrf
                                <button class="btn btn-sm btn-success"><i class="bi bi-check-circle-fill me-1"></i> Selesaikan</button>
                            </form>
                        @endif
                            @if ($order->status == 'done' && !is_int($order->rating))
                                <a href="{{ route('buyer.rating', $order->trx_id) }}" class="btn btn-sm btn-outline-secondary me-2">
                                    <i class="bi bi-star-fill me-1"></i> Beri Rating
                                </a>
                            @endif
                            <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#detailOrder{{ $order->id }}">
                                <i class="bi bi-info-circle-fill me-1"></i> Detail
                            </button>
                            <a href="{{ route('buyer.chat', $order->trx_id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-chat-dots-fill me-1"></i> Chat
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Detail Pesanan -->
            <div class="modal fade" id="detailOrder{{ $order->id }}" tabindex="-1" aria-labelledby="detailOrderLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailOrderLabel{{ $order->id }}">Detail Pesanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Penjual:</strong> {{ $order->name }}</p>
                            <p><strong>Produk:</strong> {{ $order->product }}</p>
                            <p><strong>Harga:</strong> Rp. {{ number_format($order->price) }}</p>
                            <p><strong>Kategori:</strong> {{ $order->category }}</p>
                            <p><strong>ID Pesanan:</strong> {{ $order->trx_id }}</p>
                            <p><strong>Status:</strong> <span class="{{ $statusClass }}">{{ ucfirst($order->status) }}</span></p>
                            <p><strong>Tanggal Pemesanan:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="rejectOrder{{ $order->id }}" tabindex="-1" aria-labelledby="rejectOrderLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog text-start">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="rejectOrderLabel{{ $order->id }}">Pembatalan Pesanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ( isset($order->status_date['from']) && $order->status_date['from'] == Auth::user()->email)
                                <p class="alert alert-info"><strong>Masih dalam proses pembatalan, Tolong tunggu respon penjual maksimal 1 hari</strong></p>
                            @else
                                <p><strong>Penjual:</strong> {{ $order->name }}</p>
                                <p><strong>Nama Produk:</strong> {{ $order->product }}</p>
                                <p><strong>Harga:</strong> Rp. {{ number_format($order->price) }}</p>
                                <p><strong>Kategori:</strong> {{ $order->category }}</p>
                                <p><strong>ID Pesanan:</strong> {{ $order->trx_id }}</p>
                                <form action="{{route('orders.reject', $order->trx_id)}}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="reason" class="form-label"><strong>Alasan Pembatalan:</strong></label>
                                        <select class="form-select @error('reason') is-invalid @enderror" id="reason" name="reason" required>
                                            <option value="" selected disabled>Pilih Alasan</option>
                                            <option value="Produk tidak dikirim" {{ old('reason') == 'Produk tidak dikirim' ? 'selected' : '' }}>Produk tidak dikirim</option>
                                            <option value="Tidak sengaja membeli" {{ old('reason') == 'Tidak sengaja membeli' ? 'selected' : '' }}>Tidak sengaja membeli</option>
                                            <option value="Penjual tidak merespon" {{ old('reason') == 'Penjual tidak merespon' ? 'selected' : '' }}>Penjual tidak merespon</option>
                                            <option value="Alasan lain" {{ old('reason') == 'Alasan lain' ? 'selected' : '' }}>Alasan lain</option>
                                        </select>
                                        @error('reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="message" class="form-label"><strong>Pesan (Opsional):</strong></label>
                                        <textarea class="form-control" name="message" id="message" rows="3">{{ old('message') }}</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')"><i class="bi bi-x-circle-fill me-1"></i> Batalkan</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted text-center">Belum ada riwayat pesanan.</p>
        @endforelse

        <!-- Paginasi -->
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
                            <li class="page-item {{ $page == $orders->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach
                        <li class="page-item {{ $orders->hasMorePages() ? '' : 'disabled' }}">
                            <a class="page-link" href="{{ $orders->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        @endif
    </div>
@endsection