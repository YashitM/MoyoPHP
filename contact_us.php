<?php include 'base.php' ?>

<?php startblock('content') ?>
<?php
if(!isset($_SESSION['logincust'])) {
    header("Location: login.php");
    exit();
}
?>

    <div class="container padded-container">
        <form class="form-login" method="post" action="#">
            <div class="form-log-in-with-email">
                <div class="form-white-background">
                    <div class="form-title-row">
                        <h1>Contact Us</h1>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Reason*</span>
                            <select name="type" id="id_type">
                                <option selected>Select Reason</option>
                                <option value="Complaint">Complaint</option>
                                <option value="Query">Query</option>
                                <option value="Feedback">Feedback</option>
                            </select>
                        </label>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Message*</span>
                            <textarea name="message" placeholder="Enter Message" id="id_message"></textarea>
                        </label>
                    </div>
                    <div class="form-row">
                        <label>
                            <span>Attachment</span>
                            <input type="text" id="id_image_url" placeholder="Enter URL" name="image_url">
                        </label>
                    </div>
                    <div class="form-row">
                        <button type="submit" class="btn">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

<?php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!(!isset($_POST['type']) || trim($_POST['type']) == '') &&
        !(!isset($_POST['message']) || trim($_POST['message']) == '')
    ) {
        $fields = array(
            'type' => $_POST['type'],
            'message' => $_POST['message']
        );

        $have_api_key = 0;

        require_once("config.php");
        $config = new ConfigVars();

        if (isset($_SESSION['ApiKey'])) {
            $fields['Authorization'] = $_SESSION['ApiKey'];
            $have_api_key = 1;
        }
        else {
            $inner_fields = array (
                'fb_id' => $_SESSION['oauth_uid']
            );
            $inner_result = $config->send_post_request($inner_fields, "fetchuserdetailsbyfbid");
            $inner_obj = json_decode($inner_result);
            if(!$inner_obj->{'error'}) {
                $_SESSION['ApiKey'] = $inner_obj->{'apiKey'};
                $fields['Authorization'] = $_SESSION['ApiKey'];
                $have_api_key = 1;
            }
        }

        if($have_api_key === 1) {
            $result = $config->send_post_request($fields, "contactus");
            $obj = json_decode($result);
            echo "<script>
        	$.notify({
            	message: '" . $obj->{'message'} . "',
                type: 'success'
                });
            </script>";
        }
        else {
            echo "<script>
                $.notify({
                    message: 'Some error occurred. Please try again later.',
                    type: 'success'
                });
                </script>";
        }
    }
    else {
        echo "<script>
            $.notify({
                message: 'Please Complete All Fields',
                type: 'success'
            });
        </script>";
    }
}
?>

<?php endblock() ?>