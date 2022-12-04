@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Supplier</h2>
            </div>
            <div class="pull-right">
                @can('fish-create')
                <a class="btn btn-success" href="{{ route('suppliers.create') }}"> Create New Supplier</a>
                @endcan
                @can('fish-delete')
                <a class="btn btn-info" href = "/suppliers/trash">Deleted Data</a>
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
            <th>ID Supplier</th>
            <th>Supplier Name</th>
            <th>SPhone</th>
            <th>SLocation</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($suppliers as $supplier)
        <tr>
            <td>{{ $supplier->supplierid }}</td>
            <td>{{ $supplier->suppliername }}</td>
            <td>{{ $supplier->sphone }}</td>
            <td>{{ $supplier->slocation }}</td>
            <td>
                <form action="{{ route('suppliers.destroy',$supplier->supplierid) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('suppliers.show',$supplier->supplierid) }}">Show</a>
                    @can('fish-edit')
                    <a class="btn btn-primary" href="{{ route('suppliers.edit',$supplier->supplierid) }}">Edit</a>
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
    {!! $suppliers->links() !!}
@endsection