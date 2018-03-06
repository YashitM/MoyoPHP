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
                            <span>Reason</span>
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
                            <span>Message</span>
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
        !(!isset($_POST['message']) || trim($_POST['message']) == '') &&
        !(!isset($_POST['image_url']) || trim($_POST['image_url']) == '')
    ) {
        $type = $_POST['type'];
        $message = $_POST['message'];
        $image_url = $_POST['image_url'];

        require_once("libs/API/DbHandler.php");
        $db = new DbHandler();
        $db->createContactus($type, $message, $_SESSION['oauth_uid']);
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