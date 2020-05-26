<?php
	// Connect to database
	include("db_connect.php");
	$request_method = $_SERVER["REQUEST_METHOD"];

	function getProducts()
	{
		global $conn;
		$query = "SELECT * FROM news ORDER BY id DESC";
		$response = array();
		$result = mysqli_query($conn, $query);

		$i=0;
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;

			$response[$i]["id"] = $row["id"];
			$response[$i]["id_category"] = $row["id_category"];
			$response[$i]["title"] = $row["title"];
			$response[$i]["date_news"] = $row["date_news"];
			$response[$i]["content_type"] = $row["content_type"];
			$response[$i]["description"] = $row["description"];
			$response[$i]["news_photo"] = $row["news_photo"];
			$response[$i]["news_video"] = $row["news_video"];
			$response[$i]["news_link"] = $row["news_link"];
			$response[$i]["nbr_comments"] = getCountComment($row["id"]);
			$response[$i]["wname"] = $row["wname"];
			$response[$i]["created"] = $row["created"];
			$response[$i]["modified"] = $row["modified"];

			$i++;
		}
		
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	
	function getProduct($id=0)
	{
		global $conn;
		$query = "SELECT * FROM news";
		if($id != 0)
		{
			$query .= " WHERE id=".$id." LIMIT 1";
		}
		$response = array();
		$result = mysqli_query($conn, $query);

		$i=0;
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;

			$response[$i]["id"] = $row["id"];
			$response[$i]["id_category"] = $row["id_category"];
			$response[$i]["title"] = $row["title"];
			$response[$i]["date_news"] = $row["date_news"];
			$response[$i]["content_type"] = $row["content_type"];
			$response[$i]["description"] = $row["description"];
			$response[$i]["news_photo"] = $row["news_photo"];
			$response[$i]["news_video"] = $row["news_video"];
			$response[$i]["news_link"] = $row["news_link"];
			$response[$i]["nbr_comments"] = getCountComment($row["id"]);
			$response[$i]["wname"] = $row["wname"];
			$response[$i]["created"] = $row["created"];
			$response[$i]["modified"] = $row["modified"];

			$i++;
		}

		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	
	function AddProduct()
	{
		global $conn;
		$id_category = $_POST["id_category"];
		$title = $_POST["title"];
		$date_news = $_POST["date_news"];
		$content_type = $_POST["content_type"];
		$description = $_POST["description"];
		$news_photo = $_POST["news_photo"];
		$news_video = $_POST["news_video"];
		$news_link = $_POST["news_link"];
		$wname = $_POST["wname"];
		$created = date('Y-m-d H:i:s');
		$modified = date('Y-m-d H:i:s');

		$query="INSERT INTO news(id_category, title, date_news, content_type, description, news_photo, news_video, news_link, wname, created, modified) VALUES('".$id_category."', '".$title."', '".$date_news."', '".$content_type."', '".$description."', '".$news_photo."', '".$news_video."', '".$news_link."', '".$wname."', '".$created."', '".$modified."')";

		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Category added with success.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'ERREUR!.'. mysqli_error($conn)
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	function updateProduct($id)
	{
		global $conn;
		$_PUT = array();
		parse_str(file_get_contents('php://input'), $_PUT);
		$id_category = $_PUT["id_category"];
		$title = $_PUT["title"];
		$date_news = $_PUT["date_news"];
		$content_type = $_PUT["content_type"];
		$description = $_PUT["description"];
		$news_photo = $_PUT["news_photo"];
		$news_video = $_PUT["news_video"];
		$news_link = $_PUT["news_link"];
		$wname = $_PUT["wname"];

		$created = 'NULL';
		$modified = date('Y-m-d H:i:s');

		$query="UPDATE news SET 
		id_category='".$id_category."', 
		title='".$title."',
		date_news='".$date_news."',  
		content_type='".$content_type."',  
		description='".$description."',  
		news_photo='".$news_photo."',  
		news_video='".$news_video."',  
		news_link='".$news_link."',   
		wname='".$wname."', 
		modified='".$modified."'
		WHERE id=".$id;
		
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Category updated with success.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'Echec de la mise a jour de produit. '. mysqli_error($conn)
			);
			
		}
		
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	function deleteProduct($id)
	{
		global $conn;
		$query = "DELETE FROM news WHERE id=".$id;
		if(mysqli_query($conn, $query))
		{
			$response=array(
				'status' => 1,
				'status_message' =>'Category deleted with success.'
			);
		}
		else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'La suppression du produit a echoue. '. mysqli_error($conn)
			);
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	switch($request_method)
	{
		
		case 'GET':
			// Retrive Products
			if(!empty($_GET["id"]))
			{
				$id=intval($_GET["id"]);
				getProduct($id);
			}
			else
			{
				getProducts();
			}
			break;

		case 'POST':
			// Ajouter un produit
			AddProduct();
			break;
			
		case 'PUT':
			// Modifier un produit
			$id = intval($_GET["id"]);
			updateProduct($id);
			break;
			
		case 'DELETE':
			// Supprimer un produit
			$id = intval($_GET["id"]);
			deleteProduct($id);
			break;

		default:
			// Invalid Request Method
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}

	/*********** function apart  */

	function getCountComment($idNews)
	{
		global $conn;
		$query = "SELECT count(*) FROM comments WHERE id_news=".$idNews;
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		
		return $response[0]["count(*)"];
	}
?>