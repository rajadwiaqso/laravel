

   
    <div class="container mt-5">
    
        <h1 class="text-center">Verify Your Email</h1>
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
    </div>
