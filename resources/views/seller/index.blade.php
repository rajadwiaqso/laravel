{{-- filepath: /home/rajadwiaqso/Desktop/market 4.0/resources/views/seller/index.blade.php --}}
@extends('layout')

@section('style')
    <link rel="stylesheet" href="{{asset('css/seller.css')}}">
    <style>
        .card {
            border-radius: 10px;
        }

        .card .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .card .badge {
            font-size: 1rem;
        }

        .btn-lg {
            font-size: 1.1rem;
        }
    </style>
@endsection

@section('konten')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Dashboard Seller</h2>
        <hr class="mb-4">

        <div class="row mb-4">
            <!-- Pesanan -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="bi bi-cart-fill text-primary me-2"></i> Pesanan</h5>
                        <p class="card-text">
                            <a href="{{ route('seller.orders') }}" class="text-decoration-none">
                                Diproses: <span class="badge bg-secondary">{{ $orders }}</span>
                            </a>
                        </p>
                        <p class="card-text">
                            <a href="{{ route('seller.confirmed') }}" class="text-decoration-none">
                                Konfirmasi: <span class="badge bg-info">{{ $confirmed }}</span>
                            </a>
                        </p>
                        <p class="card-text">
                            <a href="{{ route('seller.done') }}" class="text-decoration-none">
                                Selesai: <span class="badge bg-success">{{ $done }}</span>
                            </a>
                        </p>
                        <p class="card-text">
                            <a href="{{ route('seller.reject') }}" class="text-decoration-none">
                                Dibatalkan: <span class="badge bg-danger">{{ $reject }}</span>
                            </a>
                        </p>
                        <p class="card-text">
                            <a href="{{ route('seller.orders.all') }}" class="text-decoration-none">
                                Semua Pesanan: <span class="badge bg-primary">{{ $total }}</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Keuangan -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="bi bi-wallet-fill text-success me-2"></i> Keuangan</h5>
                        <p class="card-text">
                            Saldo Tertahan: <span class="badge bg-success">Rp. {{ number_format($seller->diproses) }}</span>
                        </p>
                        <p class="card-text">
                            Saldo Toko: <span class="badge bg-success">Rp. {{ number_format($seller->credits) }}</span>
                        </p>
                        <p class="card-text">
                            Total Terjual: <span class="badge bg-primary">{{ $seller->sold_total }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Produk -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="bi bi-box-seam-fill text-warning me-2"></i> Produk</h5>
                        <p class="card-text">
                            Aktif: <span class="badge bg-warning text-dark">{{ $seller->product_total }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="d-grid gap-2">
                    <a href="{{ route('product.create') }}" class="btn btn-primary btn-lg"><i class="bi bi-plus-square-fill me-2"></i> Buat Produk Baru</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-grid gap-2">
                    <a href="{{ route('product.list') }}" class="btn btn-outline-secondary btn-lg"><i class="bi bi-list-ul me-2"></i> Daftar Produk</a>
                </div>
            </div>
        </div>

        <!-- Placeholder Grafik Penjualan -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-bar-chart-fill text-info me-2"></i> Grafik Penjualan</h5>
                        <canvas id="salesChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesData['labels']) !!},
                datasets: [{
                    label: 'Penjualan',
                    data: {!! json_encode($salesData['data']) !!},
                    backgroundColor: 'rgba(108, 99, 255, 0.2)',
                    borderColor: '#6c63ff',
                    borderWidth: 2,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Penjualan'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection