import 'package:flutter/material.dart';
import '../models/task.dart';
import '../services/api_service.dart';
import '../utils/theme.dart';
import '../utils/constants.dart';
import '../widgets/custom_widgets.dart';

class TaskListScreen extends StatefulWidget {
  const TaskListScreen({super.key});

  @override
  State<TaskListScreen> createState() => _TaskListScreenState();
}

class _TaskListScreenState extends State<TaskListScreen> {
  List<Task> tasks = [];
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    fetchData();
  }

  Future<void> fetchData() async {
    try {
      final taskList = await ApiService.fetchTodayTasks();
      final reports = await ApiService.fetchReports();
      final reportedIds = reports.map((r) => r.taskId).toSet();

      for (var task in taskList) {
        task.hasReport = reportedIds.contains(task.id);
      }

      setState(() {
        tasks = taskList;
        isLoading = false;
      });
    } catch (e) {
      setState(() => isLoading = false);
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Row(
              children: [
                const Icon(Icons.error_outline, color: Colors.white),
                const SizedBox(width: 8),
                Expanded(child: Text('Error: $e')),
              ],
            ),
            backgroundColor: AppColors.error,
            behavior: SnackBarBehavior.floating,
          ),
        );
      }
    }
  }

  Future<void> _refreshData() async {
    setState(() => isLoading = true);
    await fetchData();
  }

  void _navigateToReport(int taskId) async {
    final result = await Navigator.pushNamed(
      context,
      '/report',
      arguments: taskId,
    );

    if (result == true) {
      _refreshData();
    }
  }

  Color _getTaskStatusColor(String status) {
    switch (status.toLowerCase()) {
      case 'pending':
        return AppColors.taskPending;
      case 'in_progress':
      case 'in progress':
        return AppColors.taskInProgress;
      case 'completed':
        return AppColors.taskCompleted;
      case 'overdue':
        return AppColors.taskOverdue;
      default:
        return AppColors.textSecondary;
    }
  }

  IconData _getTaskStatusIcon(String status) {
    switch (status.toLowerCase()) {
      case 'pending':
        return Icons.schedule;
      case 'in_progress':
      case 'in progress':
        return Icons.play_circle;
      case 'completed':
        return Icons.check_circle;
      case 'overdue':
        return Icons.error;
      default:
        return Icons.assignment;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      appBar: CustomAppBar(
        title: 'Today\'s Tasks',
        showBackButton: false,
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _refreshData,
            tooltip: 'Refresh',
          ),
        ],
      ),
      body: isLoading
          ? const LoadingWidget(message: 'Loading tasks...')
          : tasks.isEmpty
              ? EmptyState(
                  icon: Icons.assignment_outlined,
                  title: 'No Tasks Today',
                  subtitle: 'You have no tasks scheduled for today. Great job!',
                  action: CustomButton(
                    text: 'Refresh',
                    onPressed: _refreshData,
                    icon: Icons.refresh,
                  ),
                )
              : RefreshIndicator(
                  onRefresh: _refreshData,
                  color: AppColors.primary,
                  child: Column(
                    children: [
                      // Summary Header
                      Container(
                        margin: const EdgeInsets.all(kPaddingMedium),
                        padding: const EdgeInsets.all(kPaddingMedium),
                        decoration: BoxDecoration(
                          gradient: LinearGradient(
                            colors: [
                              AppColors.primary,
                              AppColors.primaryLight,
                            ],
                            begin: Alignment.topLeft,
                            end: Alignment.bottomRight,
                          ),
                          borderRadius: BorderRadius.circular(kBorderRadiusLarge),
                        ),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text(
                              'Total: ${tasks.length}',
                              style: Theme.of(context)
                                  .textTheme
                                  .titleMedium
                                  ?.copyWith(color: Colors.white),
                            ),
                            Text(
                              'Completed: ${tasks.where((t) => t.status.toLowerCase() == 'completed').length}',
                              style: Theme.of(context)
                                  .textTheme
                                  .titleMedium
                                  ?.copyWith(color: Colors.white),
                            ),
                          ],
                        ),
                      ),
                      // Task List
                      Expanded(
                        child: ListView.separated(
                          physics: const AlwaysScrollableScrollPhysics(),
                          itemCount: tasks.length,
                          separatorBuilder: (_, __) => const Divider(height: 1),
                          itemBuilder: (context, index) {
                            final task = tasks[index];
                            return ListTile(
                              leading: Icon(
                                _getTaskStatusIcon(task.status),
                                color: _getTaskStatusColor(task.status),
                              ),
                              title: Text(task.title),
                              subtitle: Text(
                                task.description ?? '',
                                maxLines: 2,
                                overflow: TextOverflow.ellipsis,
                              ),
                              trailing: task.hasReport
                                  ? const Icon(Icons.check, color: Colors.green)
                                  : IconButton(
                                      icon: const Icon(Icons.note_add),
                                      tooltip: 'Report',
                                      onPressed: () => _navigateToReport(task.id),
                                    ),
                              tileColor: task.hasReport
                                  ? AppColors.taskCompleted.withOpacity(0.08)
                                  : null,
                            );
                          },
                        ),
                      ),
                    ],
                  ),
                ),
    );
  }
}
