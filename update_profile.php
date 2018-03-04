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
                    <h1>Update Profile</h1>
                </div>
                <div class="input-group">
                    <input type="hidden" name="fcm_id" id="id_fcm_id" />
                </div>
                <div class="form-row">
                    <label>
                        <span>Gender</span>
                        <select name="gender" id="id_gender">
                            <option selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <span>DOB</span>
                        <input type="date" id="id_dob" name="dob" placeholder="Date of Birth">
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <span>Mobile</span>
                        <input type="text" id="id_mobile" name="mobile" placeholder="Enter Mobile Number">
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <span>Company</span>
                        <input type="text" id="id_company" name="company" placeholder="Enter Company">
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <span>Ref Number</span>
                        <input type="text" id="id_ref_number" name="ref_number" placeholder="Enter Reference Number">
                    </label>
                </div>
                <div class="form-row">
                    <label>
                        <span>Aadhar</span>
                        <input type="text" id="id_aadhar" name="aadhar" placeholder="Enter Aadhar Number">
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
    if(isset($_POST['gender']) && isset($_POST['fcm_id']) && isset($_POST['dob']) && isset($_POST['mobile']) && isset($_POST['company']) && isset($_POST['aadhar'])) {
        $gender = $_POST['gender'];
        $fcm_id = $_POST['fcm_id'];
        $dob = $_POST['dob'];
        $mobile = $_POST['mobile'];
        $company = $_POST['company'];
        $aadhar = $_POST['aadhar'];
        $ref_number = "";
        if(isset($_POST['ref_number'])) {}
            $ref_number = $_POST['ref_number'];

        $db = new DbHandler();
        $db->createUser($_SESSION['oauth_uid'], $_SESSION['first_name']." ".$_SESSION['first_name'], $_SESSION['email'], $mobile, $gender, $dob, 0, $ref_number, $company);
    }
    else {
        echo "<br><br>Please Complete All Fields.";
    }
?>

<?php endblock() ?>