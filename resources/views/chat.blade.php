{{-- filepath: /home/rajadwiaqso/Desktop/market 4.0/resources/views/chat.blade.php --}}
@extends('layout')

@section('style')
    <link rel="stylesheet" href="{{asset('css/chat.css')}}">
    <style>
        .chat-container {
            height: 400px;
            overflow-y: auto;
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
        }

        .message-box {
            border-radius: 15px;
            padding: 10px 15px;
            max-width: 70%;
        }

        .message-box.buyer {
            background-color: #6c63ff;
            color: white;
            align-self: flex-end;
        }

        .message-box.seller {
            background-color: #e9ecef;
            color: #333;
            align-self: flex-start;
        }

        .input-group textarea {
            resize: none;
        }
    </style>
@endsection

@section('konten')
    <div class="container py-5">
        <h2>Chat dengan {{ $sellers->name }}</h2>
        <hr class="mb-4">

        <!-- Chat Messages -->
        <div id="chat-messages" class="chat-container shadow-sm">
            @if ($allMessages)
                @foreach ($allMessages as $message)
                    <div class="d-flex mb-2 {{ $message['sender'] == 'buyer' ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="message-box {{ $message['sender'] == 'buyer' ? 'buyer' : 'seller' }}">
                            <p class="mb-0">{{ $message['message'] }}</p>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($message['timestamp'])->format('d M Y, H:i') }}</small>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Belum ada pesan.</p>
            @endif
        </div>

        <!-- Chat Form -->
        <form class="message-form mt-3" action="{{ route('buyer.chat.send') }}" method="post">
            @csrf
            <input type="hidden" name="trx" value="{{ $chat->trx_id }}">
            <input type="hidden" name="role" value="{{ Auth::user()->role }}">
            <div class="input-group">
                <textarea class="form-control" name="message" rows="2" placeholder="Ketik pesan..." required></textarea>
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

        if (messageData.sender === currentUserRole && !isLocal) {
        return;
    }


    const existingMessage = document.querySelector(`.message-box[data-timestamp="${messageData.timestamp}"]`);
    if (existingMessage) return; // Jangan tambahkan jika pesan sudah ada

    const messageDiv = document.createElement('div');
    messageDiv.classList.add('d-flex', 'mb-2', messageData.sender === currentUserRole ? 'justify-content-end' : 'justify-content-start');
    const messageBox = document.createElement('div');
    messageBox.classList.add('message-box', messageData.sender === currentUserRole ? 'buyer' : 'seller');
    messageBox.dataset.timestamp = messageData.timestamp;
    const messageParagraph = document.createElement('p');
    messageParagraph.classList.add('mb-0');
    messageParagraph.textContent = messageData.message;
    const timestampSmall = document.createElement('small');
    timestampSmall.classList.add('text-muted');
    const formattedTime = new Date(messageData.timestamp).toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: 'numeric', minute: 'numeric' });
    timestampSmall.textContent = formattedTime;

    messageBox.appendChild(messageParagraph);
    messageBox.appendChild(timestampSmall);
    messageDiv.appendChild(messageBox);
    chatContainer.appendChild(messageDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

    // Listen untuk pesan baru dari WebSocket
   window.Echo.channel(`chat.${trxId}`)
    .listen('.chat.message.new', (e) => {
        console.log('Pesan baru diterima:', e);
        addMessageToChat(e); // Pastikan hanya menambahkan pesan jika belum ada
    });

    // Tangani pengiriman formulir
    messageForm.addEventListener('submit', function(event) {
    event.preventDefault();
    const message = messageInput.value.trim();
    if (!message) return; // Validasi input kosong
    const trx = this.querySelector('input[name="trx"]').value;
    const role = this.querySelector('input[name="role"]').value;
    const timestamp = new Date().toISOString();

    // Tambahkan pesan ke tampilan secara lokal hanya jika belum ada
    addMessageToChat({ sender: role, message: message, timestamp: timestamp }, true);
    messageInput.value = ''; // Bersihkan input

    // Kirim pesan ke server menggunakan Fetch API
    fetch(this.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
        },
        body: `trx=${encodeURIComponent(trx)}&role=${encodeURIComponent(role)}&message=${encodeURIComponent(message)}&timestamp=${encodeURIComponent(timestamp)}`
    })
    .then(response => response.json())
    .then(data => {
        console.log('Pesan berhasil dikirim:', data);
    })
    .catch(error => {
        console.error('Error mengirim pesan:', error);
    });
});

    // Scroll ke bagian bawah chat saat halaman pertama kali dimuat
    chatContainer.scrollTop = chatContainer.scrollHeight;
});
</script>
@endsection