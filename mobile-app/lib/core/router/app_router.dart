import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:mobile_app/core/storage/secure_storage.dart';
import 'package:mobile_app/features/auth/views/login_screen.dart';
import 'package:mobile_app/features/home/views/home_screen.dart';
import 'package:mobile_app/features/main/views/main_scaffold.dart';
import 'package:mobile_app/features/lesson_session/views/lesson_sessions_screen.dart';
import 'package:mobile_app/features/lesson_session/views/lesson_detail_screen.dart';
import 'package:mobile_app/features/lesson_session/models/lesson_session_model.dart';

final GlobalKey<NavigatorState> _rootNavigatorKey = GlobalKey<NavigatorState>();
final GlobalKey<NavigatorState> _shellNavigatorKey = GlobalKey<NavigatorState>();

class AppRouter {
  static final GoRouter router = GoRouter(
    navigatorKey: _rootNavigatorKey,
    initialLocation: '/',
    redirect: (BuildContext context, GoRouterState state) async {
      final hasToken = await SecureStorage.hasToken();
      final isLoggingIn = state.uri.toString() == '/login';

      if (!hasToken && !isLoggingIn) {
        return '/login';
      }

      if (hasToken && isLoggingIn) {
        return '/';
      }

      return null;
    },
    routes: [
      GoRoute(
        path: '/login',
        builder: (context, state) => const LoginScreen(),
      ),
      ShellRoute(
        navigatorKey: _shellNavigatorKey,
        builder: (context, state, child) {
          return MainScaffold(child: child);
        },
        routes: [
          GoRoute(
            path: '/',
            pageBuilder: (context, state) => const NoTransitionPage(child: HomeScreen()),
          ),
          GoRoute(
            path: '/sessions',
            pageBuilder: (context, state) => const NoTransitionPage(child: LessonSessionsScreen()),
            routes: [
              GoRoute(
                path: 'detail',
                parentNavigatorKey: _rootNavigatorKey,
                builder: (context, state) {
                  final session = state.extra as LessonSessionModel;
                  return LessonDetailScreen(session: session);
                },
              ),
            ]
          ),
          GoRoute(
            path: '/notifications',
            pageBuilder: (context, state) => const NoTransitionPage(
              child: Scaffold(body: Center(child: Text('Notifications'))),
            ),
          ),
          GoRoute(
            path: '/profile',
            pageBuilder: (context, state) => const NoTransitionPage(
              child: Scaffold(body: Center(child: Text('Profile'))),
            ),
          ),
        ],
      ),
    ],
  );
}
