<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\User;
use App\Models\Transaction;

class WalletController extends Controller
{
    public function rechargeWallet(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        
        $amount = $request->input('amount') * 100;

        $orderData = [
            'receipt'         => uniqid(),
            'amount'          => $amount,
            'currency'        => 'INR',
            'payment_capture' => 1,
        ];

        try {
            $order = $api->order->create($orderData);

            return view('payment.razorpay-checkout', [
                'order_id' => $order->id,
                'amount'   => $amount,
                'transaction_type'   => "recharge", 
                'key'      => env('RAZORPAY_KEY'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }


    public function transferMmount(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        
        $amount = $request->input('amount') * 100;

        $number = $request->input('number') ;
        $user = User::where('mobileno', $number)->first();
        if(!$user){
            return response()->json(['error' => 'User not found']);
        }


        $orderData = [
            'receipt'         => uniqid(),
            'amount'          => $amount,
            'currency'        => 'INR',
            'payment_capture' => 1,
        ];

        try {
            $order = $api->order->create($orderData);

            return view('payment.razorpay-checkout', [
                'order_id' => $order->id,
                'amount'   => $amount,
                'transaction_type'   => "transfer", 
                'user'   => $user->id, 
                'key'      => env('RAZORPAY_KEY'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }


    public function verifyPayment(Request $request)
    {
        $input = $request->all();
      
        $api = new \Razorpay\Api\Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            // Verify payment signature
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $input['order_id'],
                'razorpay_payment_id' => $input['payment_id'],
                'razorpay_signature' => $input['signature']
            ]);

            $transaction = new Transaction();
            $transaction->user_id = auth()->user()->id; 
            $transaction->amount = $input['amount'] / 100; 
            $transaction->transaction_type = $input['transaction_type'];
            $transaction->transaction_status =  'success';
            $transaction->transaction_id = $input['payment_id'];
            $transaction->save();

            if($input['user'] && $input['transaction_type']=="transfer"){
                $wallet_amount= auth()->user()->wallet_amount;
                $wallet_amount -= $input['amount'] / 100;
                auth()->user()->wallet_amount = $wallet_amount;
                auth()->user()->save();

                $user = User::where('id', $input['user'])->first();
                $user->wallet_amount += $input['amount'] / 100;
                $user->save();

            }else{
            $wallet_amount= auth()->user()->wallet_amount;
            $wallet_amount += $input['amount'] / 100;
            auth()->user()->wallet_amount = $wallet_amount;
            auth()->user()->save();
            }

            SendPaymentConfirmationEmail::dispatch(auth()->user()->email);


            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            $transaction = new Transaction();
            $transaction->user_id = auth()->user()->id;
            $transaction->amount = $input['amount'] / 100; 
            $transaction->transaction_type = $input['transaction_type'];
            $transaction->transaction_status =  'failed';
            $transaction->transaction_id = $input['payment_id'];
            $transaction->save();

            return response()->json(['status' => 'failed']);
        }
    }


    public function viewTransaction(Request $request)
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($transactions);
    }

}
