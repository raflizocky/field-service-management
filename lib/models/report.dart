class Report {
  final int id;
  final int taskId;

  Report({
    required this.id,
    required this.taskId,
  });

  factory Report.fromJson(Map<String, dynamic> json) {
    return Report(
      id: json['id'],
      taskId: json['task_id'],
    );
  }
}
