
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white p-3">
                    <h4 class="mb-0">Rakz Rental - Test Checkout</h4>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-warning">
                        <strong>Demo Mode:</strong>
                        This is a simulated payment. No real money will be charged.
                    </div>

                    <h5>Booking #{{ $booking->id }}</h5>

                    <p class="text-muted mb-1">
                        Vehicle: {{ $booking->vehicle_registration_no }}
                    </p>

                    <p class="text-muted">
                        {{ $booking->pickup_date->format('d M Y') }}
                        -
                        {{ $booking->return_date->format('d M Y') }}
                    </p>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-muted">Total amount</span>
                        <h3 class="mb-0">
                            FJD {{ number_format((float) $booking->billing->amount, 2) }}
                        </h3>
                    </div>

                    @if ($booking->billing->status === 'paid')
                        <div class="alert alert-success">
                            This booking has already been paid.
                        </div>
                    @elseif ($booking->status === 'cancelled')
                        <div class="alert alert-danger">
                            This booking has been cancelled and cannot be paid.
                        </div>
                    @else
                        <form method="POST"
                              action="{{ route('customer.bookings.payment.process', $booking) }}">
                            @csrf

                            <button type="submit"
                                    name="result"
                                    value="success"
                                    class="btn btn-success btn-lg w-100 mb-3">
                                Simulate Successful Payment
                            </button>

                            <button type="submit"
                                    name="result"
                                    value="failed"
                                    class="btn btn-outline-danger w-100">
                                Simulate Failed Payment
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('customer.bookings.show', $booking) }}"
                       class="btn btn-link w-100 mt-3">
                        Back to Booking
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
