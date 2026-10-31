importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: "AIzaSyBhllyvVspP7lXIhQrYmLwZZQvT6tiRpw8",
  authDomain: "matrimony-v1-ed605.firebaseapp.com",
  projectId: "matrimony-v1-ed605",
  storageBucket: "matrimony-v1-ed605.firebasestorage.app",
  messagingSenderId: "328237735040",
  appId: "1:328237735040:web:0531c01f764c3b524e9a65",
  measurementId: "G-BQPL9PMCS2"
});

const messaging = firebase.messaging();

self.addEventListener('install', () => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(clients.claim());
});

messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);

  try {
    const notificationTitle = payload.notification?.title || payload.data?.title || 'Notification';
    const notificationOptions = {
      body: payload.notification?.body || payload.data?.body || '',
      icon: payload.notification?.image || payload.data?.image || '/assets/images/logoIcon/logo.png',
      data: {
          url: payload.data?.url || '/'
      }
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
    console.log('[firebase-messaging-sw.js] Background notification shown:', notificationTitle);
  } catch (err) {
    console.error('[firebase-messaging-sw.js] Failed to show notification:', err.name, err.message, err);
  }
});

self.addEventListener('notificationclick', function(event) {
  event.notification.close();
  
  let urlToOpen = '/';
  
  if (event.notification.data && event.notification.data.url) {
      urlToOpen = event.notification.data.url;
  } else if (event.notification.data && event.notification.data.FCM_MSG && event.notification.data.FCM_MSG.data && event.notification.data.FCM_MSG.data.url) {
      urlToOpen = event.notification.data.FCM_MSG.data.url;
  }

  
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(windowClients) {
      for (var i = 0; i < windowClients.length; i++) {
        var client = windowClients[i];
        if (client.url === urlToOpen && 'focus' in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow(urlToOpen);
      }
    })
  );
});
