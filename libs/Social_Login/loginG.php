<?php
	include_once 'src/Google_Client.php';
	include_once 'src/contrib/Google_Oauth2Service.php';
	
	// Edit Following 3 Lines
	$clientId = ''; //Application client ID
	$clientSecret = ''; //Application client secret
	$redirectURL = ''; //Application Callback URL
	
	$gClient = new Google_Client();
	$gClient->setApplicationName('Your Application Name');
	$gClient->setClientId($clientId);
	$gClient->setClientSecret($clientSecret);
	$gClient->setRedirectUri($redirectURL);
	$google_oauthV2 = new Google_Oauth2Service($gClient);
?>