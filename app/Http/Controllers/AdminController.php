<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\KategoriMasalah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
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

        return view('admin.dashboard', $data);
    }

    public function users()
    {
        $users = User::withCount('tickets')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:tabel_user,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'departemen' => 'nullable|string|max:100'
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'departemen' => $request->departemen,
            'create_at' => Carbon::now()
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:tabel_user,email,' . $user->id_user . ',id_user',
            'role' => 'required|in:user,admin',
            'departemen' => 'nullable|string|max:100'
        ]);

        $data = $request->except('password');
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id_user === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.users')
            ->with('success', 'User berhasil dihapus.');
    }

    public function kategori()
    {
        $kategori = KategoriMasalah::withCount('tickets')->paginate(10);
        return view('admin.kategori.index', compact('kategori'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:tabel_kategori_masalah,nama_kategori'
        ]);

        KategoriMasalah::create($request->all());

        return redirect()->route('admin.kategori')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function updateKategori(Request $request, $id)
    {
        $kategori = KategoriMasalah::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:tabel_kategori_masalah,nama_kategori,' . $kategori->id_kategori . ',id_kategori'
        ]);

        $kategori->update($request->all());

        return redirect()->route('admin.kategori')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function deleteKategori($id)
    {
        $kategori = KategoriMasalah::findOrFail($id);
        
        if ($kategori->tickets()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang memiliki tiket.');
        }

        $kategori->delete();
        return redirect()->route('admin.kategori')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    public function laporan()
    {
        $data = [
            'totalTickets' => Ticket::count(),
            'ticketsByStatus' => Ticket::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->get(),
            'ticketsByPriority' => Ticket::selectRaw('prioritas, count(*) as total')
                ->groupBy('prioritas')
                ->get(),
            'ticketsByKategori' => KategoriMasalah::withCount('tickets')->get(),
            'topUsers' => User::withCount('tickets')
                ->orderBy('tickets_count', 'desc')
                ->limit(5)
                ->get(),
            'monthlyTickets' => Ticket::selectRaw('MONTH(tanggal_lapor) as bulan, YEAR(tanggal_lapor) as tahun, count(*) as total')
                ->groupBy('bulan', 'tahun')
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->limit(6)
                ->get()
        ];

        return view('admin.laporan', $data);
    }
}