@extends('layouts.app')

@section('content')
<div class="container">
    <h2>User Dashboard</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Date</th>
                <th>Product Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notes as $note)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $note->date }}</td>
                <td>{{ $note->product_name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection