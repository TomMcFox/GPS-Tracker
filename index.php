<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>GPS Tracker PWA</title>
    <link rel="manifest" href="assets/manifest.json">
    <meta name="theme-color" content="#121212">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <div id="splash">
        <div class="logo-placeholder">GPS TRACKER</div>
    </div>

    <div id="main-content">
        <div class="card">
            <h1>Standort</h1>
            <div class="coord-display">
                <div class="coord-item">
                    <span class="label">Breitengrad (Lat)</span>
                    <span id="lat" class="value">-</span>
                </div>
                <div class="coord-item">
                    <span class="label">Längengrad (Lon)</span>
                    <span id="lon" class="value">-</span>
                </div>
            </div>
            <button id="btn-load">Koordinaten laden</button>
            <button id="btn-export" style="background: rgba(255,255,255,0.1); margin-top: 0.5rem;">Koordinaten exportieren</button>
            <div id="status">Bereit</div>
        </div>
    </div>

    <script>
        // Data storage
        let coordLogs = [];

        // Splash Screen Logic
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const splash = document.getElementById('splash');
                const main = document.getElementById('main-content');
                
                splash.classList.add('hidden');
                main.classList.add('visible');
                
                // Try to request Wake Lock after splash
                requestWakeLock();
            }, 3000);
        });

        // Geolocation Logic
        const btnLoad = document.getElementById('btn-load');
        const btnExport = document.getElementById('btn-export');
        const latDisplay = document.getElementById('lat');
        const lonDisplay = document.getElementById('lon');
        const statusDisplay = document.getElementById('status');

        btnLoad.addEventListener('click', () => {
            if (!window.isSecureContext) {
                statusDisplay.innerHTML = "Fehler: Diese App benötigt HTTPS oder localhost (Sicherer Ursprung).";
                console.error("Not a secure context. Geolocation and PWA features will not work.");
                return;
            }

            if ("geolocation" in navigator) {
                statusDisplay.innerText = "Lade...";
                btnLoad.disabled = true;

                navigator.geolocation.getCurrentPosition((position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    const timestamp = new Date().toISOString();
                    
                    latDisplay.innerText = lat.toFixed(6);
                    lonDisplay.innerText = lon.toFixed(6);
                    statusDisplay.innerText = "Aktualisiert";
                    btnLoad.disabled = false;

                    // Log the coordinates
                    coordLogs.push({ lat, lon, timestamp });
                    console.log("Logged:", lat, lon);
                }, (error) => {
                    console.error("Error getting location", error);
                    statusDisplay.innerText = "Fehler: " + error.message;
                    btnLoad.disabled = false;
                }, {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                });
            } else {
                statusDisplay.innerText = "Geolocation nicht unterstützt";
            }
        });

        // Export Logic
        btnExport.addEventListener('click', () => {
            if (coordLogs.length === 0) {
                statusDisplay.innerText = "Keine Daten zum Export";
                return;
            }

            const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(coordLogs, null, 2));
            const downloadAnchorNode = document.createElement('a');
            downloadAnchorNode.setAttribute("href",     dataStr);
            downloadAnchorNode.setAttribute("download", "gps_tracker_logs.json");
            document.body.appendChild(downloadAnchorNode);
            downloadAnchorNode.click();
            downloadAnchorNode.remove();
            
            statusDisplay.innerText = "Export gestartet";
        });

        // Wake Lock API Logic
        let wakeLock = null;

        const requestWakeLock = async () => {
            try {
                if ('wakeLock' in navigator) {
                    wakeLock = await navigator.wakeLock.request('screen');
                    console.log('Wake Lock is active');
                    
                    wakeLock.addEventListener('release', () => {
                        console.log('Wake Lock was released');
                    });
                } else {
                    console.warn('Wake Lock API not supported');
                }
            } catch (err) {
                console.error(`${err.name}, ${err.message}`);
            }
        };

        // Re-request wake lock when page becomes visible again
        document.addEventListener('visibilitychange', async () => {
            if (wakeLock !== null && document.visibilityState === 'visible') {
                await requestWakeLock();
            }
        });

        // Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('sw.js')
                    .then(reg => console.log('Service Worker registered', reg))
                    .catch(err => console.error('Service Worker registration failed', err));
            });
        }
    </script>
</body>
</html>
