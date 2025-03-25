@extends('layouts.app')

@section('title', 'Mouvements de Stock')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Historique des Mouvements</h5>
        <a href="{{ route('stock-movements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Nouveau Mouvement
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Raison</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $movement)
                    <tr>
                        <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $movement->product->name }}</td>
                        <td>
                            <span class="badge bg-{{ $movement->type === 'in' ? 'success' : 'danger' }}">
                                {{ $movement->type === 'in' ? 'Entrée' : 'Sortie' }}
                            </span>
                        </td>
                        <td>{{ $movement->quantity }}</td>
                        <td>{{ $movement->reason }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection