<?php
    
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JoinController extends Controller
{
    public function index()
    {
        $joins = DB::table('fish')
            ->join('suppliers', 'fish.supplierid', '=', 'suppliers.supplierid')
            ->join('stores', 'fish.storeid', '=', 'stores.storeid')
            ->select('fish.fishname as fishname', 'fish.price as price', 'fish.stock as stock', 'suppliers.suppliername as suppliername', 'stores.storename as storename')
            ->paginate(10);
            return view('totals.index',compact('joins'))
                ->with('i', (request()->input('page', 1) - 1) * 10);
    }
}