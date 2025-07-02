import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class WelcomeContent extends StatelessWidget {
  const WelcomeContent({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(24, 17, 24, 0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Welcome to Primacare',
            style: GoogleFonts.actor(
              fontSize: 40,
              height: 1.1,
              letterSpacing: -0.6,
              color: Colors.white,
            ),
          ),
          const SizedBox(height: 14),
          Text(
            'Enjoy stress-free vehicle care with subscription plans tailored to your needs. From preventive maintenance to 24/7 support, we've got you covered.',
            style: GoogleFonts.actor(
              fontSize: 14,
              letterSpacing: -0.21,
              color: Colors.white,
            ),
          ),
          const SizedBox(height: 348),
          Container(
            width: 127,
            height: 38,
            alignment: Alignment.centerRight,
          ),
        ],
      ),
    );
  }
}