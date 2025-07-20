<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportTicketController extends Controller
{
    public function show(SupportTicket $ticket)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        return view('support.show', compact('ticket'));
    }

    public function list()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user has permission using direct DB query
        $hasPermission = DB::table('model_has_permissions')
            ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
            ->where('model_id', Auth::id())
            ->where('model_type', 'App\\Models\\User')
            ->where('permissions.name', 'Complaints')
            ->exists();
            
        if (!$hasPermission) {
            abort(401);
        }

        $tickets = SupportTicket::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('support.list', compact('tickets'));
    }

    public function add()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Check if user has permission using direct DB query
        $hasPermission = DB::table('model_has_permissions')
            ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
            ->where('model_id', Auth::id())
            ->where('model_type', 'App\\Models\\User')
            ->where('permissions.name', 'Complaints')
            ->exists();
            
        if (!$hasPermission) {
            abort(401);
        }

        return view('support.add');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'sent'
        ]);

        return redirect()->route('support.list')
            ->with('success', 'Complaint created successfully!');
    }
}
