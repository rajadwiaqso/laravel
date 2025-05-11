
<div class="container">
    <h2>Reset Password</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('failed'))
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
    @endif
</div>
