 <?php include ('functions.php'); ?>

<link href="assets/css/bootstrap-select.css" rel="stylesheet">
<script src="assets/js/ckeditor/ckeditor.js"></script>

	<?php 

		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}

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
		
			
		$sql_query = "SELECT news_photo FROM news WHERE id = ?";
		
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query))
		{	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result($previous_news_photo);
			$stmt->fetch();
			$stmt->close();
		}
		
		
		if(isset($_POST['btnEdit']))
		{
			

			$id_category = $_POST['id_category'];
            $title = $_POST['title'];
            $date_news = $_POST['date_news'];
            //$content_type = $_POST['content_type'];
            $description = $_POST['description'];
            $wname = $_POST['wname'];

            $modified = date('Y-m-d H:i:s');

			// get image info
			$news_photo = $_FILES['news_photo']['name'];
			$image_error = $_FILES['news_photo']['error'];
			$image_type = $_FILES['news_photo']['type'];
				
			// create array variable to handle error
			$error = array();
			
			if(empty($title))
			{
				$error['title'] = " <span class='label label-danger'>Required, please fill out this field!!</span>";
			}
				
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["news_photo"]["name"]));
			
			if(!empty($news_photo)){
				if(!(($image_type == "image/gif") || 
					($image_type == "image/jpeg") || 
					($image_type == "image/jpg") || 
					($image_type == "image/x-png") ||
					($image_type == "image/png") || 
					($image_type == "image/pjpeg")) &&
					!(in_array($extension, $allowedExts)))
					{
						$error['news_photo'] = "*<span class='label label-danger'>Image type must jpg, jpeg, gif, or png!</span>";
					}
			}
			
			

			if(!empty($title) && !empty($id_category) && empty($error['news_photo']))
			{
				if(!empty($news_photo))
				{
					
					// create random image file name
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['news_photo']['name']);
					$function = new functions;
					$news_photo = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
				
					// delete previous image
					$delete = unlink('upload/news/'."$previous_news_photo");
					$delete = unlink('upload/thumbs/'."$previous_news_photo");
					
					// upload new image
					$unggah = 'upload/news/'.$news_photo;
					$upload = move_uploaded_file($_FILES['news_photo']['tmp_name'], $unggah);	 
	  
					// updating all data
					$sql_query = "UPDATE news 
							SET id_category = ? , title = ?, date_news = ?, description = ?,  news_photo = ?, wname = ?, modified = ?
							WHERE id = ?";
					
					$upload_image = $news_photo;
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
									$modified,
									$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}else{
					
					
					// updating all data except image file
					$sql_query = "UPDATE news 
							SET id_category = ? , title = ?, date_news = ?, description = ?, wname = ?, modified = ?
							WHERE id = ?";
							
					$stmt = $connect->stmt_init();
					if($stmt->prepare($sql_query)) {	
						// Bind your variables to replace the ?s
						$stmt->bind_param('sssssss', 
							$id_category, 
							$title, 
							$date_news, 
							$description,
							$wname,
							$modified,
							$ID);
						// Execute query
						$stmt->execute();
						// store result 
						$update_result = $stmt->store_result();
						$stmt->close();
					}
				}
					
				// check update result
				if($update_result){
					$error['update_data'] = "<br><div class='alert alert-info'>News updated Successfully...</div>";
				}else{
					$error['update_data'] = "<br><div class='alert alert-danger'>Update Failed</div>";
				}
			}
			
		}
		
		// create array variable to store previous data
		$data = array();
			
		$sql_query = "SELECT * FROM news WHERE id = ?";
			
		$stmt = $connect->stmt_init();
		if($stmt->prepare($sql_query)) 
		{	
			// Bind your variables to replace the ?s
			$stmt->bind_param('s', $ID);
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result(
					$data['id'], 
					$data['id_category'], 
					$data['title'], 
					$data['date_news'], 
					$data['content_type'], 
					$data['description'],
					$data['news_photo'],
					$data['news_video'],
					$data['news_link'],
					$data['wname'],
					$data['modified'],
					$data['created']
					);
			$stmt->fetch();
			$stmt->close();
		}	
			
	?>

   <section class="content">
   
        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="manage-news.php">Manage News</a></li>
            <li class="active">Edit News</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                	<form id="form_validation" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="header">
                            <h2>EDIT NEWS</h2>
                                <?php echo isset($error['update_data']) ? $error['update_data'] : '';?>
                        </div>
                        <div class="body">

						<div class="row clearfix">
                            <div>
                                    <div class="form-group form-float col-sm-12">
                                        <div class="font-12">Title News</div>
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="title" id="title" value="<?php echo $data['title']; ?>" required>
                                        </div>
                                    </div>

									<div class="form-group col-sm-12">
                                        <div class="font-12">Category</div>
                                        <select class="form-control show-tick" name="id_category" id="id_category">
                                        <option value="0" >Category choice</option>
                                           <?php while($stmt_category->fetch())
                                           { 
												if ($category_data['id'] == $data['id_category']) { ?>
													<option value="<?php echo $category_data['id']; ?>" selected="<?php echo $category_data['id']; ?>" ><?php echo $category_data['name']; ?></option>
													<?php } else { ?>
													<option value="<?php echo $category_data['id']; ?>" ><?php echo $category_data['name']; ?></option>
                                                    <?php }
                                            } 
											?>
	                                    </select>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <div class="font-12">Date of the news</div>
                                        <div class="form-line">
                                            <input type="date" class="form-control" name="date_news" id="date_news" value="<?php echo $data['date_news']; ?>" required>
                                        </div>
                                    </div>

									<div class="form-group col-sm-12">
                                        <div class="font-12">Name Writter</div>
                                        <select class="form-control show-tick" name="wname" id="wname">
                                        <option value="0" >Name Writter choice</option>
                                           <?php while($stmt_admin->fetch())
                                           	{ 
												if ($admin_data['full_name'] == $data['wname']) { ?>
													<option value="<?php echo $admin_data['full_name']; ?>" selected="<?php echo $admin_data['full_name']; ?>" ><?php echo $admin_data['full_name']; ?></option>
													<?php } else { ?>
													<option value="<?php echo $admin_data['full_name']; ?>" ><?php echo $admin_data['full_name']; ?></option>
                                                    <?php }
                                        	} 
											?>
	                                    </select>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <div class="font-12">News contenent</div>
                                                <textarea class="form-control" name="description" id="description" class="form-control" cols="60" rows="10" required><?php echo $data['description']; ?></textarea>
                                                <script>                             
                                                    CKEDITOR.replace( 'description' );
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                    <div class="font-12 ex1">Image ( jpg / png )</div>
                                    <div class="form-group">
                                                <input type="file" name="news_photo" id="news_photo" class="dropify-image" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png gif" data-default-file="upload/news/<?php echo $data['news_photo']; ?>" data-show-remove="false"/>
                                                <div class="div-error"><?php echo isset($error['img']) ? $error['img'] : '';?></div>
                                        </div>
									</div>    
									
                                    <div class="col-sm-12">
                                    <button type="submit" name="btnEdit" class="btn bg-blue waves-effect pull-right ">Edit</button>
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