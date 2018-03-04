<?php
	include_once 'libs/SocialAuth/Google_Client.php';
	include_once 'libs/SocialAuth/contrib/Google_Oauth2Service.php';
	
	// Edit Following 3 Lines
    $clientId = ''; //Application client ID
    $clientSecret = ''; //Application client secret
    $redirectURL = 'http://localhost/MoyoPHP/'; //Application Callback URL
	
	$gClient = new Google_Client();
	$gClient->setApplicationName('CarzRideOn');
	$gClient->setClientId($clientId);
	$gClient->setClientSecret($clientSecret);
	$gClient->setRedirectUri($redirectURL);
	$google_oauthV2 = new Google_Oauth2Service($gClient);
?>