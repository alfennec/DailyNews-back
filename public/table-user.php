<?php
    include 'fcm.php';
    include 'functions.php';
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
            
        if (empty($keyword)) {
            $sql_query = "SELECT * FROM user ORDER BY id DESC";
        } else {
            $sql_query = "SELECT * FROM user WHERE fname LIKE ? OR lname LIKE ? ORDER BY id DESC";
        }
        
        
        $stmt = $connect->stmt_init();
        if ($stmt->prepare($sql_query)) {   
            // Bind your variables to replace the ?s
            if (!empty($keyword)) {
                $stmt->bind_param('ss', $bind_keyword,$bind_keyword);
            }
            // Execute query
            $stmt->execute();
            // store result 
            $stmt->store_result();
            $stmt->bind_result( 
                    $data['id'],
					$data['fname'],
					$data['lname'],
					$data['email'],
					$data['password'],
					$data['status'],
					$data['created'],
					$data['modified']
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
            $from   = ($page * $offset) - $offset;
        } else {
            //if nothing was given in page request, lets load the first page
            $from = 0;  
        }   
        
        if (empty($keyword)) {
            $sql_query = "SELECT * FROM user ORDER BY id DESC LIMIT ?, ?";
        } else {
            $sql_query = "SELECT * FROM user WHERE fname LIKE ? OR lname LIKE ? ORDER BY id DESC LIMIT ?, ?";
        }
        
        $stmt_paging = $connect->stmt_init();
        if ($stmt_paging ->prepare($sql_query)) {
            // Bind your variables to replace the ?s
            if (empty($keyword)) {
                $stmt_paging ->bind_param('ss', $from, $offset);
            } else {
                $stmt_paging ->bind_param('ssss', $bind_keyword, $bind_keyword, $from, $offset);
            }
            // Execute query
            $stmt_paging ->execute();
            // store result 
            $stmt_paging ->store_result();
            $stmt_paging->bind_result(
                	$data['id'],
					$data['fname'],
					$data['lname'],
					$data['email'],
					$data['password'],
					$data['status'],
					$data['created'],
					$data['modified']
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
            <li class="active">Manage Members</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>MANAGE MEMBERS</h2>
                            <div class="header-dropdown m-r--5">
                                <a href="add-member.php"><button type="button" class="btn bg-blue waves-effect">ADD NEW MEMBER</button></a>
                            </div>
                        </div>

                        <div class="body table-responsive">
                            
                            <form method="get">
                                <div class="col-sm-10">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="keyword" placeholder="Search by first or last name...">
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
										<th>First Name</th>
										<th>Last Name</th>
										<th>Email</th>
										<th>status</th>
                                        <th width="15%">Action</th>
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
            <li class="active">Manage Members</a></li>
        </ol>

       <div class="container-fluid">

            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>MANAGE MEMBERS</h2>
                            <div class="header-dropdown m-r--5">
                                <a href="add-member.php"><button type="button" class="btn bg-blue waves-effect">ADD NEW MEMBERS</button></a>
                            </div>
                        </div>

                        <div class="body table-responsive">
                            
                            <form method="get">
                                <div class="col-sm-10">
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" name="keyword" placeholder="Search by first or last name...">
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
										<th>First Name</th>
										<th>Last Name</th>
										<th>Email</th>
										<th>status</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>

                                <?php 
                                    while ($stmt_paging->fetch()) { ?>
                                        <tr>
                                            <td><?php echo $data['fname'];?></td>
                                            <td><?php echo $data['lname'];?></td>
											<td><?php echo $data['email'];?></td>
											<td><span class="label bg-green"><?php echo $data['status'];?></span></td>
                                            <td>
                                                <a href="edit-member.php?id=<?php echo $data['id']; ?>">
                                                    <i class="material-icons">mode_edit</i>
                                                </a>
                                            
                                                <a href="members.php?id=<?php echo $data['id'];?>" onclick="return confirm('Are you sure want to delete this user?')" >
                                                    <i class="material-icons">delete</i>
                                                </a>
                                            </td>
                                        </tr>
                                <?php 
                                    }
                                ?>
                            </table>

                            <h4><?php $function->doPages($offset, 'manage-user.php', '', $total_records, $keyword); ?></h4>
                            <?php 
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>