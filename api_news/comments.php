<?php
	// Connect to database
	include("db_connect.php");
	$request_method = $_SERVER["REQUEST_METHOD"];

	function getProducts()
	{
		global $conn;
		$query = "SELECT * FROM comments";
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	
	function getProduct($id=0)
	{
		global $conn;
		$query = "SELECT * FROM comments";
		if($id != 0)
		{
			$query .= " WHERE id_news=".$id." ORDER BY id DESC ";
		}
		$response = array();
		$result = mysqli_query($conn, $query);
		$i=0;
		while($row = mysqli_fetch_array($result))
		{
			$response[$i]["id"] = $row["id"];
			$response[$i]["id_user"] = getUserNameById($row["id_user"]);
			$response[$i]["id_news"] = $row["id_news"];
			$response[$i]["message"] = $row["message"];
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
		$id_user = $_POST["id_user"];
		$id_news = $_POST["id_news"];
		$message = $_POST["message"];
		$created = date('Y-m-d H:i:s');
		$modified = date('Y-m-d H:i:s');

		$query="INSERT INTO comments(id_user, id_news, message, created, modified) VALUES('".$id_user."', '".$id_news."', '".$message."', '".$created."', '".$modified."')";

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
		$id_user = $_PUT["id_user"];
		$id_news = $_PUT["id_news"];
		$message = $_PUT["message"];

		$created = 'NULL';
		$modified = date('Y-m-d H:i:s');

		$query="UPDATE comments SET 
		id_user='".$id_user."', 
		id_news='".$id_news."',
		message='".$message."',  
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
		$query = "DELETE FROM comments WHERE id=".$id;
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

	function getUserNameById($idUser)
	{
		global $conn;
		$query = "SELECT * FROM user WHERE id=".$idUser;
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		
		return $response[0]["fname"];
	}
?>