<?php include 'base_require_login.php' ?>

<?php startblock('content') ?>

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

<?php endblock() ?>