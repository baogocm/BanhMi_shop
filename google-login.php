<?php
require_once 'vendor/autoload.php';
require_once 'db/connect.php';
require_once 'models/user.php';
session_start();

// Google Client Configuration
$client = new Google_Client();
$client->setClientId('1034478529057-aqc0j8bh3d3ot5q7vu4k7v9qk3d0qdmv.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/doanweb/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

// If there is no code in the URL, redirect to Google's authorization page
if (!isset($_GET['code'])) {
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit;
}
?>
