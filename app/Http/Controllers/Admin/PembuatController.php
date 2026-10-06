<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PembuatController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'pembuat_materi')->withCount('materials');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $creators = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pembuat.index', compact('creators'));
    }

    public function create()
    {
        return view('admin.pembuat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::min(6)],
            'status' => ['required', 'in:active,inactive'],
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pembuat_materi',
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.pembuat.index')->with('success', 'Akun Pembuat Materi berhasil dibuat.');
    }

    public function edit($id)
    {
        $creator = User::where('role', 'pembuat_materi')->findOrFail($id);
        return view('admin.pembuat.edit', compact('creator'));
    }

    public function update(Request $request, $id)
    {
        $creator = User::where('role', 'pembuat_materi')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username,' . $creator->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $creator->id],
            'password' => ['nullable', Password::min(6)],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $creator->update($updateData);

        return redirect()->route('admin.pembuat.index')->with('success', 'Akun Pembuat Materi berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $creator = User::where('role', 'pembuat_materi')->findOrFail($id);
        $newStatus = ($creator->status === 'active') ? 'inactive' : 'active';
        $creator->update(['status' => $newStatus]);

        $statusLabel = ($newStatus === 'active') ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun Pembuat Materi {$creator->name} berhasil {$statusLabel}.");
    }

    public function destroy($id)
    {
        $creator = User::where('role', 'pembuat_materi')->findOrFail($id);
        $creator->delete();

        return redirect()->route('admin.pembuat.index')->with('success', 'Akun Pembuat Materi berhasil dihapus.');
    }
}
