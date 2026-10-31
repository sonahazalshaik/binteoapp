<?php
    $firebaseConfig = gs('firebase_config');
?>

<?php if($firebaseConfig && @$firebaseConfig->apiKey): ?>
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"></script>

<div x-data="firebaseAdminManager()" x-init="init()">
</div>

<script>
    "use strict";

    function firebaseAdminManager() {
        return {
            messaging: null,
            config: {
                apiKey: "<?php echo e($firebaseConfig->apiKey); ?>",
                authDomain: "<?php echo e($firebaseConfig->authDomain); ?>",
                projectId: "<?php echo e($firebaseConfig->projectId); ?>",
                storageBucket: "<?php echo e($firebaseConfig->storageBucket); ?>",
                messagingSenderId: "<?php echo e($firebaseConfig->messagingSenderId); ?>",
                appId: "<?php echo e($firebaseConfig->appId); ?>",
                measurementId: "<?php echo e($firebaseConfig->measurementId); ?>"
            },
            vapidKey: "<?php echo e($firebaseConfig->vapidKey); ?>",

            init() {
                console.log('[FCM-ADMIN] Firebase init started, admin authenticated:', <?php echo e(auth()->guard('admin')->check() ? 'true' : 'false'); ?>);
                if (!firebase.apps.length) {
                    firebase.initializeApp(this.config);
                    console.log('[FCM-ADMIN] Firebase app initialized');
                }
                this.messaging = firebase.messaging();
                console.log('[FCM-ADMIN] Messaging instance created');

                if (<?php echo e(auth()->guard('admin')->check() ? 'true' : 'false'); ?>) {
                    this.setupNotifications();
                }

                this.messaging.onMessage((payload) => {
                    console.log('[FCM-ADMIN] Foreground message received:', payload);
                    const notificationTitle = payload.notification?.title || payload.data?.title || 'Notification';
                    const notificationOptions = {
                        body: payload.notification?.body || payload.data?.body || '',
                        icon: payload.notification?.image || payload.data?.image || '/assets/images/logoIcon/logo.png',
                        data: {
                            url: payload.data?.url || '/'
                        }
                    };

                    navigator.serviceWorker.getRegistration().then(registration => {
                        if (registration) {
                            registration.showNotification(notificationTitle, notificationOptions);
                        } else if (Notification.permission === 'granted') {
                            new Notification(notificationTitle, notificationOptions);
                        }
                    }).catch(function(err) {
                        if (Notification.permission === 'granted') {
                            new Notification(notificationTitle, notificationOptions);
                        }
                    });
                });
            },

            async setupNotifications() {
                if (!('serviceWorker' in navigator)) {
                    console.warn('[FCM-ADMIN] Service Worker not supported');
                    return;
                }

                try {
                    console.log('[FCM-ADMIN] Registering service worker...');
                    const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js?v=' + Date.now());
                    console.log('[FCM-ADMIN] Service worker registered, scope:', registration.scope, 'state:', registration.active ? 'active' : 'waiting');
                    
                    await navigator.serviceWorker.ready;
                    console.log('[FCM-ADMIN] Service worker ready');

                    const permission = await Notification.requestPermission();
                    console.log('[FCM-ADMIN] Notification permission:', permission);
                    
                    if (permission === 'granted') {
                        console.log('[FCM-ADMIN] Requesting FCM token with vapidKey:', this.vapidKey ? this.vapidKey.substring(0, 20) + '...' : 'MISSING');
                        const token = await this.messaging.getToken({
                            vapidKey: this.vapidKey,
                            serviceWorkerRegistration: registration
                        });

                        if (token) {
                            console.log('[FCM-ADMIN] Token retrieved successfully, length:', token.length);
                            this.updateTokenOnServer(token);
                        } else {
                            console.warn('[FCM-ADMIN] getToken returned empty token');
                        }
                    } else {
                        console.warn('[FCM-ADMIN] Notification permission denied or dismissed');
                    }
                } catch (err) {
                    console.error('[FCM-ADMIN] Firebase initialization failed:', err.name, err.message, err);
                }
            },

            async updateTokenOnServer(token) {
                const deviceType = this.getDeviceType();
                console.log('[FCM-ADMIN] Sending token to server, device_type:', deviceType, 'token length:', token.length);
                try {
                    const response = await fetch("<?php echo e(route('admin.profile.firebase_token')); ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                        },
                        body: JSON.stringify({ token: token, device_type: deviceType })
                    });
                    const data = await response.json();
                    console.log('[FCM-ADMIN] Server response:', JSON.stringify(data));
                } catch (err) {
                    console.error('[FCM-ADMIN] Error updating admin token on server:', err.name, err.message);
                }
            },

            getDeviceType() {
                const ua = navigator.userAgent.toLowerCase();
                if (/mobile|iphone|ipod|android(?!.*tablet)/.test(ua)) return 'mobile';
                if (/tablet|ipad|android(?!.*mobile)/.test(ua)) return 'tablet';
                return 'desktop';
            }
        }
    }
</script>
<?php endif; ?>
<?php /**PATH C:\laragon\www\youtube-test(23-10-2026)\resources\views\partials\firebase_admin_script.blade.php ENDPATH**/ ?>