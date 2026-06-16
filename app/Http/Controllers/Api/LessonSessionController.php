<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LessonSession;
use Illuminate\Http\Request;

class LessonSessionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Cari relasi StaffMember dari user yang login
        $staffMember = $user->staffMember;

        if (!$staffMember) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        // Ambil sesi pelajaran yang ditugaskan ke guru tersebut
        $sessions = LessonSession::with(['schoolClass', 'subject', 'teacher'])
            ->where('staff_member_id', $staffMember->id)
            ->orderBy('session_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $staffMember = $user->staffMember;

        if (!$staffMember) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $session = LessonSession::where('id', $id)
            ->where('staff_member_id', $staffMember->id)
            ->first();

        if (!$session) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'status' => 'nullable|string',
            'achievement_percent' => 'nullable|integer|min:0|max:100',
            'execution_notes' => 'nullable|string',
            'homework_notes' => 'nullable|string',
            'issues_notes' => 'nullable|string',
            'actual_start_at' => 'nullable|date',
            'actual_end_at' => 'nullable|date',
        ]);

        $session->update($validated);

        // Pastikan load kembali relasinya agar objek balikan tetap utuh
        $session->load(['schoolClass', 'subject', 'teacher']);

        return response()->json([
            'success' => true,
            'message' => 'Sesi berhasil diperbarui',
            'data' => $session
        ]);
    }
}
