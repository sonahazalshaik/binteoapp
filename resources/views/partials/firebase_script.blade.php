@php
    $firebaseConfig = gs('firebase_config');
@endphp

@if($firebaseConfig && @$firebaseConfig->apiKey)
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"></script>

<div x-data="firebaseManager()" x-init="init()">
</div>

<script>
    "use strict";

    function firebaseManager() {
        return {
            messaging: null,
            config: {
                apiKey: "{{ $firebaseConfig->apiKey }}",
                authDomain: "{{ $firebaseConfig->authDomain }}",
                projectId: "{{ $firebaseConfig->projectId }}",
                storageBucket: "{{ $firebaseConfig->storageBucket }}",
                messagingSenderId: "{{ $firebaseConfig->messagingSenderId }}",
                appId: "{{ $firebaseConfig->appId }}",
                measurementId: "{{ $firebaseConfig->measurementId }}"
            },
            vapidKey: "{{ $firebaseConfig->vapidKey }}",

            init() {
                console.log('[FCM] Firebase init started, user authenticated:', {{ auth()->check() ? 'true' : 'false' }});
                if (!firebase.apps.length) {
                    firebase.initializeApp(this.config);
                    console.log('[FCM] Firebase app initialized');
                }
                this.messaging = firebase.messaging();
                console.log('[FCM] Messaging instance created');

                if ({{ auth()->check() ? 'true' : 'false' }}) {
                    this.setupNotifications();
                }

                this.messaging.onMessage((payload) => {
                    console.log('[FCM] Foreground message received:', payload);

                    const notificationTitle = payload.notification?.title || payload.data?.title || 'Notification';
                    const notificationOptions = {
                        body: payload.notification?.body || payload.data?.body || '',
                        icon: payload.notification?.image || payload.data?.image || '/assets/images/logoIcon/logo.png',
                        data: {
                            url: payload.data?.url || '/'
                        },
                        requireInteraction: false,
                        tag: 'fcm-foreground-' + Date.now(),
                        renotify: true
                    };

                    // Show native push notification even when user is on the site
                    if (Notification.permission === 'granted' && navigator.serviceWorker.controller) {
                        navigator.serviceWorker.getRegistration().then(registration => {
                            if (registration) {
                                registration.showNotification(notificationTitle, notificationOptions);
                            } else if (Notification.permission === 'granted') {
                                new Notification(notificationTitle, notificationOptions);
                            }
                        }).catch(function(err) {
                            console.error('[FCM] Failed to show foreground notification via SW:', err);
                            // Fallback to Notification API directly
                            try {
                                new Notification(notificationTitle, notificationOptions);
                            } catch(e) {
                                console.error('[FCM] Fallback notification also failed:', e);
                            }
                        });
                    } else if (Notification.permission === 'granted') {
                        // Fallback: use Notification API directly if no SW controller
                        try {
                            new Notification(notificationTitle, notificationOptions);
                            console.log('[FCM] Foreground notification shown via Notification API');
                        } catch(e) {
                            console.error('[FCM] Notification API failed:', e);
                        }
                    }
                });
            },

            async setupNotifications() {
                if (!('serviceWorker' in navigator)) {
                    console.warn('[FCM] Service Worker not supported in this browser');
                    return;
                }

                try {
                    console.log('[FCM] Registering service worker...');
                    const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                    console.log('[FCM] Service worker registered, scope:', registration.scope, 'state:', registration.active ? 'active' : 'waiting');
                    
                    await navigator.serviceWorker.ready;
                    console.log('[FCM] Service worker ready');

                    const permission = await Notification.requestPermission();
                    console.log('[FCM] Notification permission:', permission);
                    
                    if (permission === 'granted') {
                        console.log('[FCM] Requesting FCM token with vapidKey:', this.vapidKey ? this.vapidKey.substring(0, 20) + '...' : 'MISSING');
                        const token = await this.messaging.getToken({
                            vapidKey: this.vapidKey,
                            serviceWorkerRegistration: registration
                        });

                        if (token) {
                            console.log('[FCM] Token retrieved successfully, length:', token.length);
                            this.updateTokenOnServer(token);
                        } else {
                            console.warn('[FCM] getToken returned empty token');
                        }
                    } else {
                        console.warn('[FCM] Notification permission denied or dismissed');
                    }
                } catch (err) {
                    console.error('[FCM] Firebase initialization failed:', err.name, err.message, err);
                }
            },

            async updateTokenOnServer(token) {
                const deviceType = this.getDeviceType();
                console.log('[FCM] Sending token to server, device_type:', deviceType, 'token length:', token.length);
                try {
                    const response = await fetch("{{ route('update.fcm.token') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ token: token, device_type: deviceType })
                    });
                    const data = await response.json();
                    console.log('[FCM] Server response:', JSON.stringify(data));
                } catch (err) {
                    console.error('[FCM] Error updating token on server:', err.name, err.message);
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
@endif
