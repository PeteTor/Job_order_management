<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();



$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        $_SESSION['user_type'] = 'google';
        $_SESSION['name'] = $userInfo->name;
        $_SESSION['email'] = $userInfo->email;
        $_SESSION['user_image'] = $userInfo->picture;

        $_SESSION['success'] = "Googe login Successful";

        header("Location: ../user/user_page.php");
        exit();
    } else {
        $_SESSION['error'] = "Login with Google failed";
        header("Location: ../auth/index.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid login";
    header("Location: ../auth/index.php");
    exit();
}
