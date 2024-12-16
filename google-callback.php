<?php
require_once 'vendor/autoload.php';
require_once 'db/connect.php';
require_once 'models/user.php';
session_start();

// Google Client Configuration
$client = new Google_Client();
$client->setClientId('YOUR_CLIENT_ID');
$client->setClientSecret('YOUR_CLIENT_SECRET');
$client->setRedirectUri('http://localhost/doanweb/google-callback.php');

try {
    // Get token from code
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // Get user info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    
    $email = $google_account_info->email;
    $name = $google_account_info->name;
    
    // Initialize User model
    $userModel = new User($conn);
    
    // Check if user exists
    $user = $userModel->getUserByEmail($email);
    
    if (!$user) {
        // Create new user
        $username = explode('@', $email)[0];
        $random_password = bin2hex(random_bytes(8));
        
        $userModel->registerGoogleUser([
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'password' => password_hash($random_password, PASSWORD_DEFAULT)
        ]);
        
        $user = $userModel->getUserByEmail($email);
    }
    
    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    
    // Redirect based on role
    if ($_SESSION['role'] == 1) {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: index.php');
    }
    exit();
    
} catch (Exception $e) {
    error_log('Google Login Error: ' . $e->getMessage());
    header('Location: login.php?error=google_login_failed');
    exit();
}
?>
