<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Services\EventPaymentService;
use App\Models\EventOrder;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Callback KKiaPay (webhook)
     */
    public function kkiapayCallback(Request $request)
    {
        Log::info('KKiaPay callback received', $request->all());

        try {
            $result = $this->paymentService->handleKkiapayCallback($request->all());

            if ($result['success']) {
                return response()->json(['status' => 'success', 'message' => 'Payment processed']);
            }

            return response()->json(['status' => 'error', 'message' => $result['message']], 400);

        } catch (\Exception $e) {
            Log::error('KKiaPay callback error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal error'], 500);
        }
    }

    /**
     * Callback FedaPay (webhook)
     */
    public function fedapayCallback(Request $request)
    {
        Log::info('FedaPay callback received', $request->all());

        try {
            $result = $this->paymentService->handleFedapayCallback($request->all());

            if ($result['success']) {
                return response()->json(['status' => 'success', 'message' => 'Payment processed']);
            }

            return response()->json(['status' => 'error', 'message' => $result['message']], 400);

        } catch (\Exception $e) {
            Log::error('FedaPay callback error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Internal error'], 500);
        }
    }

    /**
     * Callback / retour FeexPay.
     */
    public function feexpayCallback(Request $request, EventPaymentService $eventPaymentService)
    {
        Log::info('FeexPay callback received', $request->all());

        try {
            $result = $this->paymentService->handleFeexpayCallback($request->all());
            $callbackInfo = $result['callback_info'] ?? [];

            if (($result['success'] ?? false) && is_array($callbackInfo)) {
                $type = $callbackInfo['type'] ?? null;
                $reference = (string) ($callbackInfo['reference'] ?? $result['reference'] ?? '');

                if ($reference !== '' && in_array($type, ['event_registration', 'event_order'], true)) {
                    $eventPaymentService->handlePaymentSuccess($reference, [
                        'registration_id' => $callbackInfo['registration_id'] ?? null,
                        'order_id' => $callbackInfo['order_id'] ?? null,
                        'transaction_id' => $result['provider_reference'] ?? ($request->input('reference') ?: null),
                        'status' => $result['status'] ?? 'SUCCESSFUL',
                    ]);
                }
            }

            if ($request->isMethod('get')) {
                if (($callbackInfo['type'] ?? null) === 'event_registration' && ! empty($callbackInfo['registration_id'])) {
                    $registration = EventRegistration::find($callbackInfo['registration_id']);
                    if ($registration) {
                        return redirect()->route('event.ticket.public', $registration->qr_code);
                    }
                }

                if (($callbackInfo['type'] ?? null) === 'event_order' && ! empty($callbackInfo['order_id'])) {
                    $order = EventOrder::find($callbackInfo['order_id']);
                    if ($order) {
                        return redirect()->route('event.order.confirmation', $order->order_number);
                    }
                }

                return redirect()->route('payment.success', [
                    'reference' => $callbackInfo['reference'] ?? $request->input('reference'),
                ]);
            }

            if ($result['success'] ?? false) {
                return response()->json(['status' => 'success', 'message' => 'Payment processed']);
            }

            if ($result['pending'] ?? false) {
                return response()->json(['status' => 'pending', 'message' => $result['message'] ?? 'Payment pending']);
            }

            return response()->json(['status' => 'error', 'message' => $result['message'] ?? 'Payment failed'], 400);
        } catch (\Exception $e) {
            Log::error('FeexPay callback error: ' . $e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Internal error'], 500);
        }
    }

    /**
     * Page d'annulation de paiement
     */
    public function cancel(Request $request)
    {
        return redirect()->route('formations')->with('error', 'Le paiement a été annulé.');
    }
}
