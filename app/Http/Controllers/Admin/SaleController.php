<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Stock;

class SaleController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.sales.index')->only('index');
        $this->middleware('can:admin.sales.create')->only('create', 'store'); // Quiero que este middleware verifique que los usuarios que entren a la ruta tanto create como store tengan el permiso admin.sales.create
        $this->middleware('can:admin.sales.edit')->only('edit', 'update');
        $this->middleware('can:admin.sales.destroy')->only('delete', 'destroy');
    }

    public function index()
    {
        $sales = Sale::with('product')->latest()->paginate();
        return view('admin.sales.index', compact('sales'));
    }

    public function create()
    {
        $sale     = new Sale();
        $stocks   = Stock::with('product')->where('amount', '>=', 1)->get();
        $products = collect();

        foreach($stocks as $stock) {
            $products->add($stock->product);
        }

        $products = $products->pluck('name', 'id');

        return view('admin.sales.create', compact('sale', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $stock = Stock::where('product_id', $request['product_id'])->where('amount', '>=', 1)->first();

        if ($stock) {
            Sale::create( $request->all() + [ 'user_id' => Auth()->user()->id ] );
            $stock->update([ 'amount' => $stock->amount - 1 ]);
            
            return redirect()->route('admin.sales.index')->with('info', ['type' => 'success', 'title' => 'Sale created!', 'text' => 'Sale created successfully.']);
        }

        return redirect()->route('admin.stock.create')->with('info', ['type' => 'error', 'title' => 'Sale no created!', 'text' => 'Sale no created, no products in stock.']);
    }

    public function edit(Sale $sale)
    {
        $stocks   = Stock::with('product')->where('amount', '>=', 1)->get();
        $products = collect();

        foreach($stocks as $stock) {
            $products->add($stock->product);
        }

        $products = $products->pluck('name', 'id');

        return view('admin.sales.edit', compact('sale', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $request->validate([
            'product_id' => 'required'
        ]);

        $oldStock = Stock::where('product_id', $sale->product_id)->first();
        $newStock = Stock::where('product_id', $request['product_id'])->where('amount', '>=', 1)->first();

        if ($newStock) {
            $oldStock->update([ 'amount' => $oldStock->amount + 1 ]);
            $newStock->update([ 'amount' => $newStock->amount - 1 ]);

            $sale->update( $request->all() );
            return redirect()->route('admin.sales.index')->with('info', ['type' => 'success', 'title' => 'Sale updated!', 'text' => 'Sale updated successfully.']);
        }
        
        return redirect()->route('admin.sales.edit', $sale)->with('info', ['type' => 'success', 'title' => 'Sale no updated!', 'text' => 'Sale no updated, no products in stock.']);
    }

    public function delete(Sale $sale)
    {
        return view('admin.sales.delete', compact('sale'));
    }

    public function destroy(Sale $sale) {
        $stock = Stock::where('product_id', $sale->product_id)->first();
        
        $stock->update([ 'amount' => $stock->amount + 1 ]);
        $sale->delete();

        return redirect()->route('admin.sales.index')->with('info', ['type' => 'success', 'title' => 'Sale deleted!', 'text' => 'Sale deleted successfully.']);
    }
}
