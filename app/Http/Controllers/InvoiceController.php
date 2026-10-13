<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Support\QrCodeGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * InvoiceController
 * ---------------------------------------------------------------------------
 * Renders a branded PDF invoice for a booking. Accessible to:
 *   - The customer who owns the booking
 *   - Any admin
 *
 * Uses barryvdh/laravel-dompdf — install with `composer require barryvdh/laravel-dompdf`.
 */
class InvoiceController extends Controller
{
    public function download(Booking $booking)
    {
        $user = Auth::user();

        // Authorization: owner OR admin
        if (!$user || ($user->id !== $booking->user_id && !$user->isAdmin())) {
            abort(403);
        }

        $booking->load(['user', 'vehicle.category', 'billing', 'replacements.replacementVehicle']);

        // Generate a QR code pointing to the booking's canonical URL.
        // Staff can scan it at pickup to pull up the booking instantly.
        $bookingUrl = route(
            $user->isAdmin() ? 'admin.bookings.show' : 'customer.bookings.show',
            $booking
        );
        $qrDataUri = null;

            try {
             $qrDataUri = QrCodeGenerator::dataUri($bookingUrl, 180);
            } 
            catch (\Throwable $e) {
                \Log::error('Invoice QR generation failed: ' . $e->getMessage());
            }

        $pdf = Pdf::loadView('invoices.booking', [
                'booking' => $booking,
                'qrDataUri' => $qrDataUri,
                'bookingUrl' => $bookingUrl,
            ])
            ->setPaper('a4');

        $filename = "rakz-rental-invoice-{$booking->id}.pdf";

        return $pdf->download($filename);
    }
}
