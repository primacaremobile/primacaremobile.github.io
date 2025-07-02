import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class StatusBar extends StatelessWidget {
  const StatusBar({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(24, 16, 24, 0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            '9:41',
            style: GoogleFonts.poppins(
              fontSize: 15,
              fontWeight: FontWeight.w600,
              color: Colors.white,
            ),
          ),
          Row(
            children: [
              Image.network(
                'https://cdn.builder.io/api/v1/image/assets/TEMP/73f220fff83808d0feba900b2e7bf98ff3ff5bfd54d005cf3c7f1cccaec2d364?placeholderIfAbsent=true&apiKey=08aaa84e8bea4ab5bcf6c4e10dfe03d0',
                width: 17,
                height: 11,
                fit: BoxFit.contain,
              ),
              const SizedBox(width: 6),
              Image.network(
                'https://cdn.builder.io/api/v1/image/assets/TEMP/1a4dd3fa32101bacf4e6009f44c83e433280dab700bf5334596284853bd7277b?placeholderIfAbsent=true&apiKey=08aaa84e8bea4ab5bcf6c4e10dfe03d0',
                width: 15,
                height: 11,
                fit: BoxFit.contain,
              ),
              const SizedBox(width: 6),
              Image.network(
                'https://cdn.builder.io/api/v1/image/assets/TEMP/7d7f3bdbe98896dac8842d4a33c976973c2281837363628878defc43e8bb2f02?placeholderIfAbsent=true&apiKey=08aaa84e8bea4ab5bcf6c4e10dfe03d0',
                width: 25,
                height: 12,
                fit: BoxFit.contain,
              ),
            ],
          ),
        ],
      ),
    );
  }
}