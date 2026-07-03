import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:mobile_app/core/network/api_client.dart';
import 'package:mobile_app/features/lesson_session/models/lesson_session_model.dart';
import 'package:mobile_app/features/lesson_session/providers/lesson_session_provider.dart';
import 'package:go_router/go_router.dart';

class LessonDetailScreen extends ConsumerStatefulWidget {
  final LessonSessionModel session;
  const LessonDetailScreen({super.key, required this.session});
  @override
  ConsumerState<LessonDetailScreen> createState() => _LessonDetailScreenState();
}

class _LessonDetailScreenState extends ConsumerState<LessonDetailScreen> {
  late String _status;
  late double _achievementPercent;
  late TextEditingController _executionNotesCtrl;
  late TextEditingController _homeworkNotesCtrl;
  late TextEditingController _issuesNotesCtrl;
  late TextEditingController _assessmentPlanCtrl;
  late TextEditingController _notesCtrl;
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _status = widget.session.status;
    _achievementPercent = widget.session.achievementPercent.toDouble();
    _executionNotesCtrl = TextEditingController(text: widget.session.executionNotes ?? '');
    _homeworkNotesCtrl = TextEditingController(text: widget.session.homeworkNotes ?? '');
    _issuesNotesCtrl = TextEditingController(text: widget.session.issuesNotes ?? '');
    _assessmentPlanCtrl = TextEditingController(text: widget.session.assessmentPlan ?? '');
    _notesCtrl = TextEditingController(text: widget.session.notes ?? '');
  }

  @override
  void dispose() {
    _executionNotesCtrl.dispose();
    _homeworkNotesCtrl.dispose();
    _issuesNotesCtrl.dispose();
    _assessmentPlanCtrl.dispose();
    _notesCtrl.dispose();
    super.dispose();
  }

  Future<void> _saveReport() async {
    setState(() => _isLoading = true);
    try {
      await ref.read(lessonSessionsProvider.notifier).updateSession(widget.session.id, {
        'status': _status,
        'achievement_percent': _achievementPercent.toInt(),
        'execution_notes': _executionNotesCtrl.text,
        'homework_notes': _homeworkNotesCtrl.text,
        'issues_notes': _issuesNotesCtrl.text,
        'assessment_plan': _assessmentPlanCtrl.text,
        'notes': _notesCtrl.text,
      });
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Laporan disimpan')));
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString()), backgroundColor: Colors.red));
    } finally {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    // Cari data ter-update dari provider jika ada (karena ref.invalidateSelf akan me-rebuild dengan data baru)
    final sessions = ref.watch(lessonSessionsProvider).value ?? [];
    final currentSession = sessions.firstWhere((s) => s.id == widget.session.id, orElse: () => widget.session);

    return Scaffold(
      backgroundColor: const Color(0xFFF8F9FA),
      appBar: AppBar(
        title: const Text('Detail Sesi', style: TextStyle(color: Colors.black87, fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        elevation: 0,
        centerTitle: false,
        iconTheme: const IconThemeData(color: Colors.black87),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            _buildHeader(currentSession),
            const SizedBox(height: 16),
            _buildReportAccordion(),
            const SizedBox(height: 16),
            _buildMaterialsAccordion(currentSession),
            const SizedBox(height: 16),
            _buildAssignmentsAccordion(currentSession),
            const SizedBox(height: 16),
            _buildAssessmentsAccordion(currentSession),
            const SizedBox(height: 16),
            _buildCasesAccordion(currentSession),
            const SizedBox(height: 32),
          ],
        ),
      ),
    );
  }

  Widget _buildHeader(LessonSessionModel session) {
    String timeRange = '${session.startTime ?? '00:00'} - ${session.endTime ?? '00:00'}';
    
    String formattedDate = session.sessionDate ?? '-';
    if (session.sessionDate != null) {
      try {
        DateTime d = DateTime.parse(session.sessionDate!);
        formattedDate = '${d.month.toString().padLeft(2, '0')}-${d.day.toString().padLeft(2, '0')}-${d.year}';
      } catch (_) {}
    }

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
      child: Column(
        children: [
          Row(
            children: [
              const Icon(Icons.class_outlined, color: Colors.blue),
              const SizedBox(width: 8),
              Expanded(child: Text(session.schoolClassName ?? '-', style: const TextStyle(fontWeight: FontWeight.bold), maxLines: 1, overflow: TextOverflow.ellipsis)),
              const SizedBox(width: 8),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(color: Colors.blue.withOpacity(0.1), borderRadius: BorderRadius.circular(12)),
                child: Text(session.status.toUpperCase(), style: const TextStyle(fontSize: 10, color: Colors.blue)),
              )
            ],
          ),
          const Divider(height: 24),
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('Mata Pelajaran', style: TextStyle(color: Colors.grey)),
              const SizedBox(width: 16),
              Expanded(child: Text(session.subjectName ?? '-', style: const TextStyle(fontWeight: FontWeight.bold), textAlign: TextAlign.right)),
            ],
          ),
          const SizedBox(height: 8),
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('Topik', style: TextStyle(color: Colors.grey)),
              const SizedBox(width: 16),
              Expanded(child: Text(session.topic ?? '-', style: const TextStyle(fontWeight: FontWeight.bold), textAlign: TextAlign.right)),
            ],
          ),
          const SizedBox(height: 8),
          Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text('Tanggal & Waktu', style: TextStyle(color: Colors.grey)),
              const SizedBox(width: 16),
              Expanded(child: Text('$formattedDate / $timeRange', style: const TextStyle(fontWeight: FontWeight.bold), textAlign: TextAlign.right)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildReportAccordion() {
    return Container(
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
      child: ExpansionTile(
        title: const Text('Laporan Pelaksanaan', style: TextStyle(fontWeight: FontWeight.bold)),
        leading: const Icon(Icons.assignment_turned_in_outlined, color: Colors.orange),
        childrenPadding: const EdgeInsets.all(16),
        children: [
          DropdownButtonFormField<String>(
            value: _status,
            decoration: const InputDecoration(labelText: 'Status Sesi', border: OutlineInputBorder()),
            items: const [
              DropdownMenuItem(value: 'draft', child: Text('Draft')),
              DropdownMenuItem(value: 'published', child: Text('Published')),
              DropdownMenuItem(value: 'ongoing', child: Text('Sedang Berlangsung')),
              DropdownMenuItem(value: 'completed', child: Text('Selesai')),
              DropdownMenuItem(value: 'cancelled', child: Text('Dibatalkan')),
            ],
            onChanged: (val) { if (val != null) setState(() => _status = val); },
          ),
          const SizedBox(height: 16),
          const Text('Pencapaian Belajar', style: TextStyle(fontWeight: FontWeight.bold)),
          Row(
            children: [
              Expanded(
                child: Slider(
                  value: _achievementPercent, min: 0, max: 100, divisions: 20,
                  label: '${_achievementPercent.round()}%',
                  onChanged: (val) => setState(() => _achievementPercent = val),
                ),
              ),
              Text('${_achievementPercent.round()}%', style: const TextStyle(fontWeight: FontWeight.bold)),
            ],
          ),
          const SizedBox(height: 16),
          const Divider(),
          const SizedBox(height: 8),
          const Text('Catatan Tambahan', style: TextStyle(fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          TextFormField(
            controller: _assessmentPlanCtrl, maxLines: 2,
            decoration: const InputDecoration(labelText: 'Rencana Penilaian', border: OutlineInputBorder()),
          ),
          const SizedBox(height: 8),
          TextFormField(
            controller: _notesCtrl, maxLines: 2,
            decoration: const InputDecoration(labelText: 'Catatan Rencana', border: OutlineInputBorder()),
          ),
          const SizedBox(height: 8),
          TextFormField(
            controller: _executionNotesCtrl, maxLines: 2,
            decoration: const InputDecoration(labelText: 'Catatan Pelaksanaan', border: OutlineInputBorder()),
          ),
          const SizedBox(height: 8),
          TextFormField(
            controller: _homeworkNotesCtrl, maxLines: 2,
            decoration: const InputDecoration(labelText: 'Pekerjaan Rumah (PR)', border: OutlineInputBorder()),
          ),
          const SizedBox(height: 8),
          TextFormField(
            controller: _issuesNotesCtrl, maxLines: 2,
            decoration: const InputDecoration(labelText: 'Kendala / Isu', border: OutlineInputBorder()),
          ),
          const SizedBox(height: 16),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: _isLoading ? null : _saveReport,
              style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFF0047AB), foregroundColor: Colors.white),
              child: _isLoading ? const CircularProgressIndicator(color: Colors.white) : const Text('Simpan Laporan'),
            ),
          )
        ],
      ),
    );
  }

  Widget _buildMaterialsAccordion(LessonSessionModel session) {
    return Container(
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
      child: ExpansionTile(
        title: Text('Materi (${session.materials.length})', style: const TextStyle(fontWeight: FontWeight.bold)),
        leading: const Icon(Icons.library_books_outlined, color: Colors.blue),
        children: [
          ...session.materials.map((m) => ListTile(
            title: Text(m['title'] ?? '-'),
            trailing: IconButton(
              icon: const Icon(Icons.delete_outline, color: Colors.red),
              onPressed: () => ref.read(lessonSessionsProvider.notifier).detachMaterial(session.id, m['id']),
            ),
          )),
          TextButton.icon(
            onPressed: () => _showMaterialBottomSheet(session.id),
            icon: const Icon(Icons.add), label: const Text('Tambah Materi'),
          ),
          const SizedBox(height: 8),
        ],
      ),
    );
  }

  Widget _buildAssignmentsAccordion(LessonSessionModel session) {
    return Container(
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
      child: ExpansionTile(
        title: Text('Tugas (${session.assignments.length})', style: const TextStyle(fontWeight: FontWeight.bold)),
        leading: const Icon(Icons.assignment_outlined, color: Colors.green),
        children: [
          ...session.assignments.map((m) => ListTile(
            title: Text(m['title'] ?? '-'),
            trailing: IconButton(
              icon: const Icon(Icons.delete_outline, color: Colors.red),
              onPressed: () => ref.read(lessonSessionsProvider.notifier).detachAssignment(session.id, m['id']),
            ),
          )),
          TextButton.icon(
            onPressed: () => _showAssignmentBottomSheet(session.id),
            icon: const Icon(Icons.add), label: const Text('Tambah Tugas'),
          ),
          const SizedBox(height: 8),
        ],
      ),
    );
  }

  Widget _buildAssessmentsAccordion(LessonSessionModel session) {
    return Container(
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
      child: ExpansionTile(
        title: Text('Assessment (${session.assessments.length})', style: const TextStyle(fontWeight: FontWeight.bold)),
        leading: const Icon(Icons.assessment_outlined, color: Colors.purple),
        children: [
          ...session.assessments.map((m) => ListTile(
            title: Text(m['title'] ?? '-'),
            subtitle: Text('${m['type']} - Max: ${m['max_score']}'),
            trailing: IconButton(
              icon: const Icon(Icons.delete_outline, color: Colors.red),
              onPressed: () => ref.read(lessonSessionsProvider.notifier).deleteAssessment(session.id, m['id']),
            ),
          )),
          TextButton.icon(
            onPressed: () => _showAssessmentBottomSheet(session.id),
            icon: const Icon(Icons.add), label: const Text('Buat Assessment Baru'),
          ),
          const SizedBox(height: 8),
        ],
      ),
    );
  }

  Widget _buildCasesAccordion(LessonSessionModel session) {
    return Container(
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
      child: ExpansionTile(
        title: Text('Kasus Siswa (${session.cases.length})', style: const TextStyle(fontWeight: FontWeight.bold)),
        leading: const Icon(Icons.personal_injury_outlined, color: Colors.redAccent),
        children: [
          ...session.cases.map((m) => ListTile(
            title: Text(m['student']?['name'] ?? 'Siswa'),
            subtitle: Text(m['problem'] ?? '-'),
            trailing: IconButton(
              icon: const Icon(Icons.delete_outline, color: Colors.red),
              onPressed: () => ref.read(lessonSessionsProvider.notifier).deleteCase(session.id, m['id']),
            ),
          )),
          TextButton.icon(
            onPressed: () => _showCaseBottomSheet(session.id, session.schoolClassName),
            icon: const Icon(Icons.add), label: const Text('Catat Kasus Baru'),
          ),
          const SizedBox(height: 8),
        ],
      ),
    );
  }

  // ==============================
  // BOTTOM SHEETS FOR RELATIONS
  // ==============================

  Future<void> _showMaterialBottomSheet(int sessionId) async {
    List<dynamic> materials = [];
    try {
      final res = await ApiClient.instance.get('/lookup/materials');
      materials = res.data['data'];
    } catch (e) {
      // Handle error implicitly
    }

    if (!mounted) return;
    showModalBottomSheet(context: context, builder: (ctx) {
      return Container(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            const Text('Pilih Materi', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),
            Expanded(
              child: ListView.builder(
                itemCount: materials.length,
                itemBuilder: (c, i) => ListTile(
                  title: Text(materials[i]['title']),
                  trailing: const Icon(Icons.add_circle_outline),
                  onTap: () async {
                    Navigator.pop(ctx);
                    await ref.read(lessonSessionsProvider.notifier).attachMaterial(sessionId, materials[i]['id']);
                  },
                ),
              ),
            )
          ],
        ),
      );
    });
  }

  Future<void> _showAssignmentBottomSheet(int sessionId) async {
    List<dynamic> items = [];
    try {
      final res = await ApiClient.instance.get('/lookup/assignments');
      items = res.data['data'];
    } catch (e) {}

    if (!mounted) return;
    showModalBottomSheet(context: context, builder: (ctx) {
      return Container(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            const Text('Pilih Tugas', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),
            Expanded(
              child: ListView.builder(
                itemCount: items.length,
                itemBuilder: (c, i) => ListTile(
                  title: Text(items[i]['title']),
                  trailing: const Icon(Icons.add_circle_outline),
                  onTap: () async {
                    Navigator.pop(ctx);
                    await ref.read(lessonSessionsProvider.notifier).attachAssignment(sessionId, items[i]['id']);
                  },
                ),
              ),
            )
          ],
        ),
      );
    });
  }

  void _showAssessmentBottomSheet(int sessionId) {
    String type = 'kuis';
    String domain = 'kognitif';
    final titleCtrl = TextEditingController();
    final maxScoreCtrl = TextEditingController(text: '100');

    showModalBottomSheet(context: context, isScrollControlled: true, builder: (ctx) {
      return StatefulBuilder(builder: (context, setModalState) {
        return Padding(
          padding: EdgeInsets.only(bottom: MediaQuery.of(ctx).viewInsets.bottom, left: 16, right: 16, top: 16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Buat Assessment', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              const SizedBox(height: 16),
              TextField(controller: titleCtrl, decoration: const InputDecoration(labelText: 'Judul Assessment')),
              const SizedBox(height: 8),
              DropdownButtonFormField<String>(
                value: type,
                items: const [
                  DropdownMenuItem(value: 'kuis', child: Text('Kuis')),
                  DropdownMenuItem(value: 'ulangan_harian', child: Text('Ulangan Harian')),
                  DropdownMenuItem(value: 'tugas_kelas', child: Text('Tugas Kelas')),
                ],
                onChanged: (v) => setModalState(() => type = v!),
              ),
              const SizedBox(height: 8),
              DropdownButtonFormField<String>(
                value: domain,
                items: const [
                  DropdownMenuItem(value: 'kognitif', child: Text('Kognitif')),
                  DropdownMenuItem(value: 'psikomotorik', child: Text('Psikomotorik')),
                  DropdownMenuItem(value: 'afektif', child: Text('Afektif (Sikap)')),
                ],
                onChanged: (v) => setModalState(() => domain = v!),
              ),
              const SizedBox(height: 8),
              TextField(controller: maxScoreCtrl, decoration: const InputDecoration(labelText: 'Skor Maksimal'), keyboardType: TextInputType.number),
              const SizedBox(height: 16),
              ElevatedButton(
                onPressed: () async {
                  Navigator.pop(ctx);
                  await ref.read(lessonSessionsProvider.notifier).storeAssessment(sessionId, {
                    'title': titleCtrl.text, 'type': type, 'domain': domain, 'max_score': int.parse(maxScoreCtrl.text)
                  });
                },
                child: const Text('Simpan'),
              ),
              const SizedBox(height: 16),
            ],
          ),
        );
      });
    });
  }

  Future<void> _showCaseBottomSheet(int sessionId, String? className) async {
    List<dynamic> students = [];
    try {
      final res = await ApiClient.instance.get('/lookup/students'); // Or pass classId if we had it
      students = res.data['data'];
    } catch (e) {}

    if (!mounted) return;
    int? studentId;
    String status = 'tidak_selesai';
    final problemCtrl = TextEditingController();

    showModalBottomSheet(context: context, isScrollControlled: true, builder: (ctx) {
      return StatefulBuilder(builder: (context, setModalState) {
        return Padding(
          padding: EdgeInsets.only(bottom: MediaQuery.of(ctx).viewInsets.bottom, left: 16, right: 16, top: 16),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Catat Kasus Siswa', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              const SizedBox(height: 16),
              DropdownButtonFormField<int>(
                value: studentId,
                hint: const Text('Pilih Siswa'),
                items: students.map((s) => DropdownMenuItem<int>(value: s['id'], child: Text(s['name']))).toList(),
                onChanged: (v) => setModalState(() => studentId = v),
              ),
              const SizedBox(height: 8),
              TextField(controller: problemCtrl, decoration: const InputDecoration(labelText: 'Deskripsi Masalah / Kasus')),
              const SizedBox(height: 8),
              DropdownButtonFormField<String>(
                value: status,
                items: const [
                  DropdownMenuItem(value: 'tidak_selesai', child: Text('Belum Selesai')),
                  DropdownMenuItem(value: 'selesai', child: Text('Selesai')),
                ],
                onChanged: (v) => setModalState(() => status = v!),
              ),
              const SizedBox(height: 16),
              ElevatedButton(
                onPressed: () async {
                  if (studentId == null) return;
                  Navigator.pop(ctx);
                  await ref.read(lessonSessionsProvider.notifier).storeCase(sessionId, {
                    'student_id': studentId, 'problem': problemCtrl.text, 'status': status,
                  });
                },
                child: const Text('Simpan'),
              ),
              const SizedBox(height: 16),
            ],
          ),
        );
      });
    });
  }
}
