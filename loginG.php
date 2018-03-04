<?php
	include_once 'libs/SocialAuth/Google_Client.php';
	include_once 'libs/SocialAuth/contrib/Google_Oauth2Service.php';
	
	// Edit Following 3 Lines
    $clientId = '659267976289-pfhtpi394m8lp0pqredrpp5l0es5sddt.apps.googleusercontent.com'; //Application client ID
    $clientSecret = 'fcTj0WOsiqSb2-wdSvuAED4J'; //Application client secret
    $redirectURL = 'http://localhost/MoyoPHP/'; //Application Callback URL
	
	$gClient = new Google_Client();
	$gClient->setApplicationName('CarzRideOn');
	$gClient->setClientId($clientId);
	$gClient->setClientSecret($clientSecret);
	$gClient->setRedirectUri($redirectURL);
	$google_oauthV2 = new Google_Oauth2Service($gClient);
?>