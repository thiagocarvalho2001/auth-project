@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Verification</h2>
    <p>Enter the code generate by Google Authenticator:</p>

    <form action="{{ route('2fa.verify.post') }}" method="post">
        @csrf
        <input type="text" name="code" required>
        <button type="submit">Verify</button>
    </form>
</div>
@endsection