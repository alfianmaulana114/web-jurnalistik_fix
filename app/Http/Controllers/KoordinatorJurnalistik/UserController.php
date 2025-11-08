<?php

namespace App\Http\Controllers\KoordinatorJurnalistik;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(): View
    {
        $users = User::latest()->paginate(10);
        return view('koordinator-jurnalistik.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('koordinator-jurnalistik.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nim' => 'required|integer|unique:users',
            'role' => ['required', Rule::in(array_keys(User::getAllRoles()))],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('koordinator-jurnalistik.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('koordinator-jurnalistik.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'nim' => 'required|integer|unique:users,nim,' . $user->id,
            'role' => ['required', Rule::in(array_keys(User::getAllRoles()))],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('koordinator-jurnalistik.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Reset password for a user (by koordinator jurnalistik)
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('koordinator-jurnalistik.users.index')
            ->with('success', 'Password untuk ' . $user->name . ' berhasil direset.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent deletion of the last koordinator jurnalistik
        if ($user->isKoordinatorJurnalistik() && User::where('role', User::ROLE_KOORDINATOR_JURNALISTIK)->count() <= 1) {
            return redirect()->route('koordinator-jurnalistik.users.index')
                ->with('error', 'Tidak dapat menghapus koordinator jurnalistik terakhir.');
        }

        $user->delete();

        return redirect()->route('koordinator-jurnalistik.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}