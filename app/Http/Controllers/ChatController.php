<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Models\chat;
use App\Models\trx;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    
    public function buyerChat($trx){

        $chats = chat::where('trx_id', $trx)->first();

        $allMessages = [];

        $seller_search = trx::where('trx_id', $trx)->first();

        $sellers = User::where('email', $seller_search['seller_email'])->first();

// dd(Auth::user());

       

        if (is_null($chats)) {
            $chat = new chat();
            $chat->buyer_message = [];
            $chat->buyer_email = Auth::user()->email;
            $chat->seller_email = $seller_search['seller_email'];
            $chat->seller_message = [];
            $chat->message = '[]';
            $chat->trx_id = $trx;
            $chat->save();

            

            return view('chat', compact('chat', 'allMessages', 'sellers'));
        }
        else{

        if(Auth::user()->email != $chats['buyer_email']){
            dd($chats);
            redirect()->route('index');
        }
        else{
        // return dd($chat);
        $chat = chat::find($chats->id);

        if (!$chat) {
            abort(404);
        }

        // Decode JSON secara eksplisit
        $buyerMessages = json_decode(json_encode($chat->buyer_message), true);
        $sellerMessages = json_decode(json_encode($chat->seller_message), true);

        // Urutkan pesan
        
        if ($chat->buyer_message) {
            foreach ($chat->buyer_message as $message) {
                $message['sender'] = 'buyer'; // Tambahkan informasi pengirim
                $allMessages[] = $message;
            }
        }
        if ($chat->seller_message) {
            foreach ($chat->seller_message as $message) {
                $message['sender'] = 'seller'; // Tambahkan informasi pengirim
                $allMessages[] = $message;
            }
        }

        // Urutkan semua pesan berdasarkan waktu pengiriman
        usort($allMessages, function ($a, $b) {
            return strtotime($a['timestamp']) - strtotime($b['timestamp']);
        });
        // Encode JSON kembali
        $chat->buyer_message = $buyerMessages;
        $chat->seller_message = $sellerMessages;


        return view('chat', compact('chat', 'allMessages', 'sellers'));
    }
        }
    }




    public function sellerChat($trx){
        $chats = chat::where('trx_id', $trx)->first();

        $buyer_search = trx::where('trx_id', $trx)->first();

        $buyers = User::where('email', $buyer_search['buyer_email'])->first();
        

        $allMessages = [];


        if (is_null($chats)) {
            $chat = new chat();
            $chat->buyer_email = $buyers['email'];
            $chat->seller_email = Auth::user()->email;
            $chat->buyer_message = [];
            $chat->seller_message = [];
            $chat->message = '[]';
            $chat->trx_id = $trx;
            $chat->save();

            return view('seller.chat', compact('chat', 'allMessages', 'buyers'));
        }
        else{
            if(Auth::user()->email != $chats['seller_email']){
                redirect()->route('index');
            }
            else{
        // return dd($chat);
        $chat = chat::find($chats->id);

        if (!$chat) {
            abort(404);
        }

        // Decode JSON secara eksplisit
        $buyerMessages = json_decode(json_encode($chat->buyer_message), true);
        $sellerMessages = json_decode(json_encode($chat->seller_message), true);

        // Urutkan pesan
      
        if ($chat->buyer_message) {
            foreach ($chat->buyer_message as $message) {
                $message['sender'] = 'buyer'; // Tambahkan informasi pengirim
                $allMessages[] = $message;
            }
        }
        if ($chat->seller_message) {
            foreach ($chat->seller_message as $message) {
                $message['sender'] = 'seller'; // Tambahkan informasi pengirim
                $allMessages[] = $message;
            }
        }

        // Urutkan semua pesan berdasarkan waktu pengiriman
        usort($allMessages, function ($a, $b) {
            return strtotime($a['timestamp']) - strtotime($b['timestamp']);
        });
        // Encode JSON kembali
        $chat->buyer_message = $buyerMessages;
        $chat->seller_message = $sellerMessages;

        return view('seller.chat', compact('chat', 'allMessages', 'buyers'));
    }
        }
    }

    // public function sellerSend(Request $request){
    //     $chat = chat::where('trx_id', $request->trx)->first();

    //     $seller = json_decode($chat->seller_message, true);
    //     $seller[] = $request->message;
    //     $chat->seller_message = json_encode($seller);

    //     $messages = json_decode($chat->message, true);
    //     $messages[] = $request->message; 
    //     $chat->message = json_encode($messages); 
    //     $chat->save(); 
        
    //     return redirect()->back();
    // }

    // public function buyerSend(Request $request){
    //     $chat = chat::where('trx_id', $request->trx)->first();

    //     $buyer = json_decode($chat->buyer_message, true);
    //     $buyer[] = $request->message;
    //     $chat->buyer_message = json_encode($buyer);

    //     $messages = json_decode($chat->message, true);
    //     $messages[] = $request->message; 
    //     $chat->message = json_encode($messages); 
    //     $chat->save(); 
    //     dd($chat->message);
    // }



    public function sendMessage(Request $request)
{
    $message = Chat::where('trx_id',$request->trx)->firstOrFail();
    $user = Auth::user();
    $senderRole = $user->role;
    $newMessageContent = $request->message;
    $timestamp = now()->format('Y-m-d H:i:s'); // Terima timestamp dari request atau gunakan server time sebagai fallback

    if ($senderRole == 'buyer') {
        $buyerMessages = $message->buyer_message ?? [];
        $buyerMessages[] = [
            'message' => $newMessageContent,
            'timestamp' => $timestamp,
        ];
        $message->buyer_message = $buyerMessages;
    } else {
        $sellerMessages = $message->seller_message ?? [];
        $sellerMessages[] = [
            'message' => $newMessageContent,
            'timestamp' => $timestamp,
        ];
        $message->seller_message = $sellerMessages;
    }

    $message->save();

    // Broadcast event setelah pesan disimpan, gunakan timestamp yang sama
    broadcast(new NewChatMessage($request->trx, $senderRole, $newMessageContent, $timestamp))->toOthers();

    return response()->json(['status' => 'success', 'message' => 'Pesan terkirim']); // Kembalikan respons JSON
}

}
