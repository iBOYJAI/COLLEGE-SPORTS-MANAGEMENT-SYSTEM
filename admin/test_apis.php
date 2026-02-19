<?php
// Simple API test page
require_once '../config.php';

?>
<!DOCTYPE html>
<html>

<head>
    <title>API Test</title>
    <style>
        body {
            font-family: monospace;
            padding: 20px;
        }

        .test {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
        }

        .success {
            background: #dfd;
        }

        .error {
            background: #fdd;
        }
    </style>
</head>

<body>
    <h1>API Diagnostic Test</h1>

    <h2>1. Testing Search API</h2>
    <div id="search-test" class="test">Testing...</div>

    <h2>2. Testing Notification API</h2>
    <div id="notif-test" class="test">Testing...</div>

    <h2>3. Check Console for Detailed Logs</h2>
    <p>Press F12 and check the Console tab</p>

    <script>
        // Test Search API
        fetch('../api/search.php?q=test')
            .then(res => {
                console.log('Search Response Status:', res.status);
                return res.text();
            })
            .then(text => {
                console.log('Search Raw Response:', text);
                try {
                    const data = JSON.parse(text);
                    console.log('Search Parsed Data:', data);
                    document.getElementById('search-test').innerHTML = 'SUCCESS: ' + JSON.stringify(data);
                    document.getElementById('search-test').className = 'test success';
                } catch (e) {
                    console.error('Search Parse Error:', e);
                    document.getElementById('search-test').innerHTML = 'ERROR: ' + text;
                    document.getElementById('search-test').className = 'test error';
                }
            })
            .catch(err => {
                console.error('Search Fetch Error:', err);
                document.getElementById('search-test').innerHTML = 'FETCH ERROR: ' + err;
                document.getElementById('search-test').className = 'test error';
            });

        // Test Notification API
        fetch('../api/notifications.php')
            .then(res => {
                console.log('Notification Response Status:', res.status);
                return res.text();
            })
            .then(text => {
                console.log('Notification Raw Response:', text);
                try {
                    const data = JSON.parse(text);
                    console.log('Notification Parsed Data:', data);
                    document.getElementById('notif-test').innerHTML = 'SUCCESS: ' + JSON.stringify(data);
                    document.getElementById('notif-test').className = 'test success';
                } catch (e) {
                    console.error('Notification Parse Error:', e);
                    document.getElementById('notif-test').innerHTML = 'ERROR: ' + text;
                    document.getElementById('notif-test').className = 'test error';
                }
            })
            .catch(err => {
                console.error('Notification Fetch Error:', err);
                document.getElementById('notif-test').innerHTML = 'FETCH ERROR: ' + err;
                document.getElementById('notif-test').className = 'test error';
            });
    </script>
</body>

</html>