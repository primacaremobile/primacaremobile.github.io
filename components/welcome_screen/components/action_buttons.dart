import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class ActionButtons extends StatelessWidget {
  const ActionButtons({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(24, 135, 24, 0),
      child: Column(
        children: [
          ElevatedButton(
            onPressed: () {},
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.white,
              foregroundColor: const Color(0xFF0A0F1D),
              elevation: 12,
              shadowColor: const Color(0xFF314FF6).withOpacity(0.2),
              minimumSize: const Size(double.infinity, 50),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(59),
              ),
            ),
            child: Text(
              'Get Started',
              style: GoogleFonts.actor(
                fontSize: 16,
                height: 2,
                letterSpacing: -0.24,
              ),
            ),
          ),
          const SizedBox(height: 20),
          Container(
            width: 96,
            height: 5,
            decoration: BoxDecoration(
              color: const Color(0xFF8C8BA7),
              borderRadius: BorderRadius.circular(96),
            ),
          ),
        ],
      ),
    );
  }
}