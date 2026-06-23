<div id="geolocation-blocker" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px); z-index: 999999; justify-content: center; align-items: center; flex-direction: column; color: #fff; font-family: 'Inter', system-ui, -apple-system, sans-serif; text-align: center; padding: 20px;">
    <div style="max-width: 500px; background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px; padding: 40px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <div style="background: rgba(239, 68, 68, 0.2); color: #ef4444; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; font-size: 28px; box-shadow: 0 0 20px rgba(239, 68, 68, 0.4);">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 16px; color: #f8fafc; tracking: -0.025em;">Akses Lokasi Wajib Diaktifkan</h2>
        <p style="font-size: 15px; color: #94a3b8; line-height: 1.6; margin-bottom: 28px;">Untuk menggunakan aplikasi <strong>Metland System Payroll</strong>, Anda wajib mengaktifkan akses lokasi di browser Anda. Lokasi Anda digunakan untuk verifikasi audit keamanan data gaji.</p>
        
        <!-- Error Message Placeholder -->
        <div id="geolocation-error" style="display: none; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; font-size: 13px; border-radius: 8px; padding: 12px; margin-bottom: 20px; line-height: 1.5; text-align: left; width: 100%;">
        </div>

        <button id="geolocation-btn" onclick="requestGeolocationPermission()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; border: none; padding: 14px 32px; font-size: 15px; font-weight: 600; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4); outline: none;">
            Aktifkan Akses Lokasi
        </button>
    </div>
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

    function showGeolocationBlocker() {
        const blocker = document.getElementById('geolocation-blocker');
        if (blocker) {
            blocker.style.display = 'flex';
        }
        document.body.style.overflow = 'hidden';
        
        document.querySelectorAll('a, button, input, select, textarea').forEach(el => {
            if (!el.closest('#geolocation-blocker')) {
                el.setAttribute('tabindex', '-1');
                el.style.pointerEvents = 'none';
            }
        });
    }

    function hideGeolocationBlocker() {
        const blocker = document.getElementById('geolocation-blocker');
        if (blocker) {
            blocker.style.display = 'none';
        }
        document.body.style.overflow = '';
        
        document.querySelectorAll('a, button, input, select, textarea').forEach(el => {
            if (!el.closest('#geolocation-blocker')) {
                el.removeAttribute('tabindex');
                el.style.pointerEvents = '';
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
        document.cookie = "latitude=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "longitude=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "ip_local=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

        const btn = document.getElementById('geolocation-btn');
        if (btn) {
            btn.style.display = 'none';
        }

        const message = `<div style='font-weight: 600; margin-bottom: 8px; color: #ef4444; display: flex; align-items: center;'><i class='fas fa-lock' style='margin-right: 6px;'></i> Akses Lokasi Diblokir</div>` +
                        `Silakan aktifkan akses lokasi di pengaturan browser Anda:<br>` +
                        `<ol style='margin: 0; padding-left: 20px;'>` +
                        `<li style='margin-bottom: 6px;'>Klik ikon gembok / info situs <i class='fas fa-lock' style='font-size:14px; margin: 0 2px;'></i> di sebelah kiri alamat URL browser Anda.</li>` +
                        `<li style='margin-bottom: 6px;'>Ubah izin <strong>Location / Lokasi</strong> menjadi <strong>Allow / Izinkan</strong>.</li>` +
                        `<li style='margin-bottom: 6px;'>Muat ulang halaman ini untuk menerapkan perubahan.</li>` +
                        `</ol>`;
        showError(message);
        showGeolocationBlocker();
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
        const btn = document.getElementById('geolocation-btn');
        
        if (errorDiv) {
            errorDiv.style.display = 'none';
            errorDiv.innerHTML = '';
        }

        if (!navigator.geolocation) {
            showError("<div style='font-weight: 600; margin-bottom: 8px; color: #ef4444; display: flex; align-items: center;'><i class='fas fa-exclamation-circle' style='margin-right: 6px;'></i> Tidak Didukung</div> Browser Anda tidak mendukung Geolocation atau koneksi Anda saat ini tidak aman (wajib HTTPS/localhost).");
            showGeolocationBlocker();
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

                hideGeolocationBlocker();
                window.location.reload();
            },
            function (error) {
                console.error("Location access denied or error:", error);
                
                let message = "Gagal mengambil lokasi.";
                if (error.code === error.PERMISSION_DENIED) {
                    handlePermissionRevoked();
                    return;
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    message = "<div style='font-weight: 600; margin-bottom: 8px; color: #ef4444; display: flex; align-items: center;'><i class='fas fa-map-marked-alt' style='margin-right: 6px;'></i> Lokasi Tidak Tersedia</div> Informasi lokasi tidak tersedia. Pastikan perangkat Anda terhubung ke internet dan layanan lokasi (GPS) aktif.";
                } else if (error.code === error.TIMEOUT) {
                    message = "<div style='font-weight: 600; margin-bottom: 8px; color: #ef4444; display: flex; align-items: center;'><i class='fas fa-stopwatch' style='margin-right: 6px;'></i> Waktu Habis</div> Permintaan lokasi habis waktu (timeout). Silakan klik tombol untuk mencoba kembali.";
                }
                
                showError(message);
                showGeolocationBlocker();
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 }
        );
    }

    function checkAndValidateLocation() {
        const hasLat = document.cookie.split(';').some((item) => item.trim().startsWith('latitude='));
        const hasLng = document.cookie.split(';').some((item) => item.trim().startsWith('longitude='));

        if (!hasLat || !hasLng) {
            requestGeolocationPermission();
        } else {
            // Silently verify or refresh background location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        document.cookie = `latitude=${lat}; path=/; max-age=31536000; SameSite=Lax`;
                        document.cookie = `longitude=${lng}; path=/; max-age=31536000; SameSite=Lax`;
                    },
                    function(error) {
                        console.error("Background location refresh failed:", error);
                        if (error.code === error.PERMISSION_DENIED) {
                            handlePermissionRevoked();
                        } else {
                            console.warn("Non-fatal background location error:", error.message);
                        }
                    },
                    { enableHighAccuracy: false, timeout: 15000, maximumAge: 60000 }
                );
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (!areCookiesEnabled()) {
            showError("<div style='font-weight: 600; margin-bottom: 8px; color: #ef4444; display: flex; align-items: center;'><i class='fas fa-exclamation-triangle' style='margin-right: 6px;'></i> Cookie Dinonaktifkan</div> Browser Anda menolak penyimpanan Cookie. Harap aktifkan Cookie di pengaturan browser Anda untuk menggunakan aplikasi ini.");
            const btn = document.getElementById('geolocation-btn');
            if (btn) btn.style.display = 'none';
            showGeolocationBlocker();
            return;
        }

        // Use Permissions API if supported
        try {
            if (navigator.permissions && navigator.permissions.query) {
                navigator.permissions.query({ name: 'geolocation' }).then(function(permissionStatus) {
                    if (permissionStatus.state === 'denied') {
                        handlePermissionRevoked();
                    } else {
                        checkAndValidateLocation();
                    }

                    permissionStatus.onchange = function() {
                        if (permissionStatus.state === 'denied') {
                            handlePermissionRevoked();
                        }
                    };
                }).catch(function(e) {
                    console.warn("Permissions query rejected, falling back to standard checks:", e);
                    checkAndValidateLocation();
                });
            } else {
                checkAndValidateLocation();
            }
        } catch (e) {
            console.warn("Permissions API error, falling back to standard checks:", e);
            checkAndValidateLocation();
        }
    });
</script>
