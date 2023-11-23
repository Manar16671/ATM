@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Transfer') }}</div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('transfer') }}">
                            @csrf

                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input id="amount" type="number" step="0.01" class="form-control" name="amount" required>
                            </div>

                            <div class="form-group">
                                <label for="recipient_email">Recipient Email</label>
                                <input id="recipient_email" type="email" class="form-control" name="recipient_email" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Transfer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection