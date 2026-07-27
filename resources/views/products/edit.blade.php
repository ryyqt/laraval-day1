@extends('layouts.app')

@section('title', 'Edit Product')
@section('meta_description', 'Update an existing product.')

@section('styles')
<style>
    .form-page-wrap { max-width: 760px; margin: 0 auto; }

    .breadcrumb {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.82rem; color: var(--text-muted); margin-bottom: 24px;
    }
    .breadcrumb a { color: var(--text-muted); transition: color var(--transition); }
    .breadcrumb a:hover { color: var(--accent-1); }
    .breadcrumb svg { width: 13px; height: 13px; color: var(--text-muted); }
    .breadcrumb-current { color: var(--text-secondary); font-weight: 500; }

    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .form-card-header {
        padding: 24px 28px 0; border-bottom: 1px solid var(--border); padding-bottom: 20px;
        display: flex; align-items: center; gap: 14px;
    }
    .form-card-icon {
        width: 44px; height: 44px; border-radius: var(--radius-sm);
        background: var(--accent-soft); display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .form-card-icon svg { width: 20px; height: 20px; color: var(--accent-1); }
    .form-card-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); }
    .form-card-header p { font-size: 0.85rem; color: var(--text-secondary); margin-top: 2px; }

    .form-body { padding: 28px; display: flex; flex-direction: column; gap: 22px; }
    .field-group { display: flex; flex-direction: column; gap: 7px; }
    label {
        font-size: 0.82rem; font-weight: 600; color: var(--text-secondary);
        display: flex; align-items: center; gap: 4px;
    }
    label .required { color: #f43f5e; }

    .input-wrap { position: relative; }
    .input-wrap svg {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        width: 16px; height: 16px; color: var(--text-muted); pointer-events: none;
    }

    input[type="text"], input[type="number"], select, textarea {
        width: 100%; background: var(--bg-elevated); border: 1px solid var(--border);
        border-radius: var(--radius-sm); color: var(--text-primary); font-family: inherit;
        font-size: 0.9rem; padding: 10px 14px 10px 38px; outline: none; transition: var(--transition);
    }
    textarea { padding-left: 14px; resize: vertical; min-height: 110px; }
    select { padding-left: 14px; }
    input::placeholder, textarea::placeholder, select option { color: var(--text-muted); }
    input:focus, select:focus, textarea:focus {
        border-color: var(--border-hover); background: var(--bg-elevated);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
    }
    input.is-invalid, select.is-invalid, textarea.is-invalid {
        border-color: rgba(244,63,94,0.6); box-shadow: 0 0 0 3px rgba(244,63,94,0.1);
    }

    .field-hint { font-size: 0.78rem; color: var(--text-muted); }
    .field-error { font-size: 0.78rem; color: #f43f5e; display: flex; align-items: center; gap: 4px; }
    .field-error svg { width: 13px; height: 13px; }

    .char-row { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
    .char-count { font-size: 0.75rem; color: var(--text-muted); }
    .char-count.warn { color: #f59e0b; }
    .char-count.over { color: #f43f5e; }

    /* ── Current image display ── */
    .current-img-wrap {
        position: relative; border-radius: var(--radius-sm); overflow: hidden;
        border: 1px solid var(--border); background: var(--bg-elevated);
    }
    .current-img-wrap img {
        width: 100%; max-height: 260px; object-fit: cover; display: block;
    }
    .current-img-overlay {
        position: absolute; bottom: 0; left: 0; right: 0; padding: 10px 14px;
        background: linear-gradient(transparent, rgba(0,0,0,0.65));
        display: flex; align-items: center; justify-content: space-between;
    }
    .current-img-label { font-size: 0.78rem; color: rgba(255,255,255,0.8); }
    .btn-remove-img {
        display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px;
        background: rgba(244,63,94,0.85); color: #fff; border: none; border-radius: 6px;
        font-size: 0.75rem; font-weight: 600; cursor: pointer; transition: var(--transition);
    }
    .btn-remove-img:hover { background: #f43f5e; }
    .btn-remove-img svg { width: 13px; height: 13px; }

    .removed-notice {
        display: none; align-items: center; gap: 8px; padding: 10px 14px;
        background: rgba(244,63,94,0.1); border: 1px solid rgba(244,63,94,0.25);
        border-radius: var(--radius-sm); font-size: 0.82rem; color: #f43f5e;
    }
    .removed-notice svg { width: 15px; height: 15px; flex-shrink: 0; }
    .btn-undo { margin-left: auto; font-size: 0.78rem; color: var(--accent-1); cursor: pointer; border: none; background: none; font-weight: 600; }

    /* ── Upload zone ── */
    .upload-zone {
        border: 2px dashed var(--border); border-radius: var(--radius-sm);
        padding: 28px 20px; text-align: center; cursor: pointer;
        transition: var(--transition); background: var(--bg-elevated);
        position: relative;
    }
    .upload-zone:hover,
    .upload-zone.drag-over { border-color: var(--accent-1); background: var(--accent-soft); }
    .upload-zone input[type="file"] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
        padding: 0; border: none; background: none;
    }
    .upload-zone-icon {
        width: 48px; height: 48px; border-radius: 50%; background: var(--accent-soft);
        display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;
    }
    .upload-zone-icon svg { width: 22px; height: 22px; color: var(--accent-1); }
    .upload-zone p { font-size: 0.875rem; color: var(--text-secondary); }
    .upload-zone span { font-size: 0.78rem; color: var(--text-muted); }

    .img-preview-wrap {
        display: none; position: relative; margin-top: 12px;
        border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border);
    }
    .img-preview-wrap img {
        width: 100%; max-height: 260px; object-fit: cover; display: block;
    }
    .img-preview-remove {
        position: absolute; top: 8px; right: 8px;
        width: 30px; height: 30px; border-radius: 50%;
        background: rgba(0,0,0,0.55); border: none; cursor: pointer; color: #fff;
        display: flex; align-items: center; justify-content: center; transition: var(--transition);
    }
    .img-preview-remove:hover { background: #f43f5e; }
    .img-preview-remove svg { width: 16px; height: 16px; }

    /* ── Footer ── */
    .form-footer {
        display: flex; align-items: center; justify-content: flex-end; gap: 10px;
        padding: 20px 28px; border-top: 1px solid var(--border); background: var(--bg-elevated);
    }
    .btn {
        display: inline-flex; align-items: center; gap: 7px; padding: 9px 18px;
        border-radius: var(--radius-sm); font-size: 0.875rem; font-weight: 600; cursor: pointer;
        transition: var(--transition); border: none; text-decoration: none; white-space: nowrap;
    }
    .btn svg { width: 16px; height: 16px; flex-shrink: 0; }
    .btn-primary {
        background: var(--accent-grad); color: #fff;
        box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.45); }
    .btn-ghost {
        background: var(--bg-card); color: var(--text-secondary); border: 1px solid var(--border);
    }
    .btn-ghost:hover { border-color: var(--border-hover); color: var(--text-primary); }
</style>
@endsection

@section('content')
<div class="form-page-wrap">
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('products.index') }}">Products</a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
        <span class="breadcrumb-current">Edit Product</span>
    </nav>

    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487 18.75 6.375 10.5 14.625 7.5 15.75l1.125-3z"/>
                </svg>
            </div>
            <div>
                <h1>Edit Product</h1>
                <p>Update the product details below.</p>
            </div>
        </div>

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')

            <div class="form-body">
                {{-- Product Name --}}
                <div class="field-group">
                    <label for="name">Product Name <span class="required">*</span></label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"/>
                        </svg>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" placeholder="e.g. Wireless Headphones" maxlength="255" class="{{ $errors->has('name') ? 'is-invalid' : '' }}" autofocus autocomplete="off">
                    </div>
                    @error('name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Category --}}
                <div class="field-group">
                    <label for="category_id">Category</label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                        <select id="category_id" name="category_id" class="{{ $errors->has('category_id') ? 'is-invalid' : '' }}">
                            <option value="">Select a category</option>
                            @isset($categories)
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    @error('category_id')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Price --}}
                <div class="field-group">
                    <label for="price">Price <span class="required">*</span></label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-4-4h8"/>
                        </svg>
                        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" placeholder="0.00" step="0.01" min="0" class="{{ $errors->has('price') ? 'is-invalid' : '' }}">
                    </div>
                    @error('price')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Quantity --}}
                <div class="field-group">
                    <label for="quantity">Quantity <span class="required">*</span></label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" placeholder="0" min="0" step="1" class="{{ $errors->has('quantity') ? 'is-invalid' : '' }}">
                    </div>
                    @error('quantity')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="field-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Add a short product description" maxlength="1000" class="{{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description', $product->description) }}</textarea>
                    <div class="char-row">
                        @error('description')
                            <span class="field-error">{{ $message }}</span>
                        @else
                            <span class="field-hint">Optional — max 1 000 characters.</span>
                        @enderror
                        <span class="char-count" id="desc-counter">0 / 1000</span>
                    </div>
                </div>

                {{-- Image --}}
                <div class="field-group">
                    <label>Product Image</label>

                    {{-- Hidden flag to signal image removal --}}
                    <input type="hidden" name="remove_image" id="remove_image" value="0">

                    @if($product->image)
                        {{-- Current image --}}
                        <div class="current-img-wrap" id="current-img-wrap">
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                            <div class="current-img-overlay">
                                <span class="current-img-label">Current image</span>
                                <button type="button" class="btn-remove-img" id="btn-remove-img">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                    </svg>
                                    Remove
                                </button>
                            </div>
                        </div>

                        {{-- Removed notice --}}
                        <div class="removed-notice" id="removed-notice">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                            </svg>
                            Image will be removed on save.
                            <button type="button" class="btn-undo" id="btn-undo-remove">Undo</button>
                        </div>

                        {{-- Replace upload --}}
                        <div class="upload-zone" id="upload-zone" style="display:none;">
                            <input type="file" id="image" name="image" accept="image/*" class="{{ $errors->has('image') ? 'is-invalid' : '' }}">
                            <div class="upload-zone-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                </svg>
                            </div>
                            <p>Click to upload a replacement image</p>
                            <span>JPG, PNG, WEBP, GIF · max 2 MB</span>
                        </div>
                    @else
                        {{-- No existing image — show upload zone --}}
                        <div class="upload-zone" id="upload-zone">
                            <input type="file" id="image" name="image" accept="image/*" class="{{ $errors->has('image') ? 'is-invalid' : '' }}">
                            <div class="upload-zone-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                </svg>
                            </div>
                            <p>Click to upload or drag and drop</p>
                            <span>JPG, PNG, WEBP, GIF · max 2 MB</span>
                        </div>
                    @endif

                    {{-- New file preview --}}
                    <div class="img-preview-wrap" id="img-preview-wrap">
                        <img id="img-preview" src="" alt="New image preview">
                        <button type="button" class="img-preview-remove" id="img-preview-remove" title="Cancel selection">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    @error('image')
                        <span class="field-error">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror

                    <span class="field-hint">Upload a new image to replace the current one.</span>
                </div>
            </div>

            <div class="form-footer">
                <a href="{{ route('products.index') }}" class="btn btn-ghost">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Update Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Description char counter
    const descEl    = document.getElementById('description');
    const counterEl = document.getElementById('desc-counter');
    const MAX = 1000;
    function updateCounter() {
        const len = descEl.value.length;
        counterEl.textContent = len + ' / ' + MAX;
        counterEl.className = 'char-count' + (len > MAX * 0.9 && len <= MAX ? ' warn' : len > MAX ? ' over' : '');
    }
    if (descEl) { descEl.addEventListener('input', updateCounter); updateCounter(); }

    // Elements (may not exist if product has no image)
    const imageInput       = document.getElementById('image');
    const previewWrap      = document.getElementById('img-preview-wrap');
    const previewImg       = document.getElementById('img-preview');
    const removePreviewBtn = document.getElementById('img-preview-remove');
    const uploadZone       = document.getElementById('upload-zone');
    const removeImgFlag    = document.getElementById('remove_image');

    // Existing-image controls (only present when product has an image)
    const currentWrap      = document.getElementById('current-img-wrap');
    const removedNotice    = document.getElementById('removed-notice');
    const btnRemove        = document.getElementById('btn-remove-img');
    const btnUndo          = document.getElementById('btn-undo-remove');

    // ── Remove existing image ──
    if (btnRemove) {
        btnRemove.addEventListener('click', () => {
            removeImgFlag.value = '1';
            currentWrap.style.display   = 'none';
            removedNotice.style.display = 'flex';
            if (uploadZone) uploadZone.style.display = 'block';
        });
    }

    // ── Undo remove ──
    if (btnUndo) {
        btnUndo.addEventListener('click', () => {
            removeImgFlag.value = '0';
            currentWrap.style.display   = 'block';
            removedNotice.style.display = 'none';
            if (uploadZone) uploadZone.style.display = 'none';
            // Clear any newly selected file too
            if (imageInput) imageInput.value = '';
            if (previewWrap) previewWrap.style.display = 'none';
        });
    }

    // ── New file preview ──
    if (imageInput) {
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                previewWrap.style.display = 'block';
                if (uploadZone) uploadZone.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    }

    // ── Cancel new selection ──
    if (removePreviewBtn) {
        removePreviewBtn.addEventListener('click', () => {
            if (imageInput) imageInput.value = '';
            previewImg.src = '';
            previewWrap.style.display = 'none';
            if (uploadZone) uploadZone.style.display = 'block';
        });
    }

    // ── Drag-and-drop highlight ──
    if (uploadZone) {
        uploadZone.addEventListener('dragover',  e => { e.preventDefault(); uploadZone.classList.add('drag-over'); });
        uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));
        uploadZone.addEventListener('drop',      e => { e.preventDefault(); uploadZone.classList.remove('drag-over'); });
    }
</script>
@endsection