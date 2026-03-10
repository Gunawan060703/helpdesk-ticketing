<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $data = [
                'totalTickets' => Ticket::count(),
                'openTickets' => Ticket::where('status', 'Open')->count(),
                'inProgressTickets' => Ticket::where('status', 'In Progress')->count(),
                'resolvedTickets' => Ticket::where('status', 'Resolved')->count(),
                'closedTickets' => Ticket::where('status', 'Closed')->count(),
                'recentTickets' => Ticket::with('user')->latest('tanggal_lapor')->take(5)->get(),
                'ticketsByKategori' => Ticket::selectRaw('kategori, count(*) as total')
                    ->groupBy('kategori')
                    ->get()
            ];
        } else {
            $data = [
                'myTickets' => Ticket::where('id_user', $user->id_user)->count(),
                'openTickets' => Ticket::where('id_user', $user->id_user)->where('status', 'Open')->count(),
                'inProgressTickets' => Ticket::where('id_user', $user->id_user)->where('status', 'In Progress')->count(),
                'resolvedTickets' => Ticket::where('id_user', $user->id_user)->where('status', 'Resolved')->count(),
                'closedTickets' => Ticket::where('id_user', $user->id_user)->where('status', 'Closed')->count(),
                'recentTickets' => Ticket::where('id_user', $user->id_user)
                    ->latest('tanggal_lapor')
                    ->take(5)
                    ->get()
            ];
        }

        return view('dashboard', $data);
    }
}