<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Jika request JSON, return data untuk DataTables
        if ($request->wantsJson()) {
            $users = User::select(['id', 'username', 'name', 'email', 'role', 'is_locked'])
                ->get();

            return response()->json($users);
        }

        return view('pages.users');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|min:8|max:100|unique:users',
            'name' => 'required|string|min:8|max:100',
            'email' => 'required|string|email|min:8|max:100|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:100',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'role' => 'required|in:admin,operator',
        ]);

        User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|string|min:8|max:100|unique:users,username,' . $user->id,
            'name' => 'required|string|min:8|max:100',
            'email' => 'required|string|email|min:8|max:100|unique:users,email,' . $user->id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:100',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'role' => 'required|in:admin,operator',
        ]);

        $updateData = [
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return response()->json(['success' => true, 'message' => 'User berhasil diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus akun sendiri']);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
    }

    /**
     * Toggle user lock status
     */
    public function toggleLock(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat mengunci akun sendiri']);
        }

        if ($user->is_locked) {
            $user->unlock();
            $message = 'User berhasil di-unlock';
        } else {
            $user->lock();
            $message = 'User berhasil di-lock';
        }

        return response()->json(['success' => true, 'message' => $message]);
    }
}
