<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firebase</title>
</head>

<body>

    <script type="module">
        // Import the functions you need from the SDKs you need
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.13.1/firebase-app.js";
        import {
            onMessage,
            getMessaging,
            getToken
        } from "https://www.gstatic.com/firebasejs/10.13.1/firebase-messaging.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
           
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        // Notification.requestPermission().then(permission => {
        //     if (permission === 'granted') {
        //         console.log('Notification permission granted.');
        //         getToken();
        //     } else {
        //         console.log('Unable to get permission to notify.');
        //     }
        // });
        onMessage(messaging, (payload) => {
            console.log('Message received. ', payload);
            // ...
        });
        getToken(messaging, {
            vapidKey: ""
        }).then((currentToken) => {
            if (currentToken) {
                console.log('here', currentToken);
                // Send the token to your server and update the UI if necessary
                // ...
            } else {
                // Show permission request UI
                console.log('No registration token available. Request permission to generate one.');
                // ...
            }
        }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);
            // ...
        });
    </script>
</body>

</html>