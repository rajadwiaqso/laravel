
   
    <div class="container mt-5">
    
        <h1 class="text-center">Verify Your Email</h1>


@if (session('failed'))
    <div class="alert alert-danger mt-3">
        {{ session('failed') }}
    </div>
    
@endif

@if (session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
    
@endif

 @if(session('warning'))
        

         <div class="alert alert-warning">
        Harap tunggu <span id="countdown">{{ session('wait_time') }}</span> detik sebelum mengirim ulang.
    </div>
    @endif

        <form action="{{ route('verify.email') }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-3">

                

            </div>
            <div class="mb-3">
            <label for="verification_code" class="form-label">Verification Code:</label>
            <input type="text" name="verification_code" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Verify</button>
        </form>
        <form action="{{route('verify.email.resend')}}" method="post">
            @csrf 
            <button id="resendBtn" class="btn btn-primary mt-3" {{ session('wait_time') > 0 ? 'disabled' : ''}}>Resend Verification Code</button>
        </form>
    </div>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const countdownElement = document.getElementById('countdown');
        const resendButton = document.getElementById('resendBtn');
        let timeLeft = parseInt(countdownElement ? countdownElement.textContent : 0);

        if (timeLeft > 0) {
            const countdownInterval = setInterval(function() {
                timeLeft--;
                countdownElement.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    resendButton.removeAttribute('disabled');
                    countdownElement.textContent = '0'; // Atau teks lain jika perlu
                }
            }, 1000);

            // Optional: Nonaktifkan tombol kirim ulang saat hitungan mundur berjalan
            resendButton.setAttribute('disabled', true);
        }
    });
</script>
