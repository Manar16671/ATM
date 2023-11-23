<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
       $user = auth()->user();
        return view('atm.dashboard', compact('user'));
    }

    public function depositForm()
    {
        return view('atm.deposit');
    }

public function deposit(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'amount' => 'required|numeric|min:0.01',
    ]);

    $receiptId = uniqid('receipt_');

    $user->balance += $request->input('amount');
    $user->save();
    Transaction::create([
        'user_id' => $user->id,
        'type' => 'deposit',
        'amount' => $request->input('amount'),
        'receipt_id' => $receiptId,
    ]);

    return redirect()->route('dashboard.receipt', ['receiptId' => $receiptId])->with('success', 'Deposit successful');
}



    public function transferForm()
    {
        return view('atm.transfer');
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'recipient_email' => 'required|email|exists:users,email',
        ]);
    
        $sender = auth()->user();
        $recipient = User::where('email', $request->input('recipient_email'))->first();
    
        if (!$recipient) {
            return redirect()->route('dashboard')->with('error', 'Recipient not found');
        }
    
        // No need to check for insufficient funds here, as it's handled by the middleware
    
        $receiptId = uniqid('receipt_');
    
        $sender->balance -= $request->input('amount');
        $recipient->balance += $request->input('amount');
    
        $sender->save();
        $recipient->save();
    
        $senderTransaction = Transaction::create([
            'user_id' => $sender->id,
            'type' => 'transfer',
            'amount' => $request->input('amount'),
            'recipient_id' => $recipient->id,
            'receipt_id' => $receiptId, 
        ]);
    
        $recipientTransaction = Transaction::create([
            'user_id' => $recipient->id,
            'type' => 'received',
            'amount' => $request->input('amount'),
            'recipient_id' => $sender->id,
            'receipt_id' => $receiptId, 
        ]);
    
        return redirect()->route('dashboard.receipt', ['receiptId' => $receiptId])->with('success', 'Transfer successful');
    }
    

    
    public function withdrawalForm()
    {
        return view('atm.withdrawal');
    }

    public function withdrawal(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'amount' => 'required|numeric|min:0.01',
    ]);

    $withdrawalAmount = $request->input('amount');

    $receiptId = uniqid('receipt_');

    $user->balance -= $withdrawalAmount;
    $user->save();

    Transaction::create([
        'user_id' => $user->id,
        'type' => 'withdrawal',
        'amount' => $withdrawalAmount,
        'receipt_id' => $receiptId,
    ]);

    return redirect()->route('dashboard.receipt', ['receiptId' => $receiptId])->with('success', 'Withdrawal successful');
}

    
    public function receipt($receiptId)
{
    $transaction = Transaction::where('receipt_id', $receiptId)->first();

    if (!$transaction) {
        abort(404); 
    }

    return view('atm.receipt', compact('transaction'));
}


}