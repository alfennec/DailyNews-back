<?php include('session.php'); ?>
<?php include('public/menubar.php'); ?>

<?php 
    if (isset($_GET['id'])) {
        $ID = $_GET['id'];
    } else {
        $ID = "";
    }
            
    // create array variable to handle error
    $error = array();
            
    // create array variable to store data from database
    $data = array();
        
    // get data from reservation table
    $sql_query = "SELECT id, title, message, image, url_notification, created, modified FROM notification WHERE id = ?";
        
    $stmt = $connect->stmt_init();
    if($stmt->prepare($sql_query)) {    
        // Bind your variables to replace the ?s
        $stmt->bind_param('s', $ID);
        // Execute query
        $stmt->execute();
        // store result 
        $stmt->store_result();
        $stmt->bind_result(
                $data['id'], 
                $data['title'],
                $data['message'],
                $data['image'],
                $data['url_notification'],
                $data['created'],
                $data['modified']
                );
        $stmt->fetch();
        $stmt->close();
    }
            
?>

<?php
  /*$setting_qry    = "SELECT * FROM tbl_settings where id = '1'";
  $setting_result = mysqli_query($connect, $setting_qry);
  $settings_row   = mysqli_fetch_assoc($setting_result);

  $onesignal_app_id = $settings_row['onesignal_app_id']; 
  $onesignal_rest_api_key = $settings_row['onesignal_rest_api_key'];
  $protocol_type = $settings_row['protocol_type'];

  define("ONESIGNAL_APP_ID", $onesignal_app_id);
  define("ONESIGNAL_REST_KEY", $onesignal_rest_api_key);

  $cat_qry = "SELECT * FROM tbl_fcm_template ORDER BY message";
  $cat_result = mysqli_query($connect, $cat_qry); */

    function sendMessage($title, $cat_name, $description, $external_link, $big_image) 
    {
        $content = array(
                         "en" => $description                                                 
                         );

        $fields = array(
                        'app_id' => "e35764e1-d593-4576-9525-18e8ff3d43f4",
                        'included_segments' => array('All'),                                            
                        'data' => array("foo" => "bar","cat_id"=> "0","cat_name"=>$cat_name, "external_link"=>$external_link),
                        'headings'=> array("en" => $title),
                        'contents' => $content,
                        'big_picture' => $big_image         
                        );

        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                   'Authorization: Basic ZjkyODY4Y2YtZTMyMy00NjQ2LTk3NTItYTAzNjllOWYxM2Fh'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);        
        
        echo "Congratulations, push notification sent...";

        return $response;
    }

  if (isset($_POST['submit'])) 
  {

        $title = $_POST['title'];
        $cat_name = '';
        $description = $_POST['message'];
        $external_link = $_POST['external_link'];
        $big_image = "http://localhost/DailyNews/upload/notification/"+$_POST['image'];
        

        if ($_POST['external_link'] != "") 
        {
            $external_link = $_POST['external_link'];
        } else 
        {
            $external_link = "no_url";
        } 

        sendMessage($title, $cat_name, $description, $external_link, $big_image);

        $_SESSION['msg'] = "Congratulations, push notification sent...";
        header("Location:push-notification.php");
        exit; 

  }
  
?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="push-notification.php">Manage Notification</a></li>
            <li class="active">Send Notification</a></li>
        </ol>

        <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form method="post" enctype="multipart/form-data">
                        <div class="card">
                            <div class="header">
                                <h2>SEND NOTIFICATION</h2>
                            </div>
                            <div class="body">

                                <div class="row clearfix">

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Title *</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="title" id="title" placeholder="Title" value="<?php echo $data['title']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Message *</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="message" id="message" placeholder="Message" value="<?php echo $data['message']; ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="file" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="upload/notification/<?php echo $data['image']; ?>" data-show-remove="false" disabled/>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Url (Optional)</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="external_link" id="external_link" placeholder="http://www.google.com" value="<?php echo $data['url_notification']; ?>" >
                                        </div>
                                    </div>
                                    <input type="hidden" name="id" id="id" value="0" />
                                    <input type="hidden" name="image" id="image" value="<?php echo $data['image']; ?>" />

                                    <div class="col-sm-12">
                                        <button class="btn bg-blue waves-effect pull-right" type="submit" name="submit">SEND NOW</button>
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