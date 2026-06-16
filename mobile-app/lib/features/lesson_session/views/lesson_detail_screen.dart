import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
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
  
  bool _isLoading = false;

  @override
  void initState() {
    super.initState();
    _status = widget.session.status;
    _achievementPercent = widget.session.achievementPercent.toDouble();
    _executionNotesCtrl = TextEditingController(text: widget.session.executionNotes ?? '');
    _homeworkNotesCtrl = TextEditingController(text: widget.session.homeworkNotes ?? '');
    _issuesNotesCtrl = TextEditingController(text: widget.session.issuesNotes ?? '');
  }

  @override
  void dispose() {
    _executionNotesCtrl.dispose();
    _homeworkNotesCtrl.dispose();
    _issuesNotesCtrl.dispose();
    super.dispose();
  }

  Future<void> _saveChanges() async {
    setState(() => _isLoading = true);
    try {
      await ref.read(lessonSessionsProvider.notifier).updateSession(widget.session.id, {
        'status': _status,
        'achievement_percent': _achievementPercent.toInt(),
        'execution_notes': _executionNotesCtrl.text,
        'homework_notes': _homeworkNotesCtrl.text,
        'issues_notes': _issuesNotesCtrl.text,
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Perubahan berhasil disimpan')));
        context.pop();
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString()), backgroundColor: Colors.red));
      }
    } finally {
      if (mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 4,
      child: Scaffold(
        backgroundColor: const Color(0xFFF8F9FA),
        appBar: AppBar(
          title: const Text('Detail Sesi', style: TextStyle(color: Colors.black87, fontWeight: FontWeight.bold)),
          backgroundColor: Colors.white,
          elevation: 0,
          centerTitle: false,
          iconTheme: const IconThemeData(color: Colors.black87),
          bottom: const TabBar(
            isScrollable: true,
            labelColor: Color(0xFF0047AB),
            unselectedLabelColor: Colors.grey,
            indicatorColor: Color(0xFF0047AB),
            tabs: [
              Tab(text: 'Info Dasar'),
              Tab(text: 'Perencanaan'),
              Tab(text: 'Pelaksanaan'),
              Tab(text: 'Catatan'),
            ],
          ),
        ),
        body: TabBarView(
          children: [
            _buildInfoTab(),
            _buildPlanTab(),
            _buildExecutionTab(),
            _buildNotesTab(),
          ],
        ),
        bottomNavigationBar: SafeArea(
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: ElevatedButton(
              style: ElevatedButton.styleFrom(
                backgroundColor: const Color(0xFF0047AB),
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(vertical: 16),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
              onPressed: _isLoading ? null : _saveChanges,
              child: _isLoading 
                ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                : const Text('Simpan Perubahan', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildInfoTab() {
    String timeRange = '${widget.session.startTime ?? '00:00'} - ${widget.session.endTime ?? '00:00'}';
    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
        child: Column(
          children: [
            _buildDetailRow(Icons.book_outlined, 'Mata Pelajaran', widget.session.subjectName ?? '-'),
            const Divider(height: 24),
            _buildDetailRow(Icons.calendar_today_outlined, 'Tanggal', widget.session.sessionDate ?? '-'),
            const Divider(height: 24),
            _buildDetailRow(Icons.access_time, 'Jam', timeRange),
            const Divider(height: 24),
            _buildDetailRow(Icons.class_outlined, 'Kelas', widget.session.schoolClassName ?? '-'),
            const Divider(height: 24),
            _buildDetailRow(Icons.person_outline, 'Guru', widget.session.teacherName ?? '-'),
            const Divider(height: 24),
            _buildDetailRow(Icons.topic_outlined, 'Topik', widget.session.topic ?? '-'),
          ],
        ),
      ),
    );
  }

  Widget _buildPlanTab() {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Column(
        children: [
          _buildReadOnlyListCard('Tujuan Pembelajaran', widget.session.learningObjectives),
          const SizedBox(height: 16),
          _buildReadOnlyListCard('Metode', widget.session.methods),
          const SizedBox(height: 16),
          _buildReadOnlyListCard('Media', widget.session.media),
        ],
      ),
    );
  }

  Widget _buildExecutionTab() {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Container(
        padding: const EdgeInsets.all(20),
        decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Status Sesi', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            DropdownButtonFormField<String>(
              value: _status,
              decoration: InputDecoration(
                border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              ),
              items: const [
                DropdownMenuItem(value: 'draft', child: Text('Draft')),
                DropdownMenuItem(value: 'published', child: Text('Published')),
                DropdownMenuItem(value: 'ongoing', child: Text('Sedang Berlangsung')),
                DropdownMenuItem(value: 'completed', child: Text('Selesai')),
                DropdownMenuItem(value: 'cancelled', child: Text('Dibatalkan')),
              ],
              onChanged: (val) {
                if (val != null) setState(() => _status = val);
              },
            ),
            const SizedBox(height: 24),
            const Text('Pencapaian Belajar', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            Row(
              children: [
                Expanded(
                  child: Slider(
                    value: _achievementPercent,
                    min: 0,
                    max: 100,
                    divisions: 20,
                    label: '${_achievementPercent.round()}%',
                    onChanged: (val) {
                      setState(() => _achievementPercent = val);
                    },
                  ),
                ),
                Text('${_achievementPercent.round()}%', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildNotesTab() {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(20),
      child: Column(
        children: [
          _buildTextField('Catatan Pelaksanaan', _executionNotesCtrl),
          const SizedBox(height: 16),
          _buildTextField('Pekerjaan Rumah (PR)', _homeworkNotesCtrl),
          const SizedBox(height: 16),
          _buildTextField('Kendala / Isu', _issuesNotesCtrl),
        ],
      ),
    );
  }

  Widget _buildTextField(String label, TextEditingController controller) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(label, style: const TextStyle(fontWeight: FontWeight.bold)),
          const SizedBox(height: 12),
          TextFormField(
            controller: controller,
            maxLines: 4,
            decoration: InputDecoration(
              hintText: 'Tulis $label di sini...',
              border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildReadOnlyListCard(String title, List<dynamic>? items) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(16)),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
          const SizedBox(height: 8),
          if (items == null || items.isEmpty)
            const Text('-', style: TextStyle(color: Colors.grey))
          else
            ...items.map((e) => Padding(
              padding: const EdgeInsets.only(bottom: 4),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('• ', style: TextStyle(color: Colors.grey)),
                  Expanded(child: Text(e.toString())),
                ],
              ),
            )),
        ],
      ),
    );
  }

  Widget _buildDetailRow(IconData icon, String label, String value) {
    return Row(
      children: [
        Icon(icon, color: Colors.grey, size: 20),
        const SizedBox(width: 16),
        Text(label, style: const TextStyle(color: Colors.grey, fontSize: 14)),
        const Spacer(),
        Flexible(
          child: Text(value, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14), textAlign: TextAlign.right),
        ),
      ],
    );
  }
}
