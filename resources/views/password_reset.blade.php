
<div class="container">
    <h2>Reset Password</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        

         <div class="alert alert-warning">
        Harap tunggu <span id="countdown">{{ session('wait_time') }}</span> detik sebelum mengirim ulang.
    </div>
    @endif
    @if (session('failed'))
        <div class="alert alert-danger">{{ session('failed') }}</div>
        
    @endif

    @if(!session('email'))

    <form action="{{ route('password.reset.sendOtp') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Send OTP</button>
    </form>
    @else

    <form action="{{ route('password.reset') }}" method="POST" class="mt-4">
        @csrf
        <div class="form-group">
            <label for="verification_code">OTP:</label>
            <input type="text" name="verification_code" id="verification_code" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
      
    
        <button type="submit" class="btn btn-success">Reset Password</button>
    </form>
      <form action="{{route('password.reset.again')}}" method="post">
            @csrf 
            
           <button id="resendBtn" {{ session('wait_time') > 0 ? 'disabled' : ''}}>Kirim Ulang</button>
            
        </form>
    @endif
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
