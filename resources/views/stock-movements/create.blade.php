@extends('layouts.app')

@section('title', 'Enregistrer un Mouvement de Stock')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Nouveau Mouvement de Stock</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('stock-movements.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="product_id" class="form-label">Produit</label>
                    <select class="form-select" id="product_id" name="product_id" required>
                        <option value="">Sélectionner un produit</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-stock="{{ $product->quantity }}">
                                {{ $product->name }} (Stock: {{ $product->quantity }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-label">Type de mouvement</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="in">Entrée de stock</option>
                        <option value="out">Sortie de stock</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="quantity" class="form-label">Quantité</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                    <small id="stockHelp" class="form-text text-muted"></small>
                </div>
                <div class="col-md-6">
                    <label for="reason" class="form-label">Raison</label>
                    <input type="text" class="form-control" id="reason" name="reason" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('stock-movements.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const typeSelect = document.getElementById('type');
        const quantityInput = document.getElementById('quantity');
        const stockHelp = document.getElementById('stockHelp');
        
        function updateStockInfo() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const currentStock = selectedOption ? parseInt(selectedOption.dataset.stock) : 0;
            const movementType = typeSelect.value;
            
            if (movementType === 'out') {
                stockHelp.textContent = `Stock actuel: ${currentStock}. La quantité ne peut pas dépasser ce montant.`;
                quantityInput.max = currentStock;
            } else {
                stockHelp.textContent = `Stock actuel: ${currentStock}`;
                quantityInput.removeAttribute('max');
            }
        }
        
        productSelect.addEventListener('change', updateStockInfo);
        typeSelect.addEventListener('change', updateStockInfo);
        
        // Initial update
        updateStockInfo();
    });
</script>
@endpush
@endsection