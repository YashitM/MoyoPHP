<?php include 'base.php' ?>

<?php startblock('content') ?>

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
<?php endblock() ?>