<?php include 'includes/config.php' ?>
<!DOCTYPE html>
<html>
 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Your Radio App</title>
    <!-- Favicon-->
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="assets/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="assets/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="assets/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="assets/plugins/morrisjs/morris.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Wait Me Css -->
    <link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" />

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="assets/css/theme.css" rel="stylesheet" />

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="assets/css/time-picker.css" rel="stylesheet" />

     <!-- JQuery DataTable Css -->
    <link href="assets/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    

    <link href="assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <!-- Latest compiled and minified CSS -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css"> -->

    <!-- Sweetalert Css -->
    <link href="assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" />
       <!-- Light Gallery Plugin Css -->
    <link href="assets/plugins/light-gallery/css/lightgallery.css" rel="stylesheet">

    <link href="assets/css/sticky-footer.css" rel="stylesheet">

    <link href="assets/css/dropify.css" type="text/css" rel="stylesheet">

    <?php if ($ENABLE_RTL_MODE == 'true') { ?>
    <link href="assets/css/rtl.css" rel="stylesheet">
    <?php } ?>

   <script type="text/javascript">
  tinymce.init({
    selector: '#tinymce',
    fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
    theme: "modern",
    menubar: false,
    plugins: [
            'advlist autolink lists charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime nonbreaking save directionality',
            'emoticons template paste textcolor colorpicker textpattern', 'link image'
        ],
        toolbar1: 'insertfile undo redo | forecolor backcolor | bold italic fontselect fontsizeselect  | alignleft aligncenter alignright alignjustify | outdent indent | media link | image',
        
        image_advtab: true, file_browser_callback: RoxyFileBrowser


  });

  function RoxyFileBrowser(field_name, url, type, win) {
              var roxyFileman = 'fileman/index.html';
              if (roxyFileman.indexOf("?") < 0) {     
                roxyFileman += "?type=" + type;   
              }
              else {
                roxyFileman += "&type=" + type;
              }
              roxyFileman += '&input=' + field_name + '&value=' + win.document.getElementById(field_name).value;
              if(tinyMCE.activeEditor.settings.language){
                roxyFileman += '&langCode=' + tinyMCE.activeEditor.settings.language;
              }
              tinyMCE.activeEditor.windowManager.open({
                 file: roxyFileman,
                 title: 'Image Upload',
                 width: 850, 
                 height: 650,
                 resizable: "yes",
                 plugins: "media",
                 inline: "yes",
                 close_previous: "no"  
              }, {     window: win,     input: field_name    });
              return false; 
            }
  </script>

        
</head>

<?php

    $error = false;

    if (isset($_POST['btnSubmit'])) {

        $item_purchase_code = $_POST['item_purchase_code'];

        if (strlen($item_purchase_code) < 36) {
            $error[] = 'Invalid Purchase Code!';
        }

        if (strlen($item_purchase_code) > 36) {
            $error[] = 'Invalid Purchase Code!';
        }

        if (empty($item_purchase_code)) {
                $error[] = 'Purchase code can not be empty!';
            }

        if(!$error) {

            $sql = "INSERT INTO tbl_purchase_code (item_purchase_code) VALUES (?)";

            $insert = $connect->prepare($sql);
            $insert->bind_param('s', $item_purchase_code);
            $insert->execute();

            $succes =<<<EOF
            <script>
            alert('Thank you');
            window.location = 'dashboard.php';
            </script>
EOF;
            echo $succes;
        }

    }

?>

<body class="theme-blue">
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="verify-purchase-code.php">YOUR RADIO APP</a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="logout.php"><i class="material-icons">power_settings_new</i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section class="container">
        <br><br><br><br><br>
        <br><br><br><br><br>
        <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <form method="post" id="form_validation" enctype="multipart/form-data">
                    <div class="card">
                        <?php echo $error ? '<div class="alert alert-info">'. implode('<br>', $error) . '</div>' : '';?>
                        <div class="body">

                            <div class="row clearfix">
                                <div class="col-sm-12">
                                <center>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <h4><img src="assets/images/ic_envato.png" width="24" height="24"> Please Verify your Purchase Code to Continue Using Admin Panel.</h4>
                                            <hr>
                                            <br>
                                            <input type="text" class="form-control" name="item_purchase_code" id="item_purchase_code" placeholder="Your item purchase code here" value="" required>
                                        </div>
                                    </div>

                                    <button type="submit" name="btnSubmit" class="btn bg-blue waves-effect">SUBMIT</button>

                                    <br>
                                    <br>
                                    <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank"><b>Where Is My Purchase Code?</b></a>
                                    <br>
                                    <a href="https://codecanyon.net/item/your-radio-app/13706746" target="_blank"><b>Don't Have Purchase Code? I Want to Purchase it first.</b></a>
                                </center>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>

                </div>
            </div>
            
        </div>

        
    </section>