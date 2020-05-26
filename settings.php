<?php include('session.php'); ?>
<?php include("public/menubar.php"); ?>
<link href="assets/css/bootstrap-select.css" rel="stylesheet">
<script src="assets/js/ckeditor/ckeditor.js"></script>

<?php

    include('public/fcm.php');

    $qry = "SELECT * FROM tbl_settings where id = '1'";
    $result = mysqli_query($connect, $qry);
    $settings_row = mysqli_fetch_assoc($result);

    if(isset($_POST['submit'])) {

        $sql_query = "SELECT * FROM tbl_settings WHERE id = '1'";
        $img_res = mysqli_query($connect, $sql_query);
        $img_row=  mysqli_fetch_assoc($img_res);

        $data = array(
            'app_fcm_key' => $_POST['app_fcm_key'],
            'api_key' => $_POST['api_key'],
            'package_name' => $_POST['package_name'],
            'onesignal_app_id' => $_POST['onesignal_app_id'],
            'onesignal_rest_api_key' => $_POST['onesignal_rest_api_key'],
            'protocol_type' => $_POST['protocol_type'],
            'privacy_policy' => $_POST['privacy_policy']
        );

        $update_setting = Update('tbl_settings', $data, "WHERE id = '1'");

        if ($update_setting > 0) {
            $_SESSION['msg'] = "";
            header( "Location:settings.php");
            exit;
        }
    }

?>


    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active">Settings</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <form method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="header">
                            <h2>SETTINGS</h2>
                            <div class="header-dropdown m-r--5">
                                <button type="submit" name="submit" class="btn bg-blue waves-effect">Save Settings</button>
                            </div>
                                <?php if(isset($_SESSION['msg'])) { ?>
                                    <br><div class='alert alert-info'>Successfully Saved...</div>
                                    <?php unset($_SESSION['msg']); } ?>
                        </div>
                        <div class="body">

                            <div class="row clearfix">
                            <div class="col-sm-12">    
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <b>applicationId (Package Name)</b>
                                        <br>
                                        <a href="" data-toggle="modal" data-target="#modal-package-name">What is my package name?</a>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <div class="font-12">applicationId (Package Name)</div>
                                            <input type="text" class="form-control" name="package_name" id="package_name" value="<?php echo $settings_row['package_name'];?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <b>Site Protocol</b>
                                        <br>
                                        <font color="#337ab7">Choose your website protocol type</font>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <div class="font-12">Protocol Type</div>
                                                <select class="form-control show-tick" name="protocol_type" id="protocol_type">
                                                        <?php if ($settings_row['protocol_type'] == 'http://') { ?>
                                                            <option value="http://" selected="selected">HTTP</option>
                                                            <option value="https://">HTTPS</option>
                                                        <?php } else { ?>
                                                            <option value="http://">HTTP</option>
                                                            <option value="https://" selected="selected">HTTPS</option>
                                                        <?php } ?>
                                                </select>
                                        </div>
                                    </div>
                                </div> 
                            </div>                               

                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <b>FCM Server Key</b>
                                        <br>
                                        <a href="" data-toggle="modal" data-target="#modal-server-key">How to obtain your FCM Server Key?</a>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    
                                    <div class="form-group">
                                        <div class="form-line">
                                            <div class="font-12">FCM Server Key</div>
                                            <textarea class="form-control" rows="3" name="app_fcm_key" id="app_fcm_key" required><?php echo $settings_row['app_fcm_key'];?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <b>OneSignal APP ID</b>
                                        <br>
                                        <a href="" data-toggle="modal" data-target="#modal-onesignal">Where do I get my OneSignal app id?</a>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <div class="font-12">OneSignal APP ID</div>
                                            <input type="text" class="form-control" name="onesignal_app_id" id="onesignal_app_id" value="<?php echo $settings_row['onesignal_app_id'];?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <b>OneSignal Rest API Key</b>
                                        <br>
                                        <a href="" data-toggle="modal" data-target="#modal-onesignal">Where do I get my OneSignal Rest API Key?</a>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <div class="font-12">OneSignal Rest API Key</div>
                                            <input type="text" class="form-control" name="onesignal_rest_api_key" id="onesignal_rest_api_key" value="<?php echo $settings_row['onesignal_rest_api_key'];?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <b>Your API Key</b>
                                        <br>
                                        <a href="" data-toggle="modal" data-target="#modal-api-key">Where I have to put my API Key?</a>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <div class="font-12">API Key</div>
                                            <input type="text" class="form-control" name="api_key" id="api_key" value="<?php echo $settings_row['api_key'];?>" required>
                                        </div>
                                        <br>
                                        <a href="change-api-key.php" class="btn bg-blue waves-effect">Change API Key</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <b>Privacy Policy</b>
                                        <br>
                                        <font color="#337ab7">This privacy policy will be displayed in your android app</font>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    
                                    <div class="form-group">
                                        <div class="form-line">
                                            <div class="font-12">Privacy Policy</div>
                                            <textarea class="form-control" name="privacy_policy" id="privacy_policy" class="form-control" cols="60" rows="10" required><?php echo $settings_row['privacy_policy'];?></textarea>

                                            <?php if ($ENABLE_RTL_MODE == 'true') { ?>
                                            <script>                             
                                                CKEDITOR.replace( 'privacy_policy' );
                                                CKEDITOR.config.contentsLangDirection = 'rtl';
                                            </script>
                                            <?php } else { ?>
                                            <script>                             
                                                CKEDITOR.replace( 'privacy_policy' );
                                            </script>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            </div>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
            
        </div>

    </section>


<?php include('public/footer.php'); ?>