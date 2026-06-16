import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:mobile_app/core/router/app_router.dart';

void main() {
  runApp(
    // Membungkus aplikasi dengan ProviderScope agar Riverpod bisa digunakan
    const ProviderScope(
      child: MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp.router(
      title: 'Lesson Sessions',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.deepPurple),
        useMaterial3: true,
      ),
      // Menggunakan router config dari AppRouter
      routerConfig: AppRouter.router,
    );
  }
}
