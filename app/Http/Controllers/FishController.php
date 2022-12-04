<?php
    
namespace App\Http\Controllers;
    
use App\Models\Fish;
use Illuminate\Http\Request;
use DB;
    
class FishController extends Controller
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
        // $fishes = Fish::where('fishname','LIKE','%'.$keyword.'%')
        // ->orWhere('price','LIKE',$keyword)
        //             ->orWhere('stock','LIKE',$keyword)
        //             ->orWhere('supplierid','LIKE',$keyword)
        //             ->orWhere('storeid','LIKE',$keyword)
        //             ->paginate(10);
        // return view('fishes.index',compact('fishes'))
        //     ->with('i', (request()->input('page', 1) - 1) * 10);
        $keyword = $request->keyword;
        $fishes = DB::table('fish')
                    ->where('fishname','LIKE','%'.$keyword.'%')
                    ->whereNull('deleted_at')
                    ->paginate(10);
        return view('fishes.index',compact('fishes'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fishes.create');
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
        //     'fishid' => 'required',
        //     'fishname' => 'required',
        //     'price' => 'required',
        //     'stock' => 'required',
        //     'supplierid' => 'required',
        //     'storeid' =>'required',
        // ]);
    
        // Fish::create($request->all());
    
        // return redirect()->route('fishes.index')
        //                 ->with('success','Fish created successfully.');
        $request->validate([
            'fishid' => 'required',
            'fishname' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'supplierid' => 'required',
            'storeid' => 'required',
        ]);
        
        DB::insert('INSERT INTO fish(fishid, fishname, price, stock, supplierid, storeid) VALUES (:fishid, :fishname, :price, :stock, :supplierid, :storeid)',
        [
            'fishid' => $request->fishid,
            'fishname' => $request->fishname,
            'price' => $request->price,
            'stock' => $request->stock,
            'supplierid' => $request->supplierid,
            'storeid' => $request->storeid,
        ]
        );

        return redirect()->route('fishes.index')->with('success', 'Fish added successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Fish $fish)
    {
        return view('fishes.show',compact('fish'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function edit(Fish $fish)
    // {
    //     return view('fishes.edit',compact('fish'));
    // }
    public function edit($id)
    {
        $fish = DB::table('fish')->where('fishid', $id)->first();
        return view('fishes.edit',compact('fish'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Fish $fish)
    // {
    //     request()->validate([
    //         'fishid' => 'required',
    //         'fishname' => 'required',
    //         'price' => 'required',
    //         'stock' => 'required',
    //         'supplierid' => 'required',
    //         'storeid' =>'required',
    //     ]);
    
    //     $fish->update($request->all());
    
    //     return redirect()->route('fishes.index')
    //                     ->with('success','Fish updated successfully');
    // }
    public function update($id, Request $request) {
        $request->validate([
            'fishid' => 'required',
            'fishname' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'supplierid' => 'required',
            'storeid' => 'required',
        ]);

        DB::update('UPDATE fish SET fishid = :fishid, fishname = :fishname, price = :price, stock = :stock, supplierid = :supplierid, storeid = :storeid WHERE fishid = :id',
        [
            'id' => $id,
            'fishid' => $request->fishid,
            'fishname' => $request->fishname,
            'price' => $request->price,
            'stock' => $request->stock,
            'supplierid' => $request->supplierid,
            'storeid' => $request->storeid,
        ]
        );

        return redirect()->route('fishes.index')->with('success', 'Fish updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Fish $fish)
    // {
    //     $fish->delete();
    //     return redirect()->route('fishes.index')
    //                     ->with('success','Fish deleted successfully');
    // }
    public function destroy($id)
    {
        DB::update('UPDATE fish SET deleted_at = NOW() WHERE fishid = :fishid', ['fishid' => $id]);
    
        return redirect()->route('fishes.index')
                        ->with('success','Fish deleted successfully');
    }
    
    public function deletelist()
    {
        // $fishes = Fish::onlyTrashed()->paginate(10);
        $fishes = DB::table('fish')
                    ->whereNotNull('deleted_at')
                    ->paginate(10);
        return view('/fishes/trash',compact('fishes'))
            ->with('i', (request()->input('page', 1) - 1) * 10);

    }
    public function restore($id)
    {
        // $fishes = Fish::withTrashed()->where('fishid',$id)->restore();
        DB::update('UPDATE fish SET deleted_at = NULL WHERE fishid = :fishid', ['fishid' => $id]);
        return redirect()->route('fishes.index')
                        ->with('success','Fish restored successfully');
    }
    public function deleteforce($id)
    {
        // $fish = Fish::withTrashed()->where('FishID',$id)->forceDelete();
        DB::delete('DELETE FROM fish WHERE fishid = :fishid', ['fishid' => $id]);
        return redirect()->route('fishes.index')
                        ->with('success','Fish deleted permanently');
    }
}