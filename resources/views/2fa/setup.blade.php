@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Authentication Two Factors configuration</h2>

    <p>Scan the QR Code down below with Google Authenticator</p>
    <div>{!! $qrCode !!}</div>

    <p>Or put the code here: <strong>{{ $secret }}</strong></p>

    <form action="{{ route('2fa.enable') }}" method="post">
        @csrf
        <label for="code">Enter the code app:</label>
        <input type="text" name="code" id="code" required>

        <button type="submit">Active 2FA</button>
    </form>
</div>
@endsection