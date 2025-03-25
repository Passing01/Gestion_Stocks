@extends('layouts.app')

@section('title', 'Détails de la Catégorie')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Détails de la Catégorie</h5>
    </div>
    <div class="card-body">
        <h3>{{ $category->name }}</h3>
        <p class="text-muted">{{ $category->description }}</p>
        
        <h5 class="mt-4">Produits dans cette catégorie</h5>
        @if($category->products->count() > 0)
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ number_format($product->price, 2) }} €</td>
                        <td>{{ $product->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info">Aucun produit dans cette catégorie.</div>
        @endif
    </div>
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Supprimer
            </button>
        </form>
    </div>
</div>
@endsection