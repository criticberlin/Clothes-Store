<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Process a payment for an order.
     */
    public function process(Request $request, Order $order)
    {
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            return redirect()->route('orders.index');
        }
        
        // Check if order is already paid
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.details', $order)
                ->with('info', 'This order has already been paid.');
        }
        
        // Get payment method
        $paymentMethod = PaymentMethod::find($order->payment_method_id);
        
        if (!$paymentMethod || !$paymentMethod->is_active) {
            return redirect()->route('orders.details', $order)
                ->with('error', 'Invalid payment method.');
        }
        
        // Process payment based on payment method
        switch ($paymentMethod->code) {
            case 'cod':
                // Cash on delivery - no processing needed
                $success = true;
                $message = 'Order confirmed for cash on delivery.';
                break;
                
            case 'card':
                // Process card payment
                $success = $this->processCardPayment($request, $order);
                $message = $success 
                    ? 'Payment processed successfully.' 
                    : 'Payment processing failed. Please try again.';
                break;
                
            case 'bnpl':
                // Process buy now pay later payment
                $success = $this->processBnplPayment($request, $order);
                $message = $success 
                    ? 'Buy now pay later payment set up successfully.' 
                    : 'Buy now pay later setup failed. Please try again.';
                break;
                
            default:
                $success = false;
                $message = 'Unsupported payment method.';
                break;
        }
        
        // Update order payment status
        if ($success) {
            $order->update([
                'payment_status' => 'paid',
                'transaction_id' => Str::uuid(),
                'payment_details' => json_encode([
                    'method' => $paymentMethod->code,
                    'timestamp' => now()->toIso8601String(),
                ]),
            ]);
            
            return redirect()->route('orders.details', $order)
                ->with('success', $message);
        }
        
        return redirect()->route('orders.details', $order)
            ->with('error', $message);
    }
    
    /**
     * Process a card payment.
     */
    private function processCardPayment(Request $request, Order $order)
    {
        // Validate card details
        $request->validate([
            'card_number' => 'required|string|min:13|max:19',
            'card_expiry' => 'required|string|size:5',
            'card_cvv' => 'required|string|size:3',
            'card_holder' => 'required|string|max:255',
        ]);
        
        // In a real implementation, you would integrate with a payment gateway here
        // For now, we'll just simulate a successful payment
        
        return true;
    }
    
    /**
     * Process a buy now pay later payment.
     */
    private function processBnplPayment(Request $request, Order $order)
    {
        // Validate BNPL details
        $request->validate([
            'bnpl_phone' => 'required|string|max:20',
            'bnpl_email' => 'required|email|max:255',
        ]);
        
        // In a real implementation, you would integrate with a BNPL provider here
        // For now, we'll just simulate a successful payment
        
        return true;
    }
    
    /**
     * Show saved payment methods.
     */
    public function savedMethods()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $paymentMethods = PaymentMethod::active()->get();
        
        return view('payment.saved-methods', compact('paymentMethods'));
    }
}
