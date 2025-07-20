<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SupportTicket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get role IDs
        $customerRoleId = DB::table('roles')->where('name', 'customer')->value('id');
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        
        // Get customer and admin users
        $customerIds = DB::table('model_has_roles')
            ->where('role_id', $customerRoleId)
            ->where('model_type', 'App\\Models\\User')
            ->pluck('model_id')
            ->toArray();
        
        $adminIds = DB::table('model_has_roles')
            ->where('role_id', $adminRoleId)
            ->where('model_type', 'App\\Models\\User')
            ->pluck('model_id')
            ->toArray();
        
        $customers = User::whereIn('id', $customerIds)->get();
        $admins = User::whereIn('id', $adminIds)->get();
        
        $ticketSubjects = [
            'Order Delivery Issue',
            'Product Quality Concern',
            'Return Request',
            'Payment Problem',
            'Account Access Issue',
            'Product Availability Question',
            'Size Guide Question',
            'Shipping Delay',
            'Damaged Product',
            'Wrong Item Received',
        ];
        
        $ticketMessages = [
            'I have not received my order yet. Could you please check the status?',
            'The product I received has a defect. How can I return it?',
            'I would like to return an item. What is the procedure?',
            'My payment was processed but the order status is still pending.',
            'I cannot access my account. Please help.',
            'When will this product be back in stock?',
            'The size chart is confusing. Can you help me choose the right size?',
            'My order is taking longer than expected to arrive.',
            'I received a damaged product. What should I do?',
            'I received the wrong item in my order.',
        ];
        
        $adminReplies = [
            'We apologize for the delay. Your order is currently being processed and will be shipped soon.',
            'We\'re sorry to hear about the defect. Please send us photos and we will arrange a return.',
            'To return an item, please fill out the return form in your account dashboard.',
            'We will check your payment status and update your order immediately.',
            'We\'ve reset your account access. Please check your email for instructions.',
            'The product will be back in stock within 2 weeks.',
            'Based on your measurements, we recommend size M for this product.',
            'We apologize for the delay. Your order has been expedited.',
            'We\'re sorry about the damage. We will send a replacement immediately.',
            'We apologize for the mix-up. We will arrange for the correct item to be sent.',
        ];
        
        foreach ($customers as $customer) {
            // Create 0-3 tickets per customer
            $ticketCount = rand(0, 3);
            
            for ($i = 0; $i < $ticketCount; $i++) {
                $index = rand(0, count($ticketSubjects) - 1);
                $status = ['open', 'in_progress', 'closed'][rand(0, 2)];
                
                $ticket = SupportTicket::create([
                    'user_id' => $customer->id,
                    'subject' => $ticketSubjects[$index],
                    'message' => $ticketMessages[$index],
                    'status' => $status,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
                
                // Add admin reply for in_progress or closed tickets
                if ($status !== 'open' && $admins->isNotEmpty()) {
                    $admin = $admins->random();
                    $ticket->update([
                        'admin_reply' => $adminReplies[$index],
                        'admin_id' => $admin->id,
                        'updated_at' => $ticket->created_at->addDays(rand(1, 3)),
                    ]);
                }
            }
        }
    }
} 