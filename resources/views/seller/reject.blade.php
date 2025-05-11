@extends('layout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/rating.css')}}">
    <style>
        .pesanan-pembeli, .pesanan-penjual {
            display: none; /* Sembunyikan secara default */
        }
        .active-tab {
            font-weight: bold;
            border-bottom: 2px solid #007bff; /* Contoh penanda tab aktif */
        }
    </style>
@endsection
@section('konten')
    <div class="container py-5">
        <h2>Pesanan Dibatalkan</h2>
        {{-- Tombol untuk memilih tampilan --}}
        <span class="btn btn-link tab-button active-tab" data-target="pembeli">Dari Pembeli</span>
        <span class="btn btn-link tab-button" data-target="penjual">Dari Penjual</span>
        <hr class="mb-4">

        {{-- Daftar pesanan dari pembeli --}}
        <div class="pesanan-pembeli">
            @forelse ($reject as $item)
                @if ($item->buyer_email != $item->status_date['from'])
                    
                    @elseif($item->status == 'confirm' || $item->status == 'done')
                        
                    @else
                
                    <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <h6 class="mb-0">Pembeli:</h6>
                                <p class="mb-0">{{ $item->name }}</p> {{-- Asumsi ada kolom nama penjual --}}
                            </div>
                            <div class="col-md-2">
                                <h6 class="mb-0">Produk:</h6>
                                <p class="mb-0">{{ $item->product }}</p>
                            </div>
                            <div class="col-md-2">
                                <h6 class="mb-0">Harga:</h6>
                                <p class="mb-0">Rp. {{ number_format($item->price) }}</p>
                            </div>
                            <div class="col-md-1">
                                <h6 class="mb-0">Kategori:</h6>
                                <p class="mb-0">{{ $item->category }}</p>
                            </div>
                            <div class="col-md-2">
                                <h6 class="mb-0">Status:</h6>
                                <span class="badge bg-danger">{{ ucfirst($item->status) }}</span>
                            </div>

                            


                            

                            <div class="modal fade" id="detailreject{{ $item->id }}" tabindex="-1" aria-labelledby="detailrejectLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailrejectLabel{{ $item->id }}">Detail Pesanan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Pembeli:</strong> {{ $item->name }}</p> {{-- Asumsi ada kolom nama penjual --}}
                                            <p><strong>Produk:</strong> {{ $item->product }}</p>
                                            <p><strong>Harga:</strong> Rp. {{ number_format($item->price) }}</p>
                                            <p><strong>Kategori:</strong> {{ $item->category }}</p>
                                            <p><strong>ID Pesanan:</strong> {{ $item->trx_id }}</p>
                                            <p><strong>ID Product:</strong> {{ $item->id_product }}</p>
                                            <p><strong>Status:</strong> {{ ($item->status == 'pending') ? ucfirst($item->status) . ' '. '(akan dibatalkan)' : ucfirst($item->status)}} </p>
                                            <p><strong>Tanggal Pemesanan:</strong> {{ $item->created_at }}</p>
                                            <p><strong>Tanggal Pembatalan:</strong> {{ $item->status_date['reject'] ?? ""}}</p>
                                            <p><strong>Alasan Pembatalan:</strong> {{ $item->status_date['reason'] ?? "tidak ada alasan"}}</p>
                                            <p><strong>Pesan Pembatalan:</strong> {{ $item->status_date['message'] ?? "tidak ada pesan"}}</p>
                                            @if (isset($item->status_date['seller_message']))
                                            <p><strong>Pesan Penjual:</strong> {{ $item->status_date['seller_message'] ?? "tidak ada pesan"}}</p>    
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

                                @if (!isset($item->status_date['seller_message']))
                                

                                 <span class="btn btn-sm btn-success text-light" data-bs-toggle="modal" data-bs-target="#beriTanggapan{{ $item->id }}">
                                    Beri Tanggapan
                                </span>

                                @endif

                                <span class="btn btn-sm btn-info text-light" data-bs-toggle="modal" data-bs-target="#detailreject{{ $item->id }}">
                                    Details
                                </span>
                                <a href="{{ route('seller.chat', $item->trx_id) }}" class="btn btn-sm btn-primary"><i class="bi bi-chat-dots-fill me-1"></i> Chat</a> {{-- Mungkin perlu penyesuaian rute chat --}}
                            </div>
                        </div>
                    </div>
                </div>

                    
                <div class="modal fade" id="beriTanggapan{{ $item->id }}" tabindex="-1" aria-labelledby="detailberiTanggapan{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailberiTanggapan{{ $item->id }}">Detail Pesanan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="myForm{{$item->id}}" action="" method="post">
                                    @csrf
                                    <p><strong>Pembeli:</strong> {{ $item->name }}</p>
                                    <p><strong>Nama Produk:</strong> {{ $item->product }}</p>
                                    <p><strong>Harga:</strong> Rp. {{ number_format($item->price) }}</p>
                                    <p><strong>Kategori:</strong> {{ $item->category }}</p>
                                    <p><strong>ID Pesanan:</strong> {{ $item->trx_id }}</p>
                                    <p><strong>Status:</strong> Akan dibatalkan</p>
                                    <p><strong>Pesan:</strong> <input type="text" name="message" id=""></p>
                                    
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="button" class="btn btn-danger" id="terimaSaranBtn{{$item->id}}">Terima Saran</button>
                                        <button type="button" class="btn btn-success" id="tolakSaranBtn{{$item->id}}">Tolak Saran</button>
                                    </div>
                                </form>
                                {{--  --}}

                                            <script>
                                                document.getElementById('terimaSaranBtn{{$item->id}}').addEventListener('click', function() {
                                                    document.getElementById('myForm{{$item->id}}').action = '{{ route('terima.saran', $item->trx_id) }}';
                                                    document.getElementById('myForm{{$item->id}}').submit();
                                                });

                                                document.getElementById('tolakSaranBtn{{$item->id}}').addEventListener('click', function() {
                                                    document.getElementById('myForm{{$item->id}}').action = '{{ route('tolak.saran', $item->trx_id) }}';
                                                    document.getElementById('myForm{{$item->id}}').submit();
                                                });
                                            </script>
                                
                            {{--  --}}
                            </div>
                        </div>
                    </div>
                </div>


                
                @endif
                

            @empty
                <p class="text-muted">Belum ada pesanan yang dibatalkan oleh pembeli.</p>
            @endforelse

            
        </div>
        {{-- Daftar pesanan dari penjual --}}
        <div class="pesanan-penjual">
            @forelse ($done as $confirm)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <h6 class="mb-0">Pembeli:</h6>
                                <p class="mb-0">{{ $confirm->name}}</p>
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
                                <span class="badge bg-danger">{{ ucfirst($confirm->status) }}</span>
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
                                            <p><strong>Tanggal Penolakan:</strong> {{ $confirm->status_date['reject'] ?? ""}}</p>
                                            <p><strong>Alasan Penolakan:</strong> {{ $confirm->status_date['reason'] ?? "tidak ada alasan"}}</p>
                                            <p><strong>Pesan Penolakan:</strong> {{ $confirm->status_date['message'] ?? "tidak ada pesan"}}</p>
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
                            </div>
                        </div>

                        @if (!is_null($confirm->rating))
                            @if(!is_int($confirm->rating))
                                <div class="mt-3 text-end">
                                    <div class="rating">
                                        @for ($i = 0; $i < $confirm->rating['rating']; $i++)
                                            <span class="star active">&#9733;</span>
                                        @endfor
                                        @for ($i = $confirm->rating['rating']; $i < 5; $i++)
                                            <span class="star">&#9733;</span>
                                        @endfor
                                    </div>
                                    <p class="mb-0"><small class="text-muted">Pesan: {{ $confirm->rating['message'] }}</small></p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-muted">Belum ada pesanan yang dibatalkan oleh penjual.</p>
            @endforelse

          
        </div>
    </div>
