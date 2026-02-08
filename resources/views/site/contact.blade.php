@extends('layouts.site')

@section('content')
<section class="npc-hero py-5">
    <div class="container">
        <h1 class="display-6 fw-bold">Contact Us</h1>
        <p class="text-muted">We would love to help you find or list a property.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="npc-card p-4 h-100">
                    <h5 class="fw-bold">Send a message</h5>
                    <form class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input class="form-control" placeholder="Your name" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input class="form-control" placeholder="you@example.com" />
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" rows="4" placeholder="How can we help?"></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="button">Send message</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="npc-card p-4 h-100">
                    <h5 class="fw-bold">Support details</h5>
                    <p class="text-muted">Reach our team for agent partnerships, listings, and media requests.</p>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Response time</span>
                            <span>Within 24 hours</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Office hours</span>
                            <span>Mon - Sat, 9AM - 6PM</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Service coverage</span>
                            <span>Nationwide</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
