import 'package:flutter/material.dart';
import 'theme.dart';

// API Configuration
const kApiBaseUrl = 'http://127.0.0.1:8000/api';

// Colors (using new theme)
const kPrimaryColor = AppColors.primary;
const kAccentColor = AppColors.secondary;

// Spacing
const kPaddingSmall = 8.0;
const kPaddingMedium = 16.0;
const kPaddingLarge = 24.0;
const kPaddingXLarge = 32.0;

// Border Radius
const kBorderRadiusSmall = 4.0;
const kBorderRadiusMedium = 8.0;
const kBorderRadiusLarge = 12.0;
const kBorderRadiusXLarge = 16.0;

// Animation Durations
const kAnimationDurationShort = Duration(milliseconds: 200);
const kAnimationDurationMedium = Duration(milliseconds: 300);
const kAnimationDurationLong = Duration(milliseconds: 500);

// Task Status Configuration
enum TaskStatus {
  pending,
  inProgress,
  completed,
  overdue,
}

extension TaskStatusExtension on TaskStatus {
  Color get color {
    switch (this) {
      case TaskStatus.pending:
        return AppColors.taskPending;
      case TaskStatus.inProgress:
        return AppColors.taskInProgress;
      case TaskStatus.completed:
        return AppColors.taskCompleted;
      case TaskStatus.overdue:
        return AppColors.taskOverdue;
    }
  }
  
  IconData get icon {
    switch (this) {
      case TaskStatus.pending:
        return Icons.schedule;
      case TaskStatus.inProgress:
        return Icons.play_circle;
      case TaskStatus.completed:
        return Icons.check_circle;
      case TaskStatus.overdue:
        return Icons.error;
    }
  }
  
  String get label {
    switch (this) {
      case TaskStatus.pending:
        return 'Pending';
      case TaskStatus.inProgress:
        return 'In Progress';
      case TaskStatus.completed:
        return 'Completed';
      case TaskStatus.overdue:
        return 'Overdue';
    }
  }
}