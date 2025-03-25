<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with('product')->latest()->get();
        return view('stock-movements.index', compact('movements'));
    }

    public function create()
    {
        $products = Product::all();
        return view('stock-movements.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:in,out',
            'reason' => 'required|string|max:255',
        ]);

        $product = Product::find($validated['product_id']);

        if ($validated['type'] === 'out' && $product->quantity < $validated['quantity']) {
            return back()->with('error', 'Stock insuffisant pour ce mouvement.');
        }

        StockMovement::create($validated);

        // Mise à jour du stock
        $product->quantity += ($validated['type'] === 'in' ? $validated['quantity'] : -$validated['quantity']);
        $product->save();

        return redirect()->route('stock-movements.index')->with('success', 'Mouvement de stock enregistré avec succès.');
    }
}