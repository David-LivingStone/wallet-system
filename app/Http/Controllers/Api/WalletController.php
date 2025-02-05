<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use Illuminate\Http\Request;
use App\Models\WalletType;
use App\Models\UserWallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function index(){
        $wallets = WalletType::get();
        if($wallets->count() > 0){
            return WalletResource::collection($wallets);
        }else{
            return response()->json([
                'message' => 'No Wallets Found'
            ], 200);
        }

    }

    public function store(Request $request){

        $validtor = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'min_balance' => 'required',
            'monthly_interest_rate' => 'required',
        ]);

        if ($validtor->fails()){
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $validtor->messages()
            ], 422);
        };

        $wallet = WalletType::create([
            'name' => $request->name,
            'min_balance' => $request->min_balance,
            'monthly_interest_rate' => $request->monthly_interest_rate,
        ]);

        return response()->json([
            'message' => 'Wallet Created Successfully',
            'data' => new WalletResource($wallet)
        ], 200);
    }

    public function createWallet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wallet_type_id' => 'required|exists:wallet_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->messages()
            ], 422);
        }
        
        $user = $request->user();

        $walletType = WalletType::find($request->wallet_type_id);
        if (!$walletType) {
            return response()->json([
                'message' => 'Wallet type not found',
            ], 404);
        }

        // Create the wallet for the user
        $userWallet = $user->wallets()->create([
            'wallet_type_id' => $walletType->id,
            'balance' => 0.00, // Initial balance
        ]);

        return response()->json([
            'message' => 'Wallet created successfully',
            'wallet' => $userWallet
        ], 201);
    }

    
    public function getWallets()
    {
        return response()->json(UserWallet::with(['user', 'walletType'])->get());
    }

    
    public function show($id)
    {
        $wallet = UserWallet::with(['user', 'walletType'])->find($id);
        if (!$wallet) {
            return response()->json(['error' => 'Wallet not found'], 404);
        }
        return response()->json($wallet);
    }

     
     public function creditWallet(Request $request)
     {
         $request->validate([
             'sender_wallet_id' => 'required|exists:user_wallets,id',
             'receiver_wallet_id' => 'required|exists:user_wallets,id|different:sender_wallet_id',
             'amount' => 'required|numeric|min:1',
         ]);
 
        // Get the authenticated user
        $user = $request->user();

        // Ensure the sender's wallet belongs to the authenticated user
        $senderWallet = $user->wallets()->where('id', $request->sender_wallet_id)->first();
        if (!$senderWallet) {
            return response()->json(['error' => 'Unauthorized access to wallet'], 403);
        }

        // Get the receiver's wallet
        $receiverWallet = UserWallet::find($request->receiver_wallet_id);
        $amount = $request->amount;
 
         if ($senderWallet->balance < $amount) {
             return response()->json(['error' => 'Insufficient funds'], 400);
         }
 
         // Deduct from sender
         $senderWallet->balance -= $amount;
         $senderWallet->save();
 
         // Add to receiver
         $receiverWallet->balance += $amount;
         $receiverWallet->save();
 
         // Record transaction
         Transaction::create([
             'sender_wallet_id' => $senderWallet->id,
             'receiver_wallet_id' => $receiverWallet->id,
             'amount' => $amount,
         ]);
 
         return response()->json(['message' => 'Transaction successful']);
     }





}
