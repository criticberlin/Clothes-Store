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
        $ticket->load(['user', 'replies.user']);
        
        // Mark ticket as read if it was new
        if ($ticket->status === 'open') {
            $ticket->status = 'pending';
            $ticket->save();
        }
        
        return view('admin.support.show', compact('ticket'));
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
        
        $reply = new SupportTicketReply();
        $reply->support_ticket_id = $ticket->id;
        $reply->user_id = Auth::id();
        $reply->message = $request->message;
        $reply->is_admin = true;
        $reply->save();
        
        // Update ticket status
        $ticket->status = $request->resolve ? 'resolved' : 'pending';
        $ticket->save();
        
        return redirect()->route('admin.support.show', $ticket)
            ->with('success', 'Reply sent successfully.');
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
        $ticket->status = 'resolved';
        $ticket->save();
        
        if ($request->message) {
            $reply = new SupportTicketReply();
            $reply->support_ticket_id = $ticket->id;
            $reply->user_id = Auth::id();
            $reply->message = $request->message;
            $reply->is_admin = true;
            $reply->save();
        }
        
        return redirect()->route('admin.support.show', $ticket)
            ->with('success', 'Ticket closed successfully.');
    }
}
