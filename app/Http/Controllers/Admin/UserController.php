<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('taughtCourses', 'enrollments.course', 'enrollments.grade');
        return view('admin.users.show', compact('user'));
    }

    // ─── Professors CRUD ─────────────────────────────────────────────────────

    public function professors()
    {
        $professors = User::where('role', 'professor')
                         ->withCount('taughtCourses')
                         ->latest()
                         ->paginate(15);

        return view('admin.professors.index', compact('professors'));
    }

    public function createProfessor()
    {
        return view('admin.professors.create');
    }

    public function storeProfessor(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['nullable', Password::min(8)],
            'bio'      => ['nullable', 'string'],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password'] ?? 'password123'),
            'role'     => 'professor',
            'bio'      => $validated['bio'] ?? null,
            'phone'    => $validated['phone'] ?? null,
        ]);

        return redirect()->route('admin.professors.index')
            ->with('success', 'Professor account created successfully.');
    }

    public function editProfessor(User $professor)
    {
        abort_if($professor->role !== 'professor', 404);
        return view('admin.professors.edit', compact('professor'));
    }

    public function updateProfessor(Request $request, User $professor)
    {
        abort_if($professor->role !== 'professor', 404);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $professor->id],
            'password' => ['nullable', Password::min(8)],
            'bio'      => ['nullable', 'string'],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'bio'   => $validated['bio'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $professor->update($data);

        return redirect()->route('admin.professors.index')
            ->with('success', 'Professor updated successfully.');
    }

    public function destroyProfessor(User $professor)
    {
        abort_if($professor->role !== 'professor', 404);

        $professor->delete();

        return redirect()->route('admin.professors.index')
            ->with('success', 'Professor deleted successfully.');
    }
}
