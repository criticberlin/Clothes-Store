<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Support\Facades\Auth;

class AdminSupportTicketController extends Controller
{
    /**
     * Display a listing of the support tickets.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $status = $request->status;
        
        $query = SupportTicket::with('user')->latest();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $tickets = $query->paginate(10);
        
        return view('admin.support.index', compact('tickets'));
    }

    /**
     * Display the specified support ticket.
     *
     * @param  \App\Models\SupportTicket  $ticket
     * @return \Illuminate\View\View
     */
    public function show(SupportTicket $ticket)
    {
        try {
            // First try to load with replies
            $ticket->load(['user']);
            
            // Check if the replies relationship exists and the table exists
            try {
                $ticket->load(['replies.user']);
            } catch (\Exception $e) {
                // If there's an error, we'll continue without loading replies
                // This allows the view to still work even if replies table doesn't exist
            }
            
            // Mark ticket as read if it was new
            if ($ticket->status === 'open') {
                $ticket->status = 'pending';
                $ticket->save();
            }
            
            return view('admin.support.show', compact('ticket'));
        } catch (\Exception $e) {
            return redirect()->route('admin.support.index')
                ->with('error', 'Error loading ticket: ' . $e->getMessage());
        }
    }

    /**
     * Reply to a support ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupportTicket  $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
        ]);
        
        try {
            // Try to create a reply if the table exists
            try {
                $reply = new SupportTicketReply();
                $reply->support_ticket_id = $ticket->id;
                $reply->user_id = Auth::id();
                $reply->message = $request->message;
                $reply->is_admin = true;
                $reply->save();
            } catch (\Exception $e) {
                // If the table doesn't exist, update the ticket with admin reply
                $ticket->admin_reply = $request->message;
                $ticket->admin_id = Auth::id();
            }
            
            // Update ticket status
            $ticket->status = $request->resolve ? 'resolved' : 'pending';
            $ticket->save();
            
            return redirect()->route('admin.support.show', $ticket)
                ->with('success', 'Reply sent successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.support.show', $ticket)
                ->with('error', 'Error sending reply: ' . $e->getMessage());
        }
    }

    /**
     * Close a support ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupportTicket  $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function close(Request $request, SupportTicket $ticket)
    {
        try {
            $ticket->status = 'resolved';
            
            if ($request->message) {
                // Try to create a reply if the table exists
                try {
                    $reply = new SupportTicketReply();
                    $reply->support_ticket_id = $ticket->id;
                    $reply->user_id = Auth::id();
                    $reply->message = $request->message;
                    $reply->is_admin = true;
                    $reply->save();
                } catch (\Exception $e) {
                    // If the table doesn't exist, update the ticket with admin reply
                    $ticket->admin_reply = $request->message;
                    $ticket->admin_id = Auth::id();
                }
            }
            
            $ticket->save();
            
            return redirect()->route('admin.support.show', $ticket)
                ->with('success', 'Ticket closed successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.support.show', $ticket)
                ->with('error', 'Error closing ticket: ' . $e->getMessage());
        }
    }
}
