<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LessonSession;
use App\Models\ClassMaterial;
use App\Models\ClassAssignment;
use App\Models\SessionAssessment;
use App\Models\LessonSessionCase;
use App\Models\Student;
use Illuminate\Http\Request;

class SessionRelationController extends Controller
{
    // ==========================================
    // GET AVAILABLE ITEMS FOR DROPDOWN
    // ==========================================
    public function availableMaterials()
    {
        return response()->json([
            'success' => true,
            'data' => ClassMaterial::select('id', 'title', 'type')->orderBy('title')->get()
        ]);
    }

    public function availableAssignments()
    {
        return response()->json([
            'success' => true,
            'data' => ClassAssignment::select('id', 'title', 'type')->orderBy('title')->get()
        ]);
    }

    public function availableStudents(Request $request)
    {
        // Filter by school_class_id if provided
        $classId = $request->query('school_class_id');
        $query = Student::select('id', 'name', 'nis');
        
        if ($classId) {
            $query->where('school_class_id', $classId);
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('name')->get()
        ]);
    }

    // ==========================================
    // MATERIALS
    // ==========================================
    public function attachMaterial(Request $request, $sessionId)
    {
        $session = LessonSession::findOrFail($sessionId);
        $materialId = $request->input('material_id');
        
        if (!$session->materials()->where('class_material_id', $materialId)->exists()) {
            $session->materials()->attach($materialId, ['order' => 1]);
        }
        
        return response()->json(['success' => true, 'message' => 'Materi ditambahkan']);
    }

    public function detachMaterial($sessionId, $materialId)
    {
        $session = LessonSession::findOrFail($sessionId);
        $session->materials()->detach($materialId);
        return response()->json(['success' => true, 'message' => 'Materi dihapus']);
    }

    // ==========================================
    // ASSIGNMENTS
    // ==========================================
    public function attachAssignment(Request $request, $sessionId)
    {
        $session = LessonSession::findOrFail($sessionId);
        $assignmentId = $request->input('assignment_id');

        if (!$session->assignments()->where('class_assignment_id', $assignmentId)->exists()) {
            $session->assignments()->attach($assignmentId, ['given_at' => now()]);
        }

        return response()->json(['success' => true, 'message' => 'Tugas ditambahkan']);
    }

    public function detachAssignment($sessionId, $assignmentId)
    {
        $session = LessonSession::findOrFail($sessionId);
        $session->assignments()->detach($assignmentId);
        return response()->json(['success' => true, 'message' => 'Tugas dihapus']);
    }

    // ==========================================
    // ASSESSMENTS
    // ==========================================
    public function storeAssessment(Request $request, $sessionId)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string',
            'domain' => 'required|string',
            'max_score' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $assessment = SessionAssessment::create([
            'lesson_session_id' => $sessionId,
            'type' => $request->type,
            'title' => $request->title,
            'domain' => $request->domain,
            'max_score' => $request->max_score,
            'notes' => $request->notes,
        ]);

        return response()->json(['success' => true, 'message' => 'Assessment dibuat', 'data' => $assessment]);
    }

    public function deleteAssessment($sessionId, $assessmentId)
    {
        SessionAssessment::where('lesson_session_id', $sessionId)->where('id', $assessmentId)->delete();
        return response()->json(['success' => true, 'message' => 'Assessment dihapus']);
    }

    // ==========================================
    // CASES
    // ==========================================
    public function storeCase(Request $request, $sessionId)
    {
        $request->validate([
            'student_id' => 'required|integer',
            'problem' => 'required|string',
            'status' => 'required|string',
            'follow_up' => 'nullable|string',
        ]);

        $case = LessonSessionCase::create([
            'lesson_session_id' => $sessionId,
            'student_id' => $request->student_id,
            'problem' => $request->problem,
            'status' => $request->status,
            'follow_up' => $request->follow_up,
        ]);

        return response()->json(['success' => true, 'message' => 'Kasus dicatat', 'data' => $case]);
    }

    public function deleteCase($sessionId, $caseId)
    {
        LessonSessionCase::where('lesson_session_id', $sessionId)->where('id', $caseId)->delete();
        return response()->json(['success' => true, 'message' => 'Kasus dihapus']);
    }
}
