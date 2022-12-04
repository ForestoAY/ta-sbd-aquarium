@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Fish Deleted</h2>
            </div>
            <div class="my-3 col-12 col-sm-8 col-md-5">
                <form action="" method="get">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Keyword" name = "keyword" aria-label="Keyword" aria-describedby="basic-addon1">
                        <button class="input-group-text btn btn-primary" id="basic-addon1">Search</button>
                    </div>
                </form>
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
            <th>ID Fish</th>
            <th>Fish Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>ID Supplier</th>
            <th>ID Store</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($fishes as $fish)
        <tr>
            <td>{{ $fish->fishid }}</td>
            <td>{{ $fish->fishname }}</td>
            <td>{{ $fish->price }}</td>
            <td>{{ $fish->stock }}</td>
            <td>{{ $fish->supplierid }}</td>
            <td>{{ $fish->storeid }}</td>
            <td>
                <form>
                    @can('fish-delete')
                    <a class="btn btn-primary" href="trash/{{ $fish ->fishid }}/restore">Restore</a>
                    @endcan
                    @csrf
                    @can('fish-delete')
                    <a class="btn btn-danger" href="trash/{{ $fish->fishid }}/forcedelete" onclick="return confirm('Are you sure?')">Force Delete</a>
                    @endcan
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $fishes->links() !!}
@endsection