/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');
   
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
firebase.initializeApp({

        apiKey: "{{ env('FIREBASE_APIKEY') }}",
        authDomain: "{{ env('FIREBASE_AUTHDOMAIN') }}",
        projectId: "{{ env('FIREBASE_PROJECTID') }}",
        storageBucket: "{{ env('FIREBASE_STORAGEBUCKET') }}",
        messagingSenderId: "{{ env('FIREBASE_MESSAGINGSENDER') }}",
        appId: "{{ env('FIREBASE_APPID') }}",
        measurementId: "{{ env('FIREBASE_MEASUREMENTID') }}"
    });
  
/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    /* Customize notification here */
    const notificationTitle = "Background Message Title";
    const notificationOptions = {
        body: "Background Message body.",
        icon: "/itwonders-web-logo.png",
    };
  
    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});


// apiKey: "{{ env('FIREBASE_APIKEY') }}",
//         authDomain: "{{ env('FIREBASE_AUTHDOMAIN') }}",
//         projectId: "{{ env('FIREBASE_PROJECTID') }}",
//         storageBucket: "{{ env('FIREBASE_STORAGEBUCKET') }}",
//         messagingSenderId: "{{ env('FIREBASE_MESSAGINGSENDER') }}",
//         appId: "{{ env('FIREBASE_APPID') }}",
//         measurementId: "{{ env('FIREBASE_MEASUREMENTID') }}"


// apiKey: "AIzaSyCVTmwKnKi3xu0A8w5O4gHDg6BWNB1cZ4g",
// authDomain: "budut-22.firebaseapp.com",
// databaseURL: "https://budut-22.firebaseio.com",
// projectId: "budut-22",
// storageBucket: "budut-22.appspot.com",
// messagingSenderId: "893085591015",
// appId: "1:893085591015:web:c2b38df485ba190e368942",
// measurementId: "G-T9FT4S3SK1"