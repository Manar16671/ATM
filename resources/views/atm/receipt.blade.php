@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Transaction Receipt') }}</div>
                 
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                    <div class="card-body">
                        <h3>Transaction Details</h3>

                        <p><strong>Receipt ID:</strong> {{ $transaction->receipt_id }}</p>
                        <p><strong>Type:</strong> {{ $transaction->type }}</p>
                        <p><strong>Amount:</strong> ${{ $transaction->amount }}</p>
                        <p><strong>Date:</strong> {{ $transaction->created_at->format('Y-m-d H:i:s') }}</p>

                        @if ($transaction->type == 'transfer')
                            <p><strong>Recipient:</strong> {{ $transaction->recipient->name }}</p>
                        @endif

                        <p><a href="{{ route('dashboard') }}">Back to Dashboard</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
