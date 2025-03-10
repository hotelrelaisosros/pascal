<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Subjects;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index($id = null)
    {
        $students = DB::select("SELECT * FROM students");

        $user_sub = [];
        foreach ($students as $student) {
            $user_sub[$student->id] = DB::table('subjects')->where('id', $student->subject_id)->value('name');
        }
        $subjects = DB::select("SELECT * FROM subjects");

        $individualStudent = DB::select("SELECT * FROM students WHERE id = ?", [$id]);
        return view('students.index', compact('students', 'individualStudent', 'subjects'));
    }

    public function add_subject($ids)
    {

        $existingSubjects = Subjects::whereIn('id', $ids)->pluck('id')->toArray();

        // Find missing IDs
        $missingSubjects = array_diff($ids, $existingSubjects);

        if (!empty($missingSubjects)) {
            return response()->json([
                'error' => 'Some subjects do not exist.',
                'missing_ids' => $missingSubjects
            ], 400);
        }

        return redirect()->route('students.index')->with('success', 'Subjects updated');
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => "required|string|max:255",
            'email' => 'required|string|email|max:255|unique:students',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        }

        if ($request->id) {
            $student = Student::findOrFail($request->id);

            if ($request->hasFile('image')) {
                if ($student->image && Storage::disk('public')->exists($student->image)) {
                    Storage::disk('public')->delete($student->image);
                }
                $student->image = $request->file('image')->store('step1/Student_types', 'public');
            }

            $student->update($request->only(['name', 'email', 'phone', 'address']));
        } else {
            $imagePath = $request->file('image')->store('step1/Student_types', 'public');

            Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'image' => $imagePath,
            ]);
        }



        return redirect()->route('students.index')->with('success', 'Student created successfully!');
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'nullable|string|max:20',

            'email' => 'nullable|string|email|max:255|unique:students,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($valid->fails()) {
            return redirect()->back()->withErrors($valid)->withInput();
        }

        $student = Student::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($student->image && Storage::disk('public')->exists($student->image)) {
                Storage::disk('public')->delete($student->image);
            }
            $student->image = $request->file('image')->store('step1/Student_types', 'public');
        }

        $student->update($request->only(['name', 'email', 'phone', 'address']));

        return redirect()->route('students.index')->with('success', 'Student updated successfully!');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        if ($student->image && Storage::disk('public')->exists($student->image)) {
            Storage::disk('public')->delete($student->image);
        }
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully!');
    }
}
