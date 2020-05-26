<?php 

	if (isset($_GET['id'])) {
		$ID = $_GET['id'];
	} else {
		$ID = "";
	}
		
	// get image file from menu table
	$sql_query = "SELECT news_photo FROM news WHERE id = ?";
			
	$stmt = $connect->stmt_init();
	if ($stmt->prepare($sql_query)) 
	{	
		// Bind your variables to replace the ?s
		$stmt->bind_param('s', $ID);
		// Execute query
		$stmt->execute();
		// store result 
		$stmt->store_result();
		$stmt->bind_result($news_photo);
		$stmt->fetch();
		$stmt->close();
	}
			
	// delete image file from directory
	$delete = unlink('upload/news/'."$news_photo");
			
	// delete data from menu table
	$sql_query = "DELETE FROM news WHERE id = ?";
			
	$stmt = $connect->stmt_init();
	if ($stmt->prepare($sql_query)) 
	{	
		// Bind your variables to replace the ?s
		$stmt->bind_param('s', $ID);
		// Execute query
		$stmt->execute();
		// store result 
		$delete_result = $stmt->store_result();
		$stmt->close();
	}
				
	// if delete data success back to reservation page
	if($delete_result)
	{
		header("location: manage-news.php");
	}

?>