@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mt-5">User Invite Token</h1>

    <form action="{{ route('generate.token') }}" method="POST" class="my-3">
        @csrf
        <div class="row">
            <span class="col-10">

                <div class="form-group">
                    {{-- <label for="email">Email Address:</label> --}}
                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter Email Address">
                </div>
            </span>
            <span class="col-2">
                <button type="submit" class="btn btn-primary">Generate Token</button>
            </span>
        </div>
    </form>
    <div class="row">

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Sr</th>
                    <th>Email</th>
                    <th>Token</th>
                    <th>Expiry</th>
                    <th>Used</th>
                    {{-- <th>Action</th> --}}
                </tr>
            </thead>
            <tbody class="">
                @foreach ($userTokens as $key => $token)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $token->email }}</td>
                    <td>{{ $token->token }}</td>
                    <td>{{ $token->expiry }}</td>
                    <td>{{ ($token->is_used ? 'YES' : 'NO') }}</td>
                    {{-- <td><button class="btn btn-sm btn-warning">Update</button></td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
