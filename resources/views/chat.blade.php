@extends('layout')

@section('style')
    <link rel="stylesheet" href="{{asset('css/chat.css')}}">
@endsection

@section('konten')
<h1>Chat</h1>

    <div class="container py-5">



        <div class="chat-container">
            <div>
                @if ($allMessages)
                <h3>{{$sellers->name}}</h3>
                    @foreach ($allMessages as $message)
                        <div class="message {{ $message['sender'] }}-message">
                            @if ($message['sender'] == 'buyer')
                                You:                             {{ $message['message'] }}
                            
                            @else 
                            {{ $message['message'] }}
                            @endif
                            

                            <small>{{ $message['timestamp'] }}</small>
                        </div>
                    @endforeach
                @endif
            </div>
        
            <form class="message-form" action="{{route('buyer.chat.send')}}" method="post">
                @csrf
                 <input type="hidden" name="trx" id="" hidden value="{{$chat->trx_id}}">
              <input type="hidden" name="role" id="" value="{{Auth::user()->role}}">
                <textarea name="message"></textarea>
                <button type="submit">Kirim</button>
            </form>
        </div>


     
@endsection