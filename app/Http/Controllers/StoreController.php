<?php
    
namespace App\Http\Controllers;
    
use App\Models\Store;
use Illuminate\Http\Request;
use DB;
    
class StoreController extends Controller
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
        // $stores = Store::where('storename','LIKE','%'.$keyword.'%')
        //             ->paginate(10);
        // return view('stores.index',compact('stores'))
        //     ->with('i', (request()->input('page', 1) - 1) * 10);
        $keyword = $request->keyword;
        $stores = DB::table('stores')->where('storename','LIKE','%'.$keyword.'%')
                    ->whereNull('deleted_at')
                    ->paginate(10);
        return view('stores.index',compact('stores'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stores.create');
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
        //     'storeid' => 'required',
        //     'storename' => 'required',
        //     'phone' => 'required',
        //     'location' => 'required',
        // ]);
    
        // Store::create($request->all());
    
        // return redirect()->route('stores.index')
        //                 ->with('success','Store created successfully.');
        $request->validate([
            'storeid' => 'required',
            'storename' => 'required',
            'phone' => 'required',
            'location' => 'required',
        ]);
        
        DB::insert('INSERT INTO stores(storeid, storename, phone, location) VALUES (:storeid, :storename, :phone, :location)',
        [
            'storeid' => $request->storeid,
            'storename' => $request->storename,
            'phone' => $request->phone,
            'location' => $request->location,
        ]
        );

        return redirect()->route('stores.index')->with('success', 'Store added successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        return view('stores.show',compact('store'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function edit(Store $store)
    // {
    //     return view('stores.edit',compact('store'));
    // }
    public function edit($id)
    {
        $store = DB::table('stores')->where('storeid', $id)->first();
        return view('stores.edit',compact('store'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Store $store)
    // {
    //      request()->validate([
    //         'storeid' => 'required',
    //         'storename' => 'required',
    //         'phone' => 'required',
    //         'location' => 'required',
    //     ]);
    
    //     $store->update($request->all());
    
    //     return redirect()->route('stores.index')
    //                     ->with('success','Store updated successfully');
    // }
    public function update($id, Request $request) {
        $request->validate([
            'storeid' => 'required',
            'storename' => 'required',
            'phone' => 'required',
            'location' => 'required',
        ]);

        DB::update('UPDATE stores SET storeid = :storeid, storename = :storename, phone = :phone, location = :location WHERE storeid = :id',
        [
            'id' => $id,
            'storeid' => $request->storeid,
            'storename' => $request->storename,
            'phone' => $request->phone,
            'location' => $request->location,
        ]
        );

        return redirect()->route('stores.index')->with('success', 'Store updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Store $store)
    // {
    //     $store->delete();
    //     return redirect()->route('stores.index')
    //                     ->with('success','Store deleted successfully');
    // }
    public function destroy($id)
    {
        DB::update('UPDATE stores SET deleted_at = NOW() WHERE storeid = :storeid', ['storeid' => $id]);
    
        return redirect()->route('stores.index')
                        ->with('success','Store deleted successfully');
    }
    
    public function deletelist()
    {
        // $stores = Store::onlyTrashed()->paginate(10);
        $stores = DB::table('stores')
                    ->whereNotNull('deleted_at')
                    ->paginate(10);
        return view('/stores/trash',compact('stores'))
            ->with('i', (request()->input('page', 1) - 1) * 10);

    }
    public function restore($id)
    {
        // $stores = Store::withTrashed()->where('storeid',$id)->restore();
        DB::update('UPDATE stores SET deleted_at = NULL WHERE storeid = :storeid', ['storeid' => $id]);
        return redirect()->route('stores.index')
                        ->with('success','Store restored successfully');
    }
    public function deleteforce($id)
    {
        // $stores = Store::withTrashed()->where('StoreID',$id)->forceDelete();
        DB::delete('DELETE FROM stores WHERE storeid = :storeid', ['storeid' => $id]);
        return redirect()->route('stores.index')
                        ->with('success','Store deleted permanently');
    }
}