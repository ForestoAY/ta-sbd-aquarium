@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Store Deleted</h2>
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
                <form>
                    @can('fish-delete')
                    <a class="btn btn-primary" href="trash/{{ $store ->storeid }}/restore">Restore</a>
                    @endcan
                    @csrf
                    @can('fish-delete')
                    <a class="btn btn-danger" href="trash/{{ $store->storeid }}/forcedelete" onclick="return confirm('Are you sure?')">Force Delete</a>
                    @endcan
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $stores->links() !!}
@endsection