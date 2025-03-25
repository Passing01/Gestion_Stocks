@extends('layouts.app')

@section('title', 'Détails du Fournisseur')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Détails du Fournisseur</h5>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <strong>Nom:</strong> {{ $supplier->name }}
            </li>
            <li class="list-group-item">
                <strong>Email:</strong> {{ $supplier->email }}
            </li>
            <li class="list-group-item">
                <strong>Téléphone:</strong> {{ $supplier->phone }}
            </li>
            <li class="list-group-item">
                <strong>Adresse:</strong> {{ $supplier->address }}
            </li>
            <li class="list-group-item">
                <strong>Date de création:</strong> {{ $supplier->created_at->format('d/m/Y H:i') }}
            </li>
        </ul>

        <h5 class="mt-4">Produits fournis</h5>
        @if($supplier->products->count() > 0)
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
                    @foreach($supplier->products as $product)
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
        <div class="alert alert-info">Aucun produit associé à ce fournisseur.</div>
        @endif
    </div>
    <div class="card-footer d-flex justify-content-end">
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr ?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Supprimer
            </button>
        </form>
    </div>
</div>
@endsection