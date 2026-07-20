@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <section class="hero">
        <h1>Welcome to your Laravel home page</h1>
        <p>This page uses a shared layout with a navbar and sidebar so the app feels consistent across screens.</p>
    </section>

    <div class="card-grid">
        <article class="card">
            <div class="stat">12</div>
            <h3>Active Projects</h3>
            <p class="muted">Track your current initiatives in one place.</p>
        </article>
        <article class="card">
            <div class="stat">84%</div>
            <h3>Team Progress</h3>
            <p class="muted">Your team is on pace for this sprint.</p>
        </article>
        <article class="card">
            <div class="stat">4</div>
            <h3>Pending Tasks</h3>
            <p class="muted">A few follow-ups still need attention.</p>
        </article>
    </div>
@endsection
