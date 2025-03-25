@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Produits</h5>
                        <p class="card-text h4">{{ App\Models\Product::count() }}</p>
                    </div>
                    <i class="bi bi-box-seam h1"></i>
                </div>
                <a href="{{ route('products.index') }}" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Catégories</h5>
                        <p class="card-text h4">{{ App\Models\Category::count() }}</p>
                    </div>
                    <i class="bi bi-tags h1"></i>
                </div>
                <a href="{{ route('categories.index') }}" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Utilisateurs</h5>
                        <p class="card-text h4">{{ App\Models\Supplier::count() }}</p>
                    </div>
                    <i class="bi bi-people h1"></i>
                </div>
                <a href="{{ route('suppliers.index') }}" class="text-white stretched-link"></a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Produits avec stock faible</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\Product::where('quantity', '<', 10)->orderBy('quantity')->get() as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $product->quantity > 0 ? 'warning' : 'danger' }}">
                                        {{ $product->quantity }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Derniers mouvements</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Type</th>
                                <th>Quantité</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\StockMovement::with('product')->latest()->take(5)->get() as $movement)
                            <tr>
                                <td>{{ $movement->product->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $movement->type === 'in' ? 'success' : 'danger' }}">
                                        {{ $movement->type === 'in' ? 'Entrée' : 'Sortie' }}
                                    </span>
                                </td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection