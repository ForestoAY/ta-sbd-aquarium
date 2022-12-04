<?php
    
namespace App\Http\Controllers;
    
use App\Models\Supplier;
use Illuminate\Http\Request;
use DB;
    
class SupplierController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:fish-list|fish-create|fish-edit|fish-delete', ['only' => ['index','show']]);
         $this->middleware('permission:fish-create', ['only' => ['create','store']]);
         $this->middleware('permission:fish-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:fish-delete', ['only' => ['destroy','deletelist']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $keyword = $request->keyword;
        // $suppliers = Supplier::where('suppliername','LIKE','%'.$keyword.'%')
        //             ->paginate(10);
        // return view('suppliers.index',compact('suppliers'))
        //     ->with('i', (request()->input('page', 1) - 1) * 10);
        $keyword = $request->keyword;
        $suppliers = DB::table('suppliers')->where('suppliername','LIKE','%'.$keyword.'%')
                    ->whereNull('deleted_at')
                    ->paginate(10);
        return view('suppliers.index',compact('suppliers'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('suppliers.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // request()->validate([
        //     'supplierid' => 'required',
        //     'suppliername' => 'required',
        //     'sphone' => 'required',
        //     'slocation' => 'required',
        // ]);
    
        // Supplier::create($request->all());
    
        // return redirect()->route('suppliers.index')
        //                 ->with('success','Supplier created successfully.');
        $request->validate([
            'supplierid' => 'required',
            'suppliername' => 'required',
            'sphone' => 'required',
            'slocation' => 'required',
        ]);
        
        DB::insert('INSERT INTO suppliers(supplierid, suppliername, sphone, slocation) VALUES (:supplierid, :suppliername, :sphone, :slocation)',
        [
            'supplierid' => $request->supplierid,
            'suppliername' => $request->suppliername,
            'sphone' => $request->sphone,
            'slocation' => $request->slocation,
        ]
        );

        return redirect()->route('suppliers.index')->with('success', 'Supplier added successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        return view('suppliers.show',compact('supplier'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function edit(Supplier $supplier)
    // {
    //     return view('suppliers.edit',compact('supplier'));
    // }
    public function edit($id)
    {
        $supplier = DB::table('suppliers')->where('supplierid', $id)->first();
        return view('suppliers.edit',compact('supplier'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Supplier $supplier)
    // {
    //      request()->validate([
    //         'supplierid' => 'required',
    //         'suppliername' => 'required',
    //         'sphone' => 'required',
    //         'slocation' => 'required',
    //     ]);
    
    //     $supplier->update($request->all());
    
    //     return redirect()->route('suppliers.index')
    //                     ->with('success','Supplier updated successfully');
    // }
    public function update($id, Request $request) {
        $request->validate([
            'supplierid' => 'required',
            'suppliername' => 'required',
            'sphone' => 'required',
            'slocation' => 'required',
        ]);

        DB::update('UPDATE suppliers SET supplierid = :supplierid, suppliername = :suppliername, sphone = :sphone, slocation = :slocation WHERE supplierid = :id',
        [
            'id' => $id,
            'supplierid' => $request->supplierid,
            'suppliername' => $request->suppliername,
            'sphone' => $request->sphone,
            'slocation' => $request->slocation,
        ]
        );

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Supplier $supplier)
    // {
    //     $supplier->delete();
    //     return redirect()->route('suppliers.index')
    //                     ->with('success','Supplier deleted successfully');
    // }
    public function destroy($id)
    {
        DB::update('UPDATE suppliers SET deleted_at = NOW() WHERE supplierid = :supplierid', ['supplierid' => $id]);
    
        return redirect()->route('suppliers.index')
                        ->with('success','Supplier deleted successfully');
    }
    
    public function deletelist()
    {
        // $suppliers = Supplier::onlyTrashed()->paginate(10);
        $suppliers = DB::table('suppliers')
                    ->whereNotNull('deleted_at')
                    ->paginate(10);
        return view('/suppliers/trash',compact('suppliers'))
            ->with('i', (request()->input('page', 1) - 1) * 10);

    }
    public function restore($id)
    {
        // $suppliers = Supplier::withTrashed()->where('supplierid',$id)->restore();
        DB::update('UPDATE suppliers SET deleted_at = NULL WHERE supplierid = :supplierid', ['supplierid' => $id]);
        return redirect()->route('suppliers.index')
                        ->with('success','Supplier restored successfully');
    }
    public function deleteforce($id)
    {
        // $suppliers = Supplier::withTrashed()->where('SupplierID',$id)->forceDelete();
        DB::delete('DELETE FROM suppliers WHERE supplierid = :supplierid', ['supplierid' => $id]);
        return redirect()->route('suppliers.index')
                        ->with('success','Supplier deleted permanently');
    }
}