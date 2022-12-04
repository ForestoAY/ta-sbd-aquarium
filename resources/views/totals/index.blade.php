@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>List View Join</h2>
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>Fish Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Supplier Name</th>
            <th>Store Name</th>
        </tr>
        @foreach ($joins as $join)
        <tr>
            <td>{{ $join->fishname }}</td>
            <td>{{ $join->price }}</td>
            <td>{{ $join->stock }}</td>
            <td>{{ $join->suppliername }}</td>
            <td>{{ $join->storename }}</td>
        </tr>
        @endforeach
    </table>
    {!! $joins->links() !!}
@endsection