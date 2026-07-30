@extends('layouts.app')

@section('title', 'Add Customer')
@section('meta_description', 'Create a new customer.')

@section('styles')
<style>
    /* ── Form page styles ── */
    .form-page-wrap {
        max-width: 640px;
        margin: 0 auto;
    }

    /* Breadcrumb */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-bottom: 24px;
    }
    .breadcrumb a {
        color: var(--text-muted);
        transition: color var(--transition);
    }
    .breadcrumb a:hover { color: var(--accent-1); }
    .breadcrumb svg { width: 13px; height: 13px; color: var(--text-muted); }
    .breadcrumb-current { color: var(--text-secondary); font-weight: 500; }

    /* Form card */
    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .form-card-header {
        padding: 24px 28px 0;
        border-bottom: 1px solid var(--border);
        padding-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .form-card-icon {
        width: 44px; height: 44px;
        border-radius: var(--radius-sm);
        background: var(--accent-soft);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .form-card-icon svg { width: 20px; height: 20px; color: var(--accent-1); }
    .form-card-header h1 { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); }
    .form-card-header p  { font-size: 0.85rem; color: var(--text-secondary); margin-top: 2px; }

    .form-body { padding: 28px; display: flex; flex-direction: column; gap: 22px; }

    /* Field group */
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
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px; height: 16px;
        color: var(--text-muted);
        pointer-events: none;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"] {
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
    input[type="text"]::placeholder,
    input[type="email"]::placeholder,
    input[type="tel"]::placeholder { color: var(--text-muted); }
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="tel"]:focus {
        border-color: var(--border-hover);
        background: var(--bg-elevated);
        box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
    }
    input.is-invalid {
        border-color: rgba(244,63,94,0.6);
        box-shadow: 0 0 0 3px rgba(244,63,94,0.1);
    }
    .field-hint { font-size: 0.78rem; color: var(--text-muted); }
    .field-error { font-size: 0.78rem; color: #f43f5e; display: flex; align-items: center; gap: 4px; }
    .field-error svg { width: 13px; height: 13px; }

    /* Form footer */
    .form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 20px 28px;
        border-top: 1px solid var(--border);
        background: var(--bg-elevated);
    }

    /* Btn */
    .btn {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px;
        border-radius: var(--radius-sm);
        font-size: 0.875rem; font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
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
        <a href="{{ route('customers.index') }}">Customers</a>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
        <span class="breadcrumb-current">Add Customer</span>
    </nav>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/>
                </svg>
            </div>
            <div>
                <h1>Add New Customer</h1>
                <p>Fill in the details below to create a new customer record.</p>
            </div>
        </div>

        <form action="{{ route('customers.store') }}" method="POST" id="create-customer-form" novalidate>
            @csrf

            <div class="form-body">

                <!-- Name -->
                <div class="field-group">
                    <label for="name">
                        Name <span class="required">*</span>
                    </label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        </svg>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. John Doe"
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

                <!-- Email -->
                <div class="field-group">
                    <label for="email">
                        Email <span class="required">*</span>
                    </label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                        </svg>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="e.g. john@example.com"
                            maxlength="255"
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                            autocomplete="off"
                        >
                    </div>
                    @error('email')
                        <span class="field-error">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div class="field-group">
                    <label for="phone_number">
                        Phone Number <span class="required">*</span>
                    </label>
                    <div class="input-wrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                        </svg>
                        <input
                            type="tel"
                            id="phone_number"
                            name="phone_number"
                            value="{{ old('phone_number') }}"
                            placeholder="e.g. +1 (555) 123-4567"
                            maxlength="20"
                            class="{{ $errors->has('phone_number') ? 'is-invalid' : '' }}"
                            autocomplete="off"
                        >
                    </div>
                    @error('phone_number')
                        <span class="field-error">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                            </svg>
                            {{ $message }}
                        </span>
                    @enderror
                    <span class="field-hint">Max 20 characters.</span>
                </div>

            </div><!-- /.form-body -->

            <div class="form-footer">
                <a href="{{ route('customers.index') }}" class="btn btn-ghost" id="btn-cancel">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" id="btn-create-customer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Create Customer
                </button>
            </div>
        </form>
    </div><!-- /.form-card -->

</div>
@endsection
