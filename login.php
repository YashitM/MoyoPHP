<?php include 'base.php' ?>

<?php startblock('content') ?>
    <div class="container" style="padding-bottom: 30%;">
        <div class="heading-text">
            Login
        </div>
        <div class="social-buttons">
            <a  href=""><br></a>
            <?php
            echo '<a href="loginFB.php" title="Facebook" class="social-button facebook"><i class="fa fa-facebook"></i></a>';
            include_once 'loginG.php';
            if(isset($_GET['code'])){
                $gClient->authenticate($_GET['code']);
                $_SESSION['token'] = $gClient->getAccessToken();
                header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
            }
            if (isset($_SESSION['token'])) {
                $gClient->setAccessToken($_SESSION['token']);
            }
            if ($gClient->getAccessToken())
            {
                $gpUserProfile = $google_oauthV2->userinfo->get();
                $_SESSION['oauth_provider'] = 'Google';
                $_SESSION['oauth_uid'] = $gpUserProfile['id'];
                $_SESSION['first_name'] = $gpUserProfile['given_name'];
                $_SESSION['last_name'] = $gpUserProfile['family_name'];
                $_SESSION['email'] = $gpUserProfile['email'];
                $_SESSION['logincust']='yes';
            } else {
                $authUrl = $gClient->createAuthUrl();
                $output= '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'" title="Google" class="social-button google"><i class="fa fa-google-plus"></i><br></a>';
                echo $output;
            }
            ?>
        </div>
        <br>
    </div>

<?php endblock() ?>