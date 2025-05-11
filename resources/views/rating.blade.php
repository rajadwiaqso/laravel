@extends('layout')
@section('style')
    <link rel="stylesheet" href="{{asset('css/rating.css')}}">
@endsection
@section('konten')
    <div class="container my-5">
        <h2>Berikan Rating untuk Pesanan Anda</h2>
        <hr class="mb-4">

        <div class="card shadow-sm p-4">
            <h5 class="mb-3">Produk: {{ $data->product }} ({{ $data->category }})</h5>
            <p class="mb-3">Harga: Rp. {{ number_format($data->price) }}</p>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('buyer.rating.post', $data->trx_id) }}" method="post">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Rating Anda:</label>
                    <div class="rating">
                        <span class="star" data-value="1">&#9733;</span>
                        <span class="star" data-value="2">&#9733;</span>
                        <span class="star" data-value="3">&#9733;</span>
                        <span class="star" data-value="4">&#9733;</span>
                        <span class="star" data-value="5">&#9733;</span>
                    </div>
                    <input type="hidden" name="rating" id="rating-value">
                    @error('rating')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Pesan Ulasan (Opsional):</label>
                    <textarea class="form-control" id="message" name="message" rows="4"></textarea>
                    @error('message')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Kirim Rating</button>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const stars = document.querySelectorAll('.star');
        const ratingValueInput = document.getElementById('rating-value');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = parseInt(this.getAttribute('data-value'));
                ratingValueInput.value = value;

                stars.forEach(s => s.classList.remove('active'));
                for (let i = 0; i < value; i++) {
                    stars[i].classList.add('active');
                }
            });

            star.addEventListener('mouseover', function() {
                const value = parseInt(this.getAttribute('data-value'));
                stars.forEach(s => s.classList.remove('hover'));
                for (let i = 0; i < value; i++) {
                    stars[i].classList.add('hover');
                }
            });

            star.addEventListener('mouseout', function() {
                stars.forEach(s => s.classList.remove('hover'));
                const currentValue = parseInt(ratingValueInput.value);
                if (currentValue > 0) {
                    for (let i = 0; i < currentValue; i++) {
                        stars[i].classList.add('active');
                    }
                }
            });
        });

        // Set initial active stars if there's a default value (optional)
        const initialRating = parseInt(ratingValueInput.value);
        if (initialRating > 0) {
            for (let i = 0; i < initialRating; i++) {
                stars[i].classList.add('active');
            }
        }
    </script>
@endsection