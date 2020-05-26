<link href="assets/css/bootstrap-select.css" rel="stylesheet">
<script src="assets/js/ckeditor/ckeditor.js"></script>

 <?php include ('functions.php'); ?>

    <?php

        $sql_query = "SELECT id, name FROM category ORDER BY name ASC";
                
        $stmt_category = $connect->stmt_init();
        if($stmt_category->prepare($sql_query)) 
        {   
            // Execute query
            $stmt_category->execute();
            // store result 
            $stmt_category->store_result();
            $stmt_category->bind_result($category_data['id'], $category_data['name']);      
        }

        $sql_query = "SELECT id, full_name FROM admin ORDER BY full_name ASC";
                
        $stmt_admin = $connect->stmt_init();
        if($stmt_admin->prepare($sql_query)) 
        {   
            // Execute query
            $stmt_admin->execute();
            // store result 
            $stmt_admin->store_result();
            $stmt_admin->bind_result($admin_data['id'], $admin_data['full_name']);      
        }
        
        //$max_serve = 10;
            
        if(isset($_POST['btnAdd']))
        {
            $id_category = $_POST['id_category'];
            $title = $_POST['title'];
            $date_news = $_POST['date_news'];
            //$content_type = $_POST['content_type'];
            $description = $_POST['description'];
            $wname = $_POST['wname'];

            $created = date('Y-m-d H:i:s');
            $modified = date('Y-m-d H:i:s');
                
            // get image info
            $news_image = $_FILES['news_image']['name'];
            $image_error = $_FILES['news_image']['error'];
            $image_type = $_FILES['news_image']['type'];
            
                
            // create array variable to handle error
            $error = array();
            
            if(empty($news_image)){
                $error['news_image'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
            }
                
            if(empty($id)){
                $error['cid'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
            }               
                
            // common image file extensions
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            
            // get image file extension
            error_reporting(E_ERROR | E_PARSE);
            $extension = end(explode(".", $_FILES["news_image"]["name"]));
                    
            if ($image_error > 0) {
                $error['news_image'] = " <span class='font-12 col-red'>You're not insert images!!</span>";
            } else if(!(($image_type == "image/gif") || 
                ($image_type == "image/jpeg") || 
                ($image_type == "image/jpg") || 
                ($image_type == "image/x-png") ||
                ($image_type == "image/png") || 
                ($image_type == "image/pjpeg")) &&
                !(in_array($extension, $allowedExts))){
            
                $error['news_image'] = " <span class='font-12'>Image type must jpg, jpeg, gif, or png!</span>";
            }
                
            if (empty($error['news_image'])) 
            {        
                // create random image file name
                $string = '0123456789';
                $file = preg_replace("/\s+/", "_", $_FILES['news_image']['name']);
                $function = new functions;
                $news_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
                    
                // upload new image
                $unggah = 'upload/news/'.$news_image;
                $upload = move_uploaded_file($_FILES['news_image']['tmp_name'], $unggah);
        
                // insert new data to menu table
                $sql_query = "INSERT INTO news (id_category, title, date_news, description, news_photo, wname, created, modified)
                        VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
                        
                $upload_image = $news_image;
                $stmt = $connect->stmt_init();
                if($stmt->prepare($sql_query)) 
                {    
                    // Bind your variables to replace the ?s
                    $stmt->bind_param('ssssssss', 
                                $id_category, 
                                $title, 
                                $date_news, 
                                $description,
                                $upload_image,
                                $wname,
                                $created,
                                $modified
                                );
                    // Execute query
                    $stmt->execute();
                    // store result 
                    $result = $stmt->store_result();
                    $stmt->close();
                }   
               
                if ($result) {
                    $error['add_radio'] = "<br><div class='alert alert-info'>News Added Successfully...</div>";
                } else {
                    $error['add_radio'] = "<br><div class='alert alert-danger'>Added Failed</div>";
                }
            }
                
            }
    ?>

   <section class="content">
   
        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage-news.php">Manage News</a></li>
            <li class="active">Add News</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                	<form id="form_validation" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="header">
                            <h2>EDIT NEWS</h2>
                                <?php echo isset($error['add_radio']) ? $error['add_radio'] : '';?>
                        </div>
                        <div class="body">

                        	<div class="row clearfix">
                            <div>
                                    <div class="form-group form-float col-sm-12">
                                        <div class="font-12">Title News</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="title" id="title" placeholder="Title news" required>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Category</div>
                                        <select class="form-control show-tick" name="id_category" id="id_category">
                                            <?php while($stmt_category->fetch()){ ?>
                                                <option value="<?php echo $category_data['id']; ?>"><?php echo $category_data['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Date of the news</div>
                                        <div class="form-line">
                                            <input type="date" class="form-control" name="date_news" id="date_news" required>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Name Writter</div>
                                        <select class="form-control show-tick" name="wname" id="wname">
                                            <?php while($stmt_admin->fetch()){ ?>
                                                <option value="<?php echo $admin_data['full_name']; ?>"><?php echo $admin_data['full_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <div class="font-12">News contenent</div>
                                                <textarea class="form-control" name="description" id="description" class="form-control" cols="60" rows="10" required></textarea>
                                                <script>                             
                                                    CKEDITOR.replace( 'description' );
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                    <div class="font-12 ex1">Image ( jpg / png )</div>
                                    <div class="form-group">
                                        <input type="file" name="news_image" id="news_image" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png gif" />
                                        <div class="div-error"><?php echo isset($error['news_image']) ? $error['news_image'] : '';?></div>
                                    </div>
                                    </div>                                

                                    <div class="col-sm-12">
                                    <button type="submit" name="btnAdd" class="btn bg-blue waves-effect pull-right ">SUBMIT</button>
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