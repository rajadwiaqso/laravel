<?php

namespace App\Http\Controllers;

use App\Models\chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    
    public function buyerChat($buyer, $seller, $trx){

        $chats = chat::where('buyer_email', $buyer)->where('seller_email', $seller)->where('trx_id', $trx)->first();

        $allMessages = [];

        $sellers = User::where('email', $seller)->first();

        if (is_null($chats)) {
            $chat = new chat();
            $chat->buyer_email = $buyer;
            $chat->seller_email = $seller;
            $chat->buyer_message = [];
            $chat->seller_message = [];
            $chat->message = '[]';
            $chat->trx_id = $trx;
            $chat->save();

            return view('chat', compact('chat', 'allMessages', 'sellers'));
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




    public function sellerChat($buyer, $seller, $trx){
        $chats = chat::where('buyer_email', $buyer)->where('seller_email', $seller)->where('trx_id', $trx)->first();

        $buyers = User::where('email', $buyer)->first();
        

        $allMessages = [];


        if (is_null($chats)) {
            $chat = new chat();
            $chat->buyer_email = $buyer;
            $chat->seller_email = $seller;
            $chat->buyer_message = [];
            $chat->seller_message = [];
            $chat->message = '[]';
            $chat->trx_id = $trx;
            $chat->save();

            return view('chat', compact('chat', 'allMessages', 'buyers'));
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
        $message = chat::where('trx_id',$request->trx)->first();

        if ($request->role == 'buyer') {
            $buyerMessages = $message->buyer_message ?? [];
            $buyerMessages[] = [
                'message' => $request->message,
                'timestamp' => now()->format('Y-m-d H:i:s'), // Simpan waktu pengiriman
            ];
            $message->buyer_message = $buyerMessages;
        } else {
            $sellerMessages = $message->seller_message ?? [];
            $sellerMessages[] = [
                'message' => $request->message,
                'timestamp' => now()->format('Y-m-d H:i:s'), // Simpan waktu pengiriman
            ];
            $message->seller_message = $sellerMessages;
        }

        $message->save();

        return redirect()->back();
    }

}
