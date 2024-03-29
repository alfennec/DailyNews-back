<?php
	include 'functions.php';
	include 'fcm.php';
?>

	<?php 
		// create object of functions class
		$function = new functions;
		
		// create array variable to store data from database
		$data = array();
		
		if(isset($_GET['keyword'])) {	
			// check value of keyword variable
			$keyword = $function->sanitize($_GET['keyword']);
			$bind_keyword = "%".$keyword."%";
		} else {
			$keyword = "";
			$bind_keyword = $keyword;
		}
			
        if (empty($keyword)) 
        {
			$sql_query = "SELECT m.id, m.title, m.news_photo, m.wname, c.name FROM news m, category c
					WHERE m.id_category = c.id  
					ORDER BY m.id DESC";
		} else {
			$sql_query = "SELECT m.id, m.title, m.news_photo, m.wname, c.name FROM news m, category c
					WHERE m.id_category = c.id AND m.title LIKE ? 
					ORDER BY m.id DESC";
		}
		
		
		$stmt = $connect->stmt_init();
		if ($stmt->prepare($sql_query)) {	
			// Bind your variables to replace the ?s
			if (!empty($keyword)) {
				$stmt->bind_param('s', $bind_keyword);
			}
			// Execute query
			$stmt->execute();
			// store result 
			$stmt->store_result();
			$stmt->bind_result( 
					$data['m.id'],
					$data['m.title'],
					$data['m.news_photo'],
					$data['m.wname'],
					$data['c.name']
					);
			// get total records
			$total_records = $stmt->num_rows;
		}
			
		// check page parameter
		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 1;
		}
						
		// number of data that will be display per page		
		$offset = 10;
						
		//lets calculate the LIMIT for SQL, and save it $from
		if ($page) {
			$from 	= ($page * $offset) - $offset;
		} else {
			//if nothing was given in page request, lets load the first page
			$from = 0;	
		}	
		
		if (empty($keyword)) {
			$sql_query = "SELECT m.id, m.title, m.news_photo, m.wname, c.name FROM news m, category c
					WHERE m.id_category = c.id  
					ORDER BY m.id DESC LIMIT ?, ?";
		} else {
			$sql_query = "SELECT m.id, m.title, m.news_photo, m.wname, c.name FROM news m, category c
					WHERE m.id_category = c.id   AND m.title LIKE ? 
					ORDER BY m.id DESC LIMIT ?, ?";
		}
		
		$stmt_paging = $connect->stmt_init();
		if ($stmt_paging ->prepare($sql_query)) {
			// Bind your variables to replace the ?s
			if (empty($keyword)) {
				$stmt_paging ->bind_param('ss', $from, $offset);
			} else {
				$stmt_paging ->bind_param('sss', $bind_keyword, $from, $offset);
			}
			// Execute query
			$stmt_paging ->execute();
			// store result 
			$stmt_paging ->store_result();
			$stmt_paging->bind_result(
                $data['m.id'],
                $data['m.title'],
                $data['m.news_photo'],
                $data['m.wname'],
                $data['c.name']
			);
			// for paging purpose
			$total_records_paging = $total_records; 
		}

		// if no data on database show "No Reservation is Available"
		if ($total_records_paging == 0) {
	
	?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active">Manage News</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Manage News</h2>
                            <div class="header-dropdown m-r--5">
                                <a href="add-news.php"><button type="button" class="btn bg-blue waves-effect">ADD NEWS</button></a>
                            </div>
                        </div>

                        <div class="body table-responsive">
	                        
	                        <form method="get">
	                        	<div class="col-sm-10">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control" name="keyword" placeholder="Search by title...">
										</div>
									</div>
								</div>
								<div class="col-sm-2">
					                <button type="submit" name="btnSearch" class="btn bg-blue btn-circle waves-effect waves-circle waves-float"><i class="material-icons">search</i></button>
								</div>
							</form>
										
							<table class='table table-hover table-striped'>
								<thead>
									<tr>
										<th>News Title</th>
										<th>News Image</th>
										<th>Writter Name</th>
										<th>Category</th>
										<th>Action</th>
									</tr>
								</thead>

								
							</table>

							<div class="col-sm-10">Wopps! No data found with the keyword you entered.</div>

						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

	<?php 
		// otherwise, show data
		} else {
			$row_number = $from + 1;
	?>

    <section class="content">

        <ol class="breadcrumb">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li class="active">Manage News</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Manage News</h2>
                            <div class="header-dropdown m-r--5">
                                <a href="add-news.php"><button type="button" class="btn bg-blue waves-effect">ADD NEWS</button></a>
                            </div>
                            <br>
                        </div>

                        <div class="body table-responsive">
	                        
	                        <form method="get">
	                        	<div class="col-sm-10">
									<div class="form-group form-float">
										<div class="form-line">
											<input type="text" class="form-control" name="keyword" placeholder="Search by title...">
										</div>
									</div>
								</div>
								<div class="col-sm-2">
					                <button type="submit" name="btnSearch" class="btn bg-blue btn-circle waves-effect waves-circle waves-float"><i class="material-icons">search</i></button>
								</div>
							</form>
										
							<table class='table table-hover table-striped'>
								<thead>
									<tr>
										<th>News Title</th>
										<th>News Image</th>
										<th>Writter Name</th>
										<th>Category</th>
										<th>Action</th>
									</tr>
								</thead>

								<?php 
									while ($stmt_paging->fetch()) { ?>
										<tr>
											<td><?php echo $data['m.title'];?></td>
							            	<td><img src="upload/news/<?php echo $data['m.news_photo'];?>" height="48px" width="48px"/></td>
											<td><?php echo $data['m.wname'];?></td>
											<td><?php echo $data['c.name'];?></td>
											<td>
												<center>
									            <a href="edit-news.php?id=<?php echo $data['m.id'];?>">
									                <i class="material-icons">mode_edit</i>
									            </a>
									                        
									            <a href="delete-news.php?id=<?php echo $data['m.id'];?>" onclick="return confirm('Are you sure want to delete this News?')" >
									                <i class="material-icons">delete</i>
												</a>
												</center>
									        </td>
										</tr>
								<?php 
									}
								?>
							</table>

							<h4><?php $function->doPages($offset, 'manage-news.php', '', $total_records, $keyword); ?></h4>
							<?php 
								}
							?>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>