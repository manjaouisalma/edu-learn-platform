<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('professor')->withCount('enrollments');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
        }

        $courses = $query->latest()->paginate(15)->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $professors = User::where('role', 'professor')->orderBy('name')->get();
        return view('admin.courses.create', compact('professors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['required', 'string'],
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'professor_id'   => ['required', 'exists:users,id'],
            'category'       => ['required', 'string', 'max:100'],
            'level'          => ['required', 'in:beginner,intermediate,advanced'],
            //'duration_hours' => ['required', 'integer', 'min:1'],
            'price'          => ['required', 'numeric', 'min:0'],
            'is_published'   => ['boolean'],
            'max_students'   => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_free'] = ($validated['price'] == 0);

         if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
            ->store('courses', 'public');
        }

        Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        $professors = User::where('role', 'professor')->orderBy('name')->get();
        return view('admin.courses.edit', compact('course', 'professors'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['required', 'string'],
             'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'professor_id'   => ['required', 'exists:users,id'],
            'category'       => ['required', 'string', 'max:100'],
            'level'          => ['required', 'in:beginner,intermediate,advanced'],
            //'duration_hours' => ['required', 'integer', 'min:1'],
            'price'          => ['required', 'numeric', 'min:0'],
            'is_published'   => ['boolean'],
            'max_students'   => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        //  Remove image
        if ($request->boolean('remove_image') && $course->image) {
            Storage::disk('public')->delete($course->image);
            $validated['image'] = null;
        }

         //  New image uploaded
    if ($request->hasFile('image')) {
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }
        $validated['image'] = $request->file('image')
                                ->store('courses', 'public');
    }

        $validated['is_free'] = ($validated['price'] == 0);

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}