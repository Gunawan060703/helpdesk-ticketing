<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\KategoriMasalah;
use App\Models\User;
use App\Models\TicketRespon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $tickets = Ticket::with('user')
                ->orderBy('tanggal_lapor', 'desc')
                ->paginate(10);
        } else {
            $tickets = Ticket::where('id_user', $user->id_user)
                ->orderBy('tanggal_lapor', 'desc')
                ->paginate(10);
        }

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $kategori = KategoriMasalah::all();
        return view('tickets.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:100',
            'judul_masalah' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'prioritas' => 'required|in:Low,Medium,High'
        ]);

        $ticket = Ticket::create([
            'id_user' => Auth::id(),
            'judul_masalah' => $request->judul_masalah,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'prioritas' => $request->prioritas,
            'status' => 'Open',
            'tanggal_lapor' => Carbon::now()
        ]);

        return redirect()->route('tickets.show', $ticket->id_ticket)
            ->with('success', 'Tiket berhasil dibuat.');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['user', 'responses.admin', 'statusLogs'])
            ->findOrFail($id);
        
        if (!Auth::user()->isAdmin() && $ticket->id_user != Auth::id()) {
            abort(403);
        }
        
        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $kategori = KategoriMasalah::all();
        
        return view('tickets.edit', compact('ticket', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'kategori' => 'required|string|max:100',
            'judul_masalah' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'prioritas' => 'required|in:Low,Medium,High'
        ]);

        $ticket->update($request->all());

        return redirect()->route('tickets.show', $ticket->id_ticket)
            ->with('success', 'Tiket berhasil diperbarui.');
    }

    public function addResponse(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'pesan_respon' => 'required|string'
        ]);

        TicketRespon::create([
            'id_ticket' => $ticket->id_ticket,
            'id_admin' => Auth::id(),
            'pesan_respon' => $request->pesan_respon,
            'tanggal_respon' => Carbon::now()
        ]);

        if ($ticket->status == 'Open') {
            $ticket->updateStatus('In Progress', Auth::id());
        }

        return redirect()->route('tickets.show', $ticket->id_ticket)
            ->with('success', 'Respon berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Open,In Progress,Resolved,Closed'
        ]);

        $ticket->updateStatus($request->status, Auth::id());

        return redirect()->route('tickets.show', $ticket->id_ticket)
            ->with('success', 'Status tiket berhasil diperbarui.');
    }
}