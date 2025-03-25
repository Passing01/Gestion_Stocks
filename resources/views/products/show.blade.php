@extends('layouts.app')

@section('title', 'Détails du Produit')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Détails du Produit</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <span class="text-muted">Aucune image</span>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <h3>{{ $product->name }}</h3>
                <p class="text-muted">{{ $product->description }}</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Catégorie:</strong> {{ $product->category->name }}
                    </li>
                    <li class="list-group-item">
                        <strong>Fournisseur:</strong> {{ $product->supplier->name }}
                    </li>
                    <li class="list-group-item">
                        <strong>Prix:</strong> {{ number_format($product->price, 2) }} €
                    </li>
                    <li class="list-group-item">
                        <strong>Quantité en stock:</strong>
                        <span class="badge bg-{{ $product->quantity > 10 ? 'success' : ($product->quantity > 0 ? 'warning' : 'danger') }}">
                            {{ $product->quantity }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <strong>Date de création:</strong> {{ $product->created_at->format('d/m/Y H:i') }}
                    </li>
                    <li class="list-group-item">
                        <strong>Dernière mise à jour:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Supprimer
            </button>
        </form>
    </div>
</div>
@endsection