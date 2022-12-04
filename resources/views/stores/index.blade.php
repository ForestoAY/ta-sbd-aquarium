@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Store</h2>
            </div>
            <div class="pull-right">
                @can('fish-create')
                <a class="btn btn-success" href="{{ route('stores.create') }}"> Create New Store</a>
                @endcan
                @can('fish-delete')
                <a class="btn btn-info" href = "/stores/trash">Deleted Data</a>
                @endcan   
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
            <th>ID Store</th>
            <th>Store Name</th>
            <th>Phone</th>
            <th>Location</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($stores as $store)
        <tr>
            <td>{{ $store->storeid }}</td>
            <td>{{ $store->storename }}</td>
            <td>{{ $store->phone }}</td>
            <td>{{ $store->location }}</td>
            <td>
                <form action="{{ route('stores.destroy',$store->storeid) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('stores.show',$store->storeid) }}">Show</a>
                    @can('fish-edit')
                    <a class="btn btn-primary" href="{{ route('stores.edit',$store->storeid) }}">Edit</a>
                    @endcan
                    @csrf
                    @method('DELETE')
                    @can('fish-delete')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    @endcan             
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $stores->links() !!}
@endsection