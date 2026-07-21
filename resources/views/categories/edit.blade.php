@extends('layouts.app')

@section('title', 'Edit Category — ' . $category->name)
@section('meta_description', 'Edit category: ' . $category->name)

@section('styles')
<style>
    /* ── Same form styles as create (self-contained) ── */
    .form-page-wrap {
        max-width: 640px;
        margin: 0 auto;
    }
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-bottom: 24px;
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
        padding: 24px 28px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .form-card-icon {
        width: 44px; height: 44px;
        border-radius: var(--radius-sm);
        background: rgba(139,92,246,0.12);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .form-card-icon svg { width: 20px; height: 20px; color: var(--accent-2); }
    .form-card-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); }
    .form-card-header p  { font-size: 0.85rem; color: var(--text-secondary); margin-top: 2px; }

    .form-body { padding: 28px; display: flex; flex-direction: column; gap: 22px; }

    .field-group { display: flex; flex-direction: column; gap: 7px; }
    label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    label .required { color: #f43f5e; }

    .input-wrap { position: relative; }
    .input-wrap svg {
        position: absolute;
        left: 12px; top: 50%;
        transform: translateY(-50%);
        width: 16px; height: 16px;
        color: var(--text-muted);
        pointer-events: none;
    }
    input[type="text"],
    textarea {
        width: 100%;
        background: var(--bg-elevated);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        color: var(--text-primary);
        font-family: inherit;
        font-size: 0.9rem;
        padding: 10px 14px 10px 38px;
        outline: none;
        transition: var(--transition);
    }
    textarea { padding-left: 14px; resize: vertical; min-height: 110px; }
    input[type="text"]::placeholder,
    textarea::placeholder { color: var(--text-muted); }
    input[type="text"]:focus,
    textarea:focus {
        border-color: var(--border-hover);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
    }
    input.is-invalid, textarea.is-invalid {
        border-color: rgba(244,63,94,0.6);
        box-shadow: 0 0 0 3px rgba(244,63,94,0.1);
    }
    .field-hint { font-size: 0.78rem; color: var(--text-muted); }
    .field-error { font-size: 0.78rem; color: #f43f5e; display: flex; align-items: center; gap: 4px; }
    .field-error svg { width: 13px; height: 13px; }
    .char-row { display: flex; justify-content: space-between; align-items: center; }
    .char-count { font-size: 0.75rem; color: var(--text-muted); }
    .char-count.warn { color: #f59e0b; }
    .char-count.over { color: #f43f5e; }

    /* Meta strip */
    .meta-strip {
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
        padding: 14px 28px;
        background: var(--bg-elevated);
        border-bottom: 1px solid var(--border);
    }
    .meta-item { display: flex; flex-direction: column; gap: 2px; }
    .meta-label { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); }
    .meta-value { font-size: 0.83rem; color: var(--text-secondary); }
    .meta-id {
        display: inline-block; padding: 1px 8px;
        background: var(--accent-soft); color: var(--accent-1);
        border-radius: 999px; font-size: 0.72rem; font-weight: 700;
    }

    .form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 20px 28px;
        border-top: 1px solid var(--border);
        background: var(--bg-elevated);
    }
    .btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px;
        border-radius: var(--radius-sm);
        font-size: 0.875rem; font-weight: 600;
        cursor: pointer; transition: var(--transition);
        border: none; text-decoration: none; white-space: nowrap;
    }
    .btn svg { width: 16px; height: 16px; flex-shrink: 0; }
    .btn-primary {
        background: var(--accent-grad);
        color: #fff;
        box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(99,102,241,0.45); }
    .btn-ghost {
        background: var(--bg-card);
        color: var(--text-secondary);
        border: 1px solid var(--border);
    }
    .btn-ghost:hover { border-color: var(--border-hover); color: var(--text-primary); }
</style>
@endsection

@section('content')
<div class="form-page-wrap">

    <!-- Breadcrumb -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('categories.index') }}">Categories</a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
        <span class="breadcrumb-current">Edit — {{ $category->name }}</span>
    </nav>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                </svg>
            </div>
            <div>
                <h1>Edit Category</h1>
                <p>Update the name or description, then save your changes.</p>
            </div>
        </div>

        <!-- Meta info strip -->
        <div class="meta-strip">
            <div class="meta-item">
                <span class="meta-label">ID</span>
                <span class="meta-value"><span class="meta-id">#{{ $category->id }}</span></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Created</span>
                <span class="meta-value">{{ $category->created_at->format('M d, Y \a\t H:i') }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Last Updated</span>
                <span class="meta-value">{{ $category->updated_at->format('M d, Y \a\t H:i') }}</span>
            </div>
        </div>

        <form action="{{ route('categories.update', $category) }}" method="POST" id="edit-category-form" novalidate>
            @csrf
            @method('PUT')

            <div class="form-body">

                <!-- Name -->
                <div class="field-group">
                    <label for="name">
                        Name <span class="required">*</span>
                    </label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z"/>
                        </svg>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $category->name) }}"
                            placeholder="Category name…"
                            maxlength="255"
                            class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                            autofocus
                            autocomplete="off"
                        >
                    </div>
                    @error('name')
                        <span class="field-error">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Description -->
                <div class="field-group">
                    <label for="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        placeholder="Brief description (optional)…"
                        maxlength="1000"
                        class="{{ $errors->has('description') ? 'is-invalid' : '' }}"
                    >{{ old('description', $category->description) }}</textarea>
                    <div class="char-row">
                        @error('description')
                            <span class="field-error">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @else
                            <span class="field-hint">Optional — max 1 000 characters.</span>
                        @enderror
                        <span class="char-count" id="desc-counter">0 / 1000</span>
                    </div>
                </div>

            </div><!-- /.form-body -->

            <div class="form-footer">
                <a href="{{ route('categories.index') }}" class="btn btn-ghost" id="btn-cancel-edit">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" id="btn-save-category">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div><!-- /.form-card -->

</div>
@endsection

@section('scripts')
<script>
    const descEl    = document.getElementById('description');
    const counterEl = document.getElementById('desc-counter');
    const MAX       = 1000;

    function updateCounter() {
        const len = descEl.value.length;
        counterEl.textContent = len + ' / ' + MAX;
        counterEl.className = 'char-count' + (len > MAX * 0.9 && len <= MAX ? ' warn' : len > MAX ? ' over' : '');
    }
    descEl.addEventListener('input', updateCounter);
    updateCounter();
</script>
@endsection
