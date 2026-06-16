class LessonSessionModel {
  final int id;
  final String? topic;
  final String? sessionDate;
  final String? startTime;
  final String? endTime;
  final String status;
  final int achievementPercent;
  final String? schoolClassName;
  final String? subjectName;
  final String? teacherName;
  
  // Perencanaan
  final List<dynamic>? learningObjectives;
  final List<dynamic>? methods;
  final List<dynamic>? media;
  
  // Pelaksanaan & Catatan
  final String? executionNotes;
  final String? homeworkNotes;
  final String? issuesNotes;
  final String? actualStartAt;
  final String? actualEndAt;

  LessonSessionModel({
    required this.id,
    this.topic,
    this.sessionDate,
    this.startTime,
    this.endTime,
    required this.status,
    required this.achievementPercent,
    this.schoolClassName,
    this.subjectName,
    this.teacherName,
    this.learningObjectives,
    this.methods,
    this.media,
    this.executionNotes,
    this.homeworkNotes,
    this.issuesNotes,
    this.actualStartAt,
    this.actualEndAt,
  });

  factory LessonSessionModel.fromJson(Map<String, dynamic> json) {
    return LessonSessionModel(
      id: json['id'] as int,
      topic: json['topic'] as String?,
      sessionDate: json['session_date'] as String?,
      startTime: json['start_time'] as String?,
      endTime: json['end_time'] as String?,
      status: json['status'] ?? 'draft',
      achievementPercent: json['achievement_percent'] ?? 0,
      schoolClassName: json['school_class']?['name'] as String?,
      subjectName: json['subject']?['name'] as String?,
      teacherName: json['teacher']?['name'] as String?,
      learningObjectives: json['learning_objectives'] as List<dynamic>?,
      methods: json['methods'] as List<dynamic>?,
      media: json['media'] as List<dynamic>?,
      executionNotes: json['execution_notes'] as String?,
      homeworkNotes: json['homework_notes'] as String?,
      issuesNotes: json['issues_notes'] as String?,
      actualStartAt: json['actual_start_at'] as String?,
      actualEndAt: json['actual_end_at'] as String?,
    );
  }
}
