@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="hero">
        <h1>Dashboard Overview</h1>
        <p>Here is a simple analytics view built on the same shared layout.</p>
    </section>

    <div class="card-grid">
        <article class="card">
            <div class="stat">1,240</div>
            <h3>Visitors</h3>
            <p class="muted">Total visits this month.</p>
        </article>
        <article class="card">
            <div class="stat">89</div>
            <h3>Orders</h3>
            <p class="muted">Successful transactions processed.</p>
        </article>
        <article class="card">
            <div class="stat">$12.4k</div>
            <h3>Revenue</h3>
            <p class="muted">Current monthly revenue.</p>
        </article>
    </div>

    <div class="card" style="margin-top: 16px;">
        <h3>Recent Activity</h3>
        <div class="list">
            <div class="list-item">New user signed up</div>
            <div class="list-item">Invoice #102 was marked paid</div>
            <div class="list-item">A report export was completed</div>
        </div>
    </div>
@endsection
