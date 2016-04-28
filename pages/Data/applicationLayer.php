<?php
	header('Accept: application/json');
	require_once __DIR__ . '/dataLayer.php';

	$action = $_POST["action"];

	switch($action)
	{
		case "LOGIN": login();
				break;

		case "ALLPRODUCTS": allProducts();
			break;

		case "LOADPRODUCTS": loadProducts();
			break;

		case "REGISTER": registerEmployee();
			  break;

		case "EDIT": editEmployee();
		   	break;

		case "ADD": addProduct();
		   	break;

		case "UPDATEP":updateProduct();
			break;

		case "EDITP": editProduct();
		   	break;

		case "DELETEP": deleteProduct();
		   	break;

		case "LOAD": loadQuotations();
			break;

		case "VIEW": viewQuotation();
			break;

		case "COOKIE": cookieCheck();
		   	break;

		case "QUOT": createQuotation();
			break;

		case "DAY": dailyQuotation();
			break;

		case "ENDSESSION": deleteSession();
		   	break;

		case "CURPRODUCT": currentProduct();
			break;

		case "RETPRODUCT": retrieveProduct();
			break;
		case "UNSETP": unsetProduct();
			break;
		case "CDETAIL": addDetail();
			break;
		case "UQUANTITY":updateQuantity();
			break;

		case "PROFILE": loadProfile();
			break;

		case "ALLEMPLOYEES": allEmployees();
			break;

		case "UNSETE":unsetEmployee();
			break;

		case "CUREMPLOYEE": currentEmployee();
			break;

		case "RETEMPLOYEE": retrieveEmployee();
			break;

		case "ADDE": addEmployee();
		   	break;

		case "UPDATEE":updateEmployee();
			break;

		case "LOADQUOTATIONS": allQuotations();
		   	break;

		case "LOADQUOTATIONSA": allQuotationsA();
		   	break;

		default:
			break;
	}

	function login(){
		$username = $_POST["id"];
		$password = $_POST["password"];
		$box = $_POST["box"];
		$result = loginAction($username);
		if ($result["statusTxt"] == "SUCCESS")
		{
			$dPassword = decryptPassword($result["password"]);
			if($dPassword!=$password){
				header('HTTP/1.1 406 Incorrect Password');
				die ('Incorrect Password');
			}
			$finalResponse = array("id" => $result["id"], "type" => $result["type"]);
			session_start();
			$_SESSION["id"] = $username;
			$_SESSION["type"] = $result["type"];
			if($box == "true")
				setcookie("id", $username, time() + 3600 * 24 * 30);
			echo json_encode($finalResponse);			
		}
		else
		{
			header('HTTP/1.1 406'+$result);
			die ($result);
		}
	}

	function currentProduct(){
		session_start();
		$_SESSION["productID"]=$_POST["productID"];
		exit;
	}

	function unsetProduct(){
		session_start();
		if (isset($_SESSION["productID"])){
			unset($_SESSION["productID"]);
		}
		exit;
	}

	function retrieveProduct(){
		session_start();
		if( isset( $_SESSION['productID']))
		{
			$result = loadProductAction($_SESSION['productID']);
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result);			
			}
			else
			{
				header('HTTP/1.1 406'+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 New Product');
			die('New Product');
		}
	}

	function addProduct(){
		session_start();
		if( isset( $_SESSION['id']))
		{
			$result = addProductAction($_POST["name"], $_POST["price"], $_POST["imageDir"], $_POST["stock"]);
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result["statusTxt"]);			
			}
			else
			{
				header('HTTP/1.1 406 '+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}

	function updateProduct(){
		session_start();
		if( isset( $_SESSION['id']))
		{
			$result = editProductAction($_POST["id"], $_POST["name"], $_POST["price"], $_POST["imageDir"], $_POST["stock"]);
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result["statusTxt"]);			
			}
			else
			{
				header('HTTP/1.1 406 '+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}

	function allProducts(){
		session_start();
		if( isset( $_SESSION['id']))
		{
			$result = loadProductsAction();
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result["array"]);			
			}
			else
			{
				header('HTTP/1.1 406'+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}

	function loadProducts(){
		session_start();
		if( isset( $_SESSION['id']))
		{
			$result = loadAvailableAction();
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result["array"]);			
			}
			else
			{
				header('HTTP/1.1 406'+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}


	//UPDATE
	function createQuotation(){
		session_start();
		if( isset( $_SESSION['id']))
		{
			$result = addQuotationAction($_SESSION['id'],$_POST["card"],$_POST["total"]);
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result);	
			}
			else
			{
				header('HTTP/1.1 406'+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}

	function addDetail(){
		session_start();
		if( isset( $_SESSION['id']))
		{
			$result = addDetailAction($_POST['idQuotation'],$_POST['idProduct'],$_POST["quantity"],$_POST["price"],$_POST["total"]);
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result);	
			}
			else
			{
				header('HTTP/1.1 406 '+$result["statusTxt"]);
				die ("NOOO");
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}

	function updateQuantity(){
		session_start();
		if( isset( $_SESSION['id']))
		{
			$result = uQuantityAction($_POST['id'],$_POST['quantity']);
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result);	
			}
			else
			{
				header('HTTP/1.1 406'+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}

	# Action to encrypt the password of the user
	function encryptPassword()
	{
		$userPassword = $_POST['password'];
	    $key = pack('H*', "bcb04b7e103a05afe34763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
	    $key_size =  strlen($key);
	    
	    $plaintext = $userPassword;

	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    
	    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
	    $ciphertext = $iv . $ciphertext;
	    
	    $userPassword = base64_encode($ciphertext);

	    return $userPassword;
	}

	#Action to decrypt the password of the user
	function decryptPassword($password)
	{
		$key = pack('H*', "bcb04b7e103a05afe34763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
	    
	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    	
	    $ciphertext_dec = base64_decode($password);
	    $iv_dec = substr($ciphertext_dec, 0, $iv_size);
	    $ciphertext_dec = substr($ciphertext_dec, $iv_size);

	    $password = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
	   	
	   	
	   	$count = 0;
	   	$length = strlen($password);

	    for ($i = $length - 1; $i >= 0; $i --)
	    {
	    	if (ord($password{$i}) === 0)
	    	{
	    		$count ++;
	    	}
	    }

	    $password = substr($password, 0,  $length - $count); 

	    return $password;
	}
	function cookieCheck(){
		if(isset($_COOKIE["id"]))
		{
			echo json_encode(array("cookieValue" => $_COOKIE["id"]));
		}
		else
		{
			header('HTTP/1.1 406 Cookie has not been set yet');
			die ('Cookie not set');
		}
	}

	function loadProfile(){
		session_start();
		if(isset( $_SESSION['id']))
		{
			$idA = $_SESSION['id'];
			$result = profileAction($idA);
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result);			
			}
			else
			{
				header('HTTP/1.1 406'+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}

	function allEmployees(){
		session_start();
		if( isset( $_SESSION['id']))
		{
			$result = loadEmployeesAction();
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result["array"]);			
			}
			else
			{
				header('HTTP/1.1 406 '+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}

	function unsetEmployee(){
		session_start();
		if (isset($_SESSION["employeeID"])){
			unset($_SESSION["employeeID"]);
		}
		exit;
	}

	function currentEmployee(){
		session_start();
		$_SESSION["employeeID"]=$_POST["employeeID"];
		exit;
	}

	function retrieveEmployee(){
		session_start();
		if( isset( $_SESSION['employeeID']))
		{
			$result = loadEmployeeAction($_SESSION['employeeID']);
			$result["password"] = decryptPassword($result["password"]);
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result);			
			}
			else
			{
				header('HTTP/1.1 406 '+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 New Product');
			die('New Product');
		}
	}

	function addEmployee(){
		session_start();
		if( isset( $_SESSION['id']))
		{
			$_POST["password"] = encryptPassword();
			$result = addEmployeeAction($_POST["id"], $_POST["password"], $_POST["name"], $_POST["lastname"], $_POST["birthdate"],
				$_POST["telephone"], $_POST["address"]);
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result["statusTxt"]);			
			}
			else
			{
				header('HTTP/1.1 406 '+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}

	function updateEmployee(){
		session_start();
		if( isset( $_SESSION['id']))
		{
			$_POST["password"] = encryptPassword();
			$result = updateEmployAction($_POST["id"], $_POST["password"], $_POST["name"], $_POST["lastname"], $_POST["birthdate"],
				$_POST["telephone"], $_POST["address"]);
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result["statusTxt"]);			
			}
			else
			{
				header('HTTP/1.1 406 '+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}

	function deleteSession()
	{
		session_start();
		if( isset( $_SESSION["id"]))
		{
			unset($_SESSION["id"]);
			unset($_SESSION["type"]);
			session_destroy();
			echo json_encode(array('success' => 'Sesión murió.'));
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die(json_encode(array('message' => 'Sesión expiró.')));
		}
	}

	function allQuotations(){
		session_start();
		if( isset( $_SESSION['id']))
		{
			$result = loadAllQuotations();
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result["array"]);			
			}
			else
			{
				header('HTTP/1.1 406'+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}

		function allQuotationsA(){
		session_start();
		if( isset( $_SESSION['id']))
		{
			$result = loadAllQuotationsA();
			if ($result["statusTxt"] == "SUCCESS")
			{
				echo json_encode($result["array"]);			
			}
			else
			{
				header('HTTP/1.1 406'+$result["statusTxt"]);
				die ($result["statusTxt"]);
			}
		}
		else
		{
			header('HTTP/1.1 406 Session has expired, you will be redirected to the login');
			die('Session expired');
		}
	}


	
?>