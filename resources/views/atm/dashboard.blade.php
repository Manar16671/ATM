@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('ATM') }}</div>
                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    
                @endif
                    <div class="card-body">

                        @if ($user)
                            <h1>Welcome, <b style="color: #ec82b2;">{!! $user->name !!}</b></h1>

                            <p>{{__('Your balance: $') }}{{ $user->balance }}</p>
                        @else
                            <p>Unable to retrieve user information.</p>
                        @endif

                        <h2>Services:</h2>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <form action="{{ route('deposit.form') }}" method="GET">
                                    @csrf
                                    <button style="color:#ec82b2" type="submit" class="btn btn-link">Deposit</button>
                                </form>
                            </li>
                            <li class="list-group-item">
                                <form action="{{ route('transfer.form') }}" method="GET">
                                    @csrf
                                    <button style="color:#ec82b2 " type="submit" class="btn btn-link">Transfer</button>
                                </form>
                            </li>
                            <li class="list-group-item">
                                <form action="{{ route('withdrawal.form') }}" method="GET">
                                    @csrf
                                    <button style="color:#ec82b2 "type="submit" class="btn btn-link ">Withdrawal</button>
                                </form>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection