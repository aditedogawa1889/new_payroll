<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akses Lokasi Wajib - Metland System Payroll</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0f172a;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
        }
        .container {
            max-width: 500px;
            width: 100%;
            margin: 20px;
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            backdrop-filter: blur(10px);
            box-sizing: border-box;
        }
        .icon-box {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            font-size: 28px;
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.4);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }
            50% { transform: scale(1.05); box-shadow: 0 0 30px rgba(239, 68, 68, 0.6); }
            100% { transform: scale(1); box-shadow: 0 0 20px rgba(239, 68, 68, 0.4); }
        }
        h2 {
            font-size: 24px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 16px;
            color: #f8fafc;
            letter-spacing: -0.025em;
        }
        p {
            font-size: 15px;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            border: none;
            padding: 14px 32px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
            outline: none;
            width: 100%;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.6);
        }
        .btn:active {
            transform: translateY(0);
        }
        #geolocation-error {
            display: none;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            font-size: 13px;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
            line-height: 1.6;
            text-align: left;
            width: 100%;
            box-sizing: border-box;
        }
        .instructions-title {
            font-weight: 600;
            margin-bottom: 8px;
            color: #ef4444;
            display: flex;
            align-items: center;
        }
        .instructions-list {
            margin: 0;
            padding-left: 20px;
        }
        .instructions-list li {
            margin-bottom: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-box">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <h2>Akses Lokasi Wajib Diaktifkan</h2>
        <p>Untuk menggunakan aplikasi <strong>Metland System Payroll</strong>, Anda wajib mengaktifkan akses lokasi di browser Anda. Lokasi Anda digunakan untuk verifikasi audit keamanan data gaji.</p>
        
        <!-- Error Message Placeholder -->
        <div id="geolocation-error"></div>

        <button onclick="requestGeolocationPermission()" class="btn">
            Aktifkan Akses Lokasi
        </button>
    </div>

    <script>
        async function getLocalIPAddress() {
            return new Promise((resolve) => {
                try {
                    const pc = new RTCPeerConnection({ iceServers: [] });
                    pc.createDataChannel('');
                    pc.createOffer().then(pc.setLocalDescription.bind(pc));
                    pc.onicecandidate = (ice) => {
                        if (!ice || !ice.candidate || !ice.candidate.candidate) {
                            resolve(null);
                            return;
                        }
                        const myIP = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/.exec(ice.candidate.candidate)?.[1];
                        if (myIP) {
                            resolve(myIP);
                            pc.onicecandidate = () => {};
                        }
                    };
                    setTimeout(() => resolve(null), 1500);
                } catch (e) {
                    resolve(null);
                }
            });
        }

        function areCookiesEnabled() {
            try {
                document.cookie = "cookietest=1; SameSite=Lax";
                const cookiesEnabled = document.cookie.indexOf("cookietest=") !== -1;
                document.cookie = "cookietest=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                return cookiesEnabled;
            } catch (e) {
                return false;
            }
        }

        function handlePermissionRevoked() {
            // Clear location cookies in case they were set
            document.cookie = "latitude=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "longitude=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "ip_local=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

            const btn = document.querySelector('.btn');
            if (btn) {
                btn.style.display = 'none';
            }

            const message = `<div class='instructions-title'><i class='fas fa-lock' style='margin-right: 6px;'></i> Akses Lokasi Diblokir</div>` +
                            `Silakan aktifkan akses lokasi di pengaturan browser Anda:<br>` +
                            `<ol class='instructions-list'>` +
                            `<li>Klik ikon gembok / info situs <i class='fas fa-lock' style='font-size:14px; margin: 0 2px;'></i> di sebelah kiri alamat URL browser Anda.</li>` +
                            `<li>Ubah izin <strong>Location / Lokasi</strong> menjadi <strong>Allow / Izinkan</strong>.</li>` +
                            `<li>Muat ulang halaman ini untuk menerapkan perubahan.</li>` +
                            `</ol>`;
            showError(message);
        }

        function showError(message) {
            const errorDiv = document.getElementById('geolocation-error');
            if (errorDiv) {
                errorDiv.innerHTML = message;
                errorDiv.style.display = 'block';
            }
        }

        function requestGeolocationPermission() {
            const errorDiv = document.getElementById('geolocation-error');
            const btn = document.querySelector('.btn');
            
            if (errorDiv) {
                errorDiv.style.display = 'none';
                errorDiv.innerHTML = '';
            }

            if (!navigator.geolocation) {
                showError("<div class='instructions-title'><i class='fas fa-exclamation-circle' style='margin-right: 6px;'></i> Tidak Didukung</div> Browser Anda tidak mendukung Geolocation atau koneksi Anda saat ini tidak aman (wajib HTTPS/localhost).");
                return;
            }

            navigator.geolocation.getCurrentPosition(
                async function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Set cookies
                    document.cookie = `latitude=${lat}; path=/; max-age=31536000; SameSite=Lax`;
                    document.cookie = `longitude=${lng}; path=/; max-age=31536000; SameSite=Lax`;

                    // Try to get local IP and store in cookie
                    let localIp = await getLocalIPAddress();
                    if (!localIp) {
                        localIp = window.location.hostname || '127.0.0.1';
                    }
                    document.cookie = `ip_local=${localIp}; path=/; max-age=31536000; SameSite=Lax`;

                    // Reload page so middleware allows access
                    window.location.reload();
                },
                function (error) {
                    console.error("Location access denied or error:", error);
                    
                    if (error.code === error.PERMISSION_DENIED) {
                        handlePermissionRevoked();
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        showError("<div class='instructions-title'><i class='fas fa-map-marked-alt' style='margin-right: 6px;'></i> Lokasi Tidak Tersedia</div> Informasi lokasi tidak tersedia. Pastikan perangkat Anda terhubung ke internet dan layanan lokasi (GPS) aktif.");
                    } else if (error.code === error.TIMEOUT) {
                        showError("<div class='instructions-title'><i class='fas fa-stopwatch' style='margin-right: 6px;'></i> Waktu Habis</div> Permintaan lokasi habis waktu (timeout). Silakan klik tombol untuk mencoba kembali.");
                    } else {
                        showError("Gagal mengambil lokasi: " + error.message);
                    }
                },
                { enableHighAccuracy: false, timeout: 15000, maximumAge: 60000 }
            );
        }

        // Auto request on page load if allowed
        document.addEventListener('DOMContentLoaded', function() {
            if (!areCookiesEnabled()) {
                showError("<div class='instructions-title'><i class='fas fa-exclamation-triangle' style='margin-right: 6px;'></i> Cookie Dinonaktifkan</div> Browser Anda menolak penyimpanan Cookie. Harap aktifkan Cookie di pengaturan browser Anda untuk menggunakan aplikasi ini.");
                const btn = document.querySelector('.btn');
                if (btn) btn.style.display = 'none';
                return;
            }

            try {
                if (navigator.permissions && navigator.permissions.query) {
                    navigator.permissions.query({ name: 'geolocation' }).then(function(permissionStatus) {
                        if (permissionStatus.state === 'denied') {
                            handlePermissionRevoked();
                        } else {
                            requestGeolocationPermission();
                        }

                        permissionStatus.onchange = function() {
                            if (permissionStatus.state === 'denied') {
                                handlePermissionRevoked();
                            } else if (permissionStatus.state === 'granted') {
                                const btn = document.querySelector('.btn');
                                if (btn) btn.style.display = '';
                                requestGeolocationPermission();
                            }
                        };
                    }).catch(function(e) {
                        console.warn("Permissions query rejected, falling back to standard checks:", e);
                        requestGeolocationPermission();
                    });
                } else {
                    requestGeolocationPermission();
                }
            } catch (e) {
                console.warn("Permissions API error, falling back to standard checks:", e);
                requestGeolocationPermission();
            }
        });
    </script>
</body>
</html>
