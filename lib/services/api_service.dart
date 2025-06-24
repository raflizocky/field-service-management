import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../models/task.dart';
import '../models/report.dart';
import '../utils/constants.dart';

class ApiService {
  static Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('token');
  }

  static Future<List<Task>> fetchTodayTasks() async {
    final token = await _getToken();

    if (token == null) {
      throw Exception('No authentication token found');
    }

    final response = await http.get(
      Uri.parse('$kApiBaseUrl/tasks/today'),
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      if (data['tasks'] != null) {
        return (data['tasks'] as List).map((e) => Task.fromJson(e)).toList();
      } else {
        return [];
      }
    } else if (response.statusCode == 401) {
      throw Exception('Unauthorized - please login again');
    } else {
      throw Exception('Failed to load tasks: ${response.statusCode}');
    }
  }

  static Future<List<Report>> fetchReports() async {
    final token = await _getToken();

    if (token == null) {
      throw Exception('No authentication token found');
    }

    final response = await http.get(
      Uri.parse('$kApiBaseUrl/reports'),
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      if (data['reports'] != null) {
        return (data['reports'] as List)
            .map((e) => Report.fromJson(e))
            .toList();
      } else {
        return [];
      }
    } else if (response.statusCode == 401) {
      throw Exception('Unauthorized - please login again');
    } else {
      throw Exception('Failed to load reports: ${response.statusCode}');
    }
  }

  static Future<Map<String, dynamic>> fetchProfile() async {
    final token = await _getToken();
    if (token == null) throw Exception('Not logged in');
    final response = await http.get(
      Uri.parse('$kApiBaseUrl/me'),
      headers: {'Authorization': 'Bearer $token'},
    );
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to load profile');
    }
  }
}
