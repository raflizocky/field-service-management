class Task {
  final int id;
  final String title;
  final String? description;
  final String status;
  bool hasReport;

  Task({
    required this.id,
    required this.title,
    this.description,
    required this.status,
    this.hasReport = false,
  });

  factory Task.fromJson(Map<String, dynamic> json) {
    return Task(
      id: json['id'],
      title: json['title'],
      description: json['description'],
      status: json['status'],
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'title': title,
        'description': description,
        'status': status,
        'has_report': hasReport,
      };
}
