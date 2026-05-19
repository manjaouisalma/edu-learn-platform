<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\User;

use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $professor = auth()->user();
         $courses = \App\Models\Course::where('professor_id', $professor->id)->get();
        $query = Enrollment::with(['student', 'course'])
            ->whereHas('course', fn($q) => $q->where('professor_id', $professor->id));
        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // Filtre statut blocage
        if ($request->filled('filter')) {
            $query->where('is_blocked', $request->filter === 'blocked');
        }

        $enrollments = $query->latest()->get();

        $stats = [
            'total'   => $enrollments->count(),
            'active'  => $enrollments->where('is_blocked', false)->count(),
            'blocked' => $enrollments->where('is_blocked', true)->count(),
        ];

        return view('professor.students.index', compact('enrollments', 'stats','courses'));
    }

    // Ajouter un étudiant et l'inscrire à un cours
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'course_id' => 'required|exists:courses,id',
            'birth_date' => 'nullable|date|before:today',
        ]);

        $student = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'student',
            'birth_date' => $request->birth_date, 
        ]);

        Enrollment::create([
            'student_id' => $student->id,
            'course_id'  => $request->course_id,
        ]);

        return back()->with('success', 'Étudiant ajouté avec succès.');
    }

    // Modifier les infos d'un étudiant
    public function update(Request $request, User $student)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'birth_date' => 'nullable|date|before:today',
        ]);

        $student->update($request->only('name', 'email','birth_date'));

        return back()->with('success', 'Étudiant modifié avec succès.');
    }

    // Bloquer / Débloquer
    public function toggleBlock(Enrollment $enrollment)
    {
        $enrollment->update(['is_blocked' => !$enrollment->is_blocked]);

        $msg = $enrollment->is_blocked ? 'Étudiant bloqué.' : 'Étudiant débloqué.';

        return back()->with('success', $msg);
    }

    // Supprimer l'inscription (pas le compte)
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return back()->with('success', 'Étudiant retiré du cours.');
    }
}
