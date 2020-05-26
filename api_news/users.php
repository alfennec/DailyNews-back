<?php
	// Connect to database
	include("db_connect.php");
	$request_method = $_SERVER["REQUEST_METHOD"];

	function getProducts()
	{
		global $conn;
		$query = "SELECT * FROM user";
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
		$query = "SELECT * FROM user";
		if($id != 0)
		{
			$query .= " WHERE id=".$id." LIMIT 1";
		}
		$response = array();
		$result = mysqli_query($conn, $query);
		while($row = mysqli_fetch_array($result))
		{
			$response[] = $row;
		}
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	
	function AddProduct()
	{
		global $conn;
		$fname = $_POST["fname"];
		$lname = $_POST["lname"];
		$email = $_POST["email"];
		$password = $_POST["password"];
		$status = $_POST["status"];
		$created = date('Y-m-d H:i:s');
		$modified = date('Y-m-d H:i:s');

		$query = "SELECT * FROM user WHERE name='".$name."' AND email='".$email."'";

		$response = array();
		$result = mysqli_query($conn, $query);
		$rowcount = mysqli_num_rows($result);

		if($rowcount == 0)
		{
			$query="INSERT INTO user(fname, lname, email, password, status, created, modified) VALUES ('".$fname."', '".$lname."', '".$email."', '".$password."', '".$status."', '".$created."', '".$modified."')";

			if(mysqli_query($conn, $query))
			{
				$response=array(
					'status' => 1,
					'status_message' =>'User added with success.'
				);
			}
			else
			{
				$response=array(
					'status' => 0,
					'status_message' =>'ERREUR!.'. mysqli_error($conn)
				);
			}
		}else
		{
			$response=array(
				'status' => 0,
				'status_message' =>'ERREUR!.'. mysqli_error($conn)
			);
		}	
	
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	
	function updateUser($id)
	{
		global $conn;
		$query = "SELECT * FROM user WHERE id=".$id;

		$response = array();
		$result = mysqli_query($conn, $query);
		$rowcount = mysqli_num_rows($result);

		if($rowcount == 1)
		{
			$_PUT = array();
			parse_str(file_get_contents('php://input'), $_PUT);
			$fname = $_PUT["fname"];
			$lname = $_PUT["lname"];
			$email = $_PUT["email"];
			$password = $_PUT["password"];
			$status = $_PUT["status"];

			$created = 'NULL';
			$modified = date('Y-m-d H:i:s');

			$query="UPDATE user SET 
			fname='".$fname."', 
			lname='".$lname."', 
			email='".$email."',
			password='".$password."',  
			status='".$status."', 
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
		}else
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
		$query = "DELETE FROM user WHERE id=".$id;
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

	function login($email, $password)
	{
		global $conn;
		$query = "SELECT * FROM user WHERE email= '".$email."' AND password='".$password."'";

		$response = array();
		$result = mysqli_query($conn, $query);
		$rowcount = mysqli_num_rows($result);

		if($rowcount == 1)
		{
			while($row = mysqli_fetch_array($result))
			{
				$response[] = $row;
			}

			$response=array
			(
				'status' => 1,
				'status_message' =>'success ',
				'result' => $response
			);
			header('Content-Type: application/json');
			echo json_encode($response, JSON_PRETTY_PRINT);
		}else
		{
			$response=array
			(
				'status' => 0,
				'status_message' =>'Mot de pass inccorect . '. mysqli_error($conn),
				'result' => "null"
			);
			header('Content-Type: application/json');
			echo json_encode($response, JSON_PRETTY_PRINT);
		}	
	}
	
	switch($request_method)
	{
		case 'GET':
			// Retrive Products
			if(!empty($_GET["email"]) && !empty($_GET["password"]))
			{
				$email=$_GET["email"];
				$password=$_GET["password"];

				login($email, $password);

			}else if(!empty($_GET["id"]))
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
			updateUser($id);
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
?>