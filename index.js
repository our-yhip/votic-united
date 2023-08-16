const functions = require('firebase-functions');
const admin = require('firebase-admin');
const cors = require('cors')({origin: true});
admin.initializeApp();

exports.userAuth = functions.https.onRequest((request, response) => {
    cors(request, response, async () => {
        const email = request.body.email;
        const password = request.body.password;

        const db = admin.firestore();
        const userRef = db.collection('users');
        const snapshot = await userRef.where('email', '==', email).get();

        if (snapshot.empty) {
            // No matching documents, create the user
            await db.collection('users').add({
                email: email,
                password: password, // In a real app, never store passwords in plain text
                time_added: admin.firestore.FieldValue.serverTimestamp()
            });
            response.send({result: "User registered."});
        } else {
            // Check if password matches
            snapshot.forEach(doc => {
                if(doc.data().password == password) {
                    response.send({result: "Login successful."});
                } else {
                    response.send({result: "Incorrect password."});
                }
            });
        }
    });
});
