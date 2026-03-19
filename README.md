# GPS Tracker PWA

Ein moderner, webbasierter GPS-Tracker als Progressive Web App (PWA). Diese App ermöglicht es, aktuelle Standortkoordinaten abzurufen, zwischenzuspeichern und als JSON-Datei zu exportieren, während das Display aktiv bleibt.

## Features

- **Progressive Web App (PWA)**: Installierbar auf dem Homescreen und offline-fähig (Service Worker).
- **Dark Design**: Edle, dunkle Benutzeroberfläche im Glassmorphismus-Look mit einem 3-sekündigen Splash-Screen beim Start.
- **GPS-Tracking**: Abfrage von Breitengrad (Lat) und Längengrad (Lon) über die Geolocation-API.
- **Log & Export**: Automatische Speicherung der abgerufenen Koordinaten mit Zeitstempel und Export-Funktion als `.json`.
- **Screen Wake Lock**: Nutzt die Wake Lock API, um zu verhindern, dass sich das Display während der Nutzung ausschaltet.

## Voraussetzungen

Da Geolocation, Service Worker und die Wake Lock API sicherheitskritische Funktionen sind, benötigt die App einen **Secure Origin**:
- Zugriff über **HTTPS**
- Oder Zugriff über **localhost** (für Entwicklung)

## Installation

1. Klone das Repository in dein Web-Verzeichnis.
2. Stelle sicher, dass die Dateistruktur wie folgt aussieht:
    - `/index.php`
    - `/sw.js`
    - `/assets/manifest.json`
    - `/assets/style.css`
3. Rufe die App über eine sichere URL auf.

## Lizenz
MIT

