<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStock;
use App\Http\Requests\UpdateStock;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with('product')->latest()->get();
        return view('admin.stocks.index', compact('stocks'));
    }

    public function create()
    {
        $stock = new Stock();
        $products = Product::all();

        return view('admin.stocks.create', compact('stock', 'products'));
    }

    public function store(StoreStock $request)
    {
        $stock = Stock::create($request->all());
        return redirect()->route('admin.stocks.edit', $stock);
    }

    public function edit(Stock $stock)
    {
        $products = Product::all();
        return view('admin.stocks.edit', compact('stock', 'products'));
    }

    public function update(UpdateStock $request, Stock $stock)
    {
        $stock->update($request->all());
        return redirect()->route('admin.stocks.edit', $stock);
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('admin.stocks.index');
    }
}
