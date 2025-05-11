@extends('layout')

@section('style')
    <link rel="stylesheet" href="{{asset('css/chat.css')}}">
@endsection

@section('konten')
    <div class="container py-5">
        <h2>Chat dengan {{ $buyers->name }}</h2>
        <hr class="mb-4">

        <div id="chat-messages" class="chat-container shadow-sm rounded p-3 mb-3" style="height: 400px; overflow-y: auto;">
            @if ($allMessages)
                @foreach ($allMessages as $message)
                    <div class="d-flex mb-2 {{ $message['sender'] == 'buyer' ? 'justify-content-start' : 'justify-content-end' }}">
                        <div class="message-box rounded p-2 {{ $message['sender'] == 'buyer' ? 'bg-light text-dark' : 'bg-primary text-white' }}" style="max-width: 70%;">
                            <p class="mb-0">{{ $message['message'] }}</p>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($message['timestamp'])->format('d M Y, H:i') }}</small>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Belum ada pesan.</p>
            @endif
        </div>

        <form class="message-form" action="{{ route('seller.chat.send') }}" method="post">
            @csrf
            <input type="hidden" name="trx" value="{{ $chat->trx_id }}">
            <input type="hidden" name="role" value="{{ Auth::user()->role }}">
            <div class="input-group">
                <textarea class="form-control" name="message" rows="3" placeholder="Ketik pesan..." required></textarea>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill"></i> Kirim</button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>

    
   
document.addEventListener('DOMContentLoaded', () => {
    const chatContainer = document.getElementById('chat-messages');
    const trxId = "{{ $chat->trx_id }}";
    const currentUserRole = "{{ Auth::user()->role }}";
    const messageForm = document.querySelector('.message-form');
    const messageInput = document.querySelector('textarea[name="message"]');

    // Fungsi untuk menambahkan pesan ke tampilan
    function addMessageToChat(messageData, isLocal = false) {
    const existingMessage = document.querySelector(`.message-box[data-timestamp="${messageData.timestamp}"]`);
    if (!existingMessage) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('d-flex', 'mb-2', messageData.sender === currentUserRole ? 'justify-content-end' : 'justify-content-start');
        const messageBox = document.createElement('div');
        messageBox.classList.add('message-box', 'rounded', 'p-2'); // Hapus kelas warna awal di sini
        messageBox.style.maxWidth = '70%';
        messageBox.dataset.timestamp = messageData.timestamp;
        const messageParagraph = document.createElement('p');
        messageParagraph.classList.add('mb-0');
        messageParagraph.textContent = messageData.message;
        const timestampSmall = document.createElement('small');
        timestampSmall.classList.add('text-muted');
        const formattedTime = new Date(messageData.timestamp).toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: 'numeric', minute: 'numeric' });
        timestampSmall.textContent = formattedTime;

        // Tambahkan kelas warna berdasarkan pengirim
        if (messageData.sender === currentUserRole) {
            messageBox.classList.add('bg-primary', 'text-white');
        } else {
            messageBox.classList.add('bg-light', 'text-dark');
        }

        messageBox.appendChild(messageParagraph);
        messageBox.appendChild(timestampSmall);
        messageDiv.appendChild(messageBox);
        chatContainer.appendChild(messageDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
}

    // Listen untuk pesan baru dari WebSocket
    window.Echo.channel(`chat.${trxId}`)
        .listen('.chat.message.new', (e) => {
            console.log('Pesan baru diterima:', e);
            addMessageToChat(e);
        });

    // Tangani pengiriman formulir
    messageForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const message = messageInput.value;
        const trx = this.querySelector('input[name="trx"]').value;
        const role = this.querySelector('input[name="role"]').value;
        const timestamp = new Date().toISOString(); // Gunakan ISO string untuk konsistensi

        // Tambahkan pesan ke tampilan secara lokal
        addMessageToChat({ sender: role, message: message, timestamp: timestamp }, true);
        messageInput.value = ''; // Bersihkan input

        // Kirim pesan ke server menggunakan Fetch API
        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
            },
            body: `trx=${encodeURIComponent(trx)}&role=${encodeURIComponent(role)}&message=${encodeURIComponent(message)}&timestamp=${encodeURIComponent(timestamp)}` // Kirim juga timestamp
        })
        .then(response => response.json())
        .then(data => {
            console.log('Pesan berhasil dikirim:', data);
            // Tidak perlu menambahkan pesan lagi di sini, WebSocket akan menanganinya
        })
        .catch(error => {
            console.error('Error mengirim pesan:', error);
            // Mungkin perlu menghapus pesan lokal jika pengiriman gagal
        });
    });

    // Scroll ke bagian bawah chat saat halaman pertama kali dimuat
    chatContainer.scrollTop = chatContainer.scrollHeight;
});
        
    </script>
@endsection