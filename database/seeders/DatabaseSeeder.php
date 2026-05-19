<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@eduflow.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'bio'      => 'Platform administrator.',
        ]);

        // ── Professors ─────────────────────────────────────────────────────
        $professor1 = User::create([
            'name'     => 'Dr. Sarah Johnson',
            'email'    => 'professor@eduflow.com',
            'password' => Hash::make('password'),
            'role'     => 'professor',
            'bio'      => 'Expert in Web Development with 10+ years of experience.',
            'phone'    => '+1-555-0101',
        ]);

        $professor2 = User::create([
            'name'     => 'Prof. Ahmed El Mansouri',
            'email'    => 'ahmed@eduflow.com',
            'password' => Hash::make('password'),
            'role'     => 'professor',
            'bio'      => 'Data Science and Machine Learning specialist.',
            'phone'    => '+212-600-000001',
        ]);

        // ── Students ───────────────────────────────────────────────────────
        $student1 = User::create([
            'name'     => 'Alice Martin',
            'email'    => 'student@eduflow.com',
            'password' => Hash::make('password'),
            'role'     => 'student',
            'bio'      => 'Aspiring full-stack developer.',
        ]);

        $student2 = User::create([
            'name'     => 'Bob Wilson',
            'email'    => 'bob@eduflow.com',
            'password' => Hash::make('password'),
            'role'     => 'student',
        ]);

        $student3 = User::create([
            'name'     => 'Fatima Zahra',
            'email'    => 'fatima@eduflow.com',
            'password' => Hash::make('password'),
            'role'     => 'student',
        ]);

        // ── Courses ────────────────────────────────────────────────────────
        $course1 = Course::create([
            'title'          => 'Laravel from Zero to Hero',
            'description'    => 'Master Laravel PHP framework from the basics to advanced features including Eloquent ORM, Blade templates, REST APIs, and deployment.',
            'professor_id'   => $professor1->id,
            'category'       => 'Web Development',
            'level'          => 'beginner',
            'duration_hours' => 40,
            'price'          => 0,
            'is_published'   => true,
            'max_students'   => 100,
        ]);

        $course2 = Course::create([
            'title'          => 'React & Tailwind CSS Masterclass',
            'description'    => 'Build modern, responsive web apps with React 18 and Tailwind CSS. Covers hooks, context API, Tailwind components, and deployment.',
            'professor_id'   => $professor1->id,
            'category'       => 'Web Development',
            'level'          => 'intermediate',
            'duration_hours' => 35,
            'price'          => 49.99,
            'is_published'   => true,
        ]);

        $course3 = Course::create([
            'title'          => 'Python for Data Science',
            'description'    => 'Learn Python, NumPy, Pandas, Matplotlib, and Scikit-Learn. Work on real-world datasets and build ML models from scratch.',
            'professor_id'   => $professor2->id,
            'category'       => 'Data Science',
            'level'          => 'beginner',
            'duration_hours' => 50,
            'price'          => 0,
            'is_published'   => true,
        ]);

        $course4 = Course::create([
            'title'          => 'Machine Learning with TensorFlow',
            'description'    => 'Deep dive into neural networks, CNNs, RNNs and Transformers using TensorFlow 2 and Keras. Build and deploy production ML models.',
            'professor_id'   => $professor2->id,
            'category'       => 'Data Science',
            'level'          => 'advanced',
            'duration_hours' => 60,
            'price'          => 79.99,
            'is_published'   => true,
        ]);

        $course5 = Course::create([
            'title'          => 'UI/UX Design Fundamentals',
            'description'    => 'Master Figma, design principles, wireframing, prototyping, and user research. Create beautiful, accessible user interfaces.',
            'professor_id'   => $professor1->id,
            'category'       => 'Design',
            'level'          => 'beginner',
            'duration_hours' => 25,
            'price'          => 29.99,
            'is_published'   => true,
        ]);

        // ── Enrollments ────────────────────────────────────────────────────
        $e1 = Enrollment::create([
            'student_id'  => $student1->id,
            'course_id'   => $course1->id,
            'status'      => 'completed',
            'enrolled_at' => now()->subDays(30),
            'completed_at'=> now()->subDays(5),
        ]);

        $e2 = Enrollment::create([
            'student_id'  => $student1->id,
            'course_id'   => $course3->id,
            'status'      => 'active',
            'enrolled_at' => now()->subDays(10),
        ]);

        $e3 = Enrollment::create([
            'student_id'  => $student2->id,
            'course_id'   => $course1->id,
            'status'      => 'active',
            'enrolled_at' => now()->subDays(15),
        ]);

        $e4 = Enrollment::create([
            'student_id'  => $student2->id,
            'course_id'   => $course3->id,
            'status'      => 'completed',
            'enrolled_at' => now()->subDays(45),
            'completed_at'=> now()->subDays(10),
        ]);

        $e5 = Enrollment::create([
            'student_id'  => $student3->id,
            'course_id'   => $course2->id,
            'status'      => 'active',
            'enrolled_at' => now()->subDays(7),
        ]);

        $e6 = Enrollment::create([
            'student_id'  => $student3->id,
            'course_id'   => $course4->id,
            'status'      => 'active',
            'enrolled_at' => now()->subDays(20),
        ]);

        // ── Grades ─────────────────────────────────────────────────────────
        Grade::create([
            'enrollment_id' => $e1->id,
            'student_id'    => $student1->id,
            'course_id'     => $course1->id,
            'professor_id'  => $professor1->id,
            'score'         => 92.5,
            'feedback'      => 'Excellent work! You demonstrated a strong understanding of Laravel fundamentals and built a solid project.',
        ]);

        Grade::create([
            'enrollment_id' => $e4->id,
            'student_id'    => $student2->id,
            'course_id'     => $course3->id,
            'professor_id'  => $professor2->id,
            'score'         => 78.0,
            'feedback'      => 'Good effort. Your Pandas skills are solid; work on the data visualization section for improvement.',
        ]);
    }
}
