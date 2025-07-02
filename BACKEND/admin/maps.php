<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Locations Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 500px; width: 100%; }
    </style>
</head>
<body>
    <h2>User Locations</h2>
    <div id="map"></div>
    
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([6.5244, 3.3792], 12); // Centered on Lagos

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var locations = [
            { address: "No 27 Akeju Street, Onipanu Shomolu", coords: [6.5370, 3.3680] },
            { address: "108 close 55 VGC", coords: [6.4625, 3.6051] },
            { address: "BLUEWATER apartments, Marwa, Lekki", coords: [6.4281, 3.4211] },
            { address: "14 Akinnagbe St, Ajah", coords: [6.4673, 3.6015] },
            { address: "No 10 Saka Tinubu Street, VI", coords: [6.4294, 3.4181] },
            { address: "23c Jide Agbalaya Street, Chevy View, Lekki", coords: [6.4371, 3.4812] },
            { address: "3 Oseni Close, Orile Agege 102212", coords: [6.6244, 3.3415] },
            { address: "4b Agungi, Lekki", coords: [6.4339, 3.4882] },
            { address: "191 Prince Ademola Oniru", coords: [6.4264, 3.4408] }
        ];

        locations.forEach(function(location) {
            L.marker(location.coords).addTo(map)
                .bindPopup(location.address)
                .openPopup();
        });
    </script>
</body>
</html>