@endsection

@section('script')
    <script>
        const tabButtons = document.querySelectorAll('.tab-button');
        const pesananPembeli = document.querySelector('.pesanan-pembeli');
        const pesananPenjual = document.querySelector('.pesanan-penjual');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const target = this.dataset.target;

                // Hapus kelas aktif dari semua tombol
                tabButtons.forEach(btn => btn.classList.remove('active-tab'));
                // Tambahkan kelas aktif ke tombol yang diklik
                this.classList.add('active-tab');

                // Sembunyikan semua daftar pesanan
                pesananPembeli.style.display = 'none';
                pesananPenjual.style.display = 'none';

                // Tampilkan daftar pesanan yang sesuai
                if (target === 'pembeli') {
                    pesananPembeli.style.display = 'block';
                } else if (target === 'penjual') {
                    pesananPenjual.style.display = 'block';
                }
            });
        });

        // Aktifkan tab berdasarkan parameter URL jika ada
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        if (tabParam === 'penjual') {
            tabButtons.forEach(btn => {
                if (btn.dataset.target === 'penjual') {
                    btn.click();
                }
            });
        } else {
            // Secara default, tampilkan tab pembeli
            tabButtons.forEach(btn => {
                if (btn.dataset.target === 'pembeli') {
                    btn.classList.add('active-tab');
                } else {
                    btn.classList.remove('active-tab');
                }
            });
            pesananPembeli.style.display = 'block';
            pesananPenjual.style.display = 'none';
        }
    </script>

@endsection