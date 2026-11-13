<div class="form-grid">
    <div class="field">
        <label for="nombre">Nombre del producto <span>*</span></label>
        <input id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre ?? '') }}" maxlength="120" required placeholder="Ej. Audífonos inalámbricos">
        @error('nombre')<small class="field-error">{{ $message }}</small>@enderror
    </div>
    <div class="field">
        <label for="categoria">Categoría <span>*</span></label>
        <input id="categoria" name="categoria" value="{{ old('categoria', $producto->categoria ?? '') }}" maxlength="80" required placeholder="Ej. Electrónica">
        @error('categoria')<small class="field-error">{{ $message }}</small>@enderror
    </div>
    <div class="field">
        <label for="precio">Precio (Bs) <span>*</span></label>
        <input id="precio" name="precio" type="number" min="0" step="0.01" value="{{ old('precio', $producto->precio ?? '') }}" required placeholder="0.00">
        @error('precio')<small class="field-error">{{ $message }}</small>@enderror
    </div>
    <div class="field">
        <label for="stock">Cantidad en stock <span>*</span></label>
        <input id="stock" name="stock" type="number" min="0" step="1" value="{{ old('stock', $producto->stock ?? 0) }}" required placeholder="0">
        @error('stock')<small class="field-error">{{ $message }}</small>@enderror
    </div>
    <div class="field full">
        <label for="descripcion">Descripción <small>(opcional)</small></label>
        <textarea id="descripcion" name="descripcion" rows="4" maxlength="1000" placeholder="Detalles adicionales del producto…">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
        @error('descripcion')<small class="field-error">{{ $message }}</small>@enderror
    </div>
</div>
<div class="form-actions">
    <a class="button light" href="{{ route('productos.index') }}">Cancelar</a>
    <button class="button primary" type="submit">{{ $submitLabel }}</button>
</div>
