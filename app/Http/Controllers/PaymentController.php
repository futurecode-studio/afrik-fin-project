<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
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
    public function feexpayCallback(Request $request)
    {
        Log::info('FeexPay callback received', $request->all());

        try {
            $result = $this->paymentService->handleFeexpayCallback($request->all());
            $callbackInfo = $result['callback_info'] ?? [];

            if ($request->isMethod('get')) {
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
