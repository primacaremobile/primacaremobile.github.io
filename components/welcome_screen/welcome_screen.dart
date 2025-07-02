import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'components/status_bar.dart';
import 'components/welcome_content.dart';
import 'components/action_buttons.dart';

class WelcomeScreen extends StatelessWidget {
  const WelcomeScreen({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFF0A0F1D),
      body: SingleChildScrollView(
        child: Container(
          constraints: const BoxConstraints(maxWidth: 480),
          margin: const EdgeInsets.symmetric(horizontal: auto),
          child: Stack(
            children: [
              Image.network(
                'https://cdn.builder.io/api/v1/image/assets/TEMP/745bbf6228b91100862cfe8ea01598f4b2611472f16159c44d04081ad651d150?placeholderIfAbsent=true&apiKey=08aaa84e8bea4ab5bcf6c4e10dfe03d0',
                width: double.infinity,
                height: MediaQuery.of(context).size.width * 0.462,
                fit: BoxFit.cover,
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: const [
                  StatusBar(),
                  WelcomeContent(),
                  ActionButtons(),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}