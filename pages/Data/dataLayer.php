<?php
	
	function connect()
	{
		$servernameDB = "localhost";
		$usernameDB = "root";
		$passwordDB = "root";
		$nameDB = "EPOSS";

		$connection = new mysqli($servernameDB, $usernameDB, $passwordDB, $nameDB);

		if($connection -> connect_error)
		{
			header('HTTP/1.1 500 Bad connection to Database');
			die("Something went wrong with the Database Connection");
		}
		else
		{
			return $connection;
		}
	}
	
	function loginAction($id)
	{
		$conn = connect();

		if ($conn != null)
		{
			$sql = "SELECT id, password, type FROM EMPLOYEE WHERE id = '$id'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0)
			{
				
				while($row = $result->fetch_assoc()) 
		    	{
		    		if ($row['type']=='D'){
						$response = array("statusTxt" => "ID Disabled!");
						return $response;
					}
			    	$response = array("statusTxt" => "SUCCESS", 'id' => $row['id'], 'password' => $row['password'], "type" => $row['type']); 
				}
		    	return $response;
			}
			else{
				$response = array("statusText" => "ID Not Found", "data" =>$row); 
			return $response;
			}
			$conn->close();
		}
		else{
			$response = array("statusTxt" => "Could Not Find Database", "data" =>$row); 
			return $response;
		}
	}

	function registerEmployeeAction($id,$password,$name,$lastname,$birthdate,$phone,$address)
	{
		$conn = connect();

		if ($conn->connect_error) 
		{
		    header('HTTP/1.1 500 Bad connection to Database');
		    return (array("statusTxt" => "Database Not Found", "code" => 1337));
		}
		else
		{
			$sql = "SELECT id FROM EMPLOYEE WHERE id = '$id'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0)
			{
				header('HTTP/1.1 409 Conflict, Username or mail already in use please select another one');
			    return (array("statusTxt" => "Employee already exists", "code" => 1337));
			}
			else
			{
				$sql = "INSERT INTO EMPLOYEE (id, password, type, name, lastname, birthdate, phone, address) VALUES ('$id','$password','E','$name','$lastname','$birthdate','$phone','$address')";
		    	
		    	if (mysqli_query($conn, $sql)) 
		    	{
				    return (array("statusTxt" => "SUCCESS"));
				} 
				else 
				{
					return (array("statusTxt" => "FAIL"));
				}
			}
		} 
	}

	function editEmployeeAction($id,$name,$lastname,$birthdate,$phone,$address){
		$conn = connect();

		if ($conn->connect_error) {
			return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "UPDATE EMPLOYEE SET name = '$name', lastname = '$lastname', birthdate = '$birthdate', phone = '$phone', address = '$address' WHERE id = '$id'"; 
		if (mysqli_query($conn, $sql)) 
    	{
    		return (array("statusTxt" => "SUCCESS"));
		}
		else
		{
			return(array("statusTxt" => "Employee was not updated"));
		}
	}

	function retrieveEmployeeAction($id){
		$conn = connect();
		if ($conn != null)
		{
			$sql = "SELECT * FROM EMPLOYEE WHERE id = '$id'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc()) 
		    	{
			    	$response = array("statusTxt" => "SUCCESS", 'type' => $row['type']); 
				}
		    	return $response;
			}
			else{
				$response = array("statusTxt" => "ID Not Found", "data" =>$row); 
				return $response;
			}
			$conn->close();
		}
		else{
			$response = array("statusTxt" => "Could Not Find Database", "data" =>$row); 
			return $response;
		}
	}

	function retrievePasswordAction($id){
		$conn = connect();
		if ($conn != null)
		{
			$sql = "SELECT password FROM EMPLOYEE WHERE id = '$id'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc()) 
		    	{
			    	$response = array("statusTxt" => "SUCCESS", 'password' => $row['password']); 
				}
		    	return $response;
			}
			else{
				$response = array("statusTxt" => "ID Not Found", "data" =>$row); 
				return $response;
			}
			$conn->close();
		}
		else{
			$response = array("statusTxt" => "Could Not Find Database", "data" =>$row); 
			return $response;
		}
	}

	function changePasswordAction($id,$password){
		$conn = connect();

		if ($conn->connect_error) {
			return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "UPDATE EMPLOYEE SET password = '$password' WHERE id = '$id'"; 
		if (mysqli_query($conn, $sql)) 
    	{
    		return (array("statusTxt" => "SUCCESS"));
		}
		else
		{
			return(array("statusTxt" => "Password was not updated"));
		}
	}

	function addProductAction($name, $price, $imageDir, $stock){
		$conn = connect();

		if ($conn->connect_error) 
		{
		    header('HTTP/1.1 500 Bad connection to Database');
		    return (array("statusTxt" => "Database Not Found", "code" => 1337));
		}
		else
		{
			$sql = "INSERT INTO PRODUCT (name, price, imageDir, stock) VALUES ('$name',$price, '$imageDir', $stock)";
		    	
	    	if (mysqli_query($conn, $sql)) 
	    	{
			    return (array("statusTxt" => "SUCCESS"));
			} 
			else 
			{
				return (array("statusTxt" => "FAIL"));
			}
		} 
	}

	function loadProductsAction(){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT * FROM PRODUCT ORDER BY name";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			$response = array();
			while($row = $result->fetch_assoc()) {
				$product = array('id'=>$row['id'],'name' => $row['name'], 'price' => $row['price'],
							'imageDir' => $row['imageDir'], 'stock' => $row['stock']);
				array_push($response, $product);
			}
			$stsTxt = array("statusTxt" => "SUCCESS","array" => $response);

			return ($stsTxt);
		}
		else {
			return(array("statusTxt" => "Products Not Found"));
		}
	}

	function loadAvailableAction(){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT * FROM PRODUCT WHERE stock >= 0 ORDER BY name";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			$response = array();
			while($row = $result->fetch_assoc()) {
				$product = array('id'=>$row['id'],'name' => $row['name'], 'price' => $row['price'],
							'imageDir' => $row['imageDir'], 'stock' => $row['stock']);
				array_push($response, $product);
			}
			$stsTxt = array("statusTxt" => "SUCCESS","array" => $response);

			return ($stsTxt);
		}
		else {
			return(array("statusTxt" => "Products Not Found"));
		}
	}

	function loadNotAvailableAction(){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT * FROM PRODUCT WHERE stock = 0 ORDER BY name";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			$response = array();
			while($row = $result->fetch_assoc()) {
				$product = array('name' => $row['name'], 'price' => $row['price'],
							'imageDir' => $row['imageDir'], 'stock' => $row['stock']);
				array_push($response, $product);
			}
			$stsTxt = array("statusTxt" => "SUCCESS","array" => $response);

			return ($stsTxt);
		}
		else {
			return(array("statusTxt" => "Products Not Found"));
		}
	}

	function loadProductAction($id){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT * FROM PRODUCT WHERE id = '$id'";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) 
		    {
			    $response = array("statusTxt" => "SUCCESS", 'id' => $row['id'], 'name' => $row['name'], "price" => $row['price'], "imageDir" => $row['imageDir'], "stock" => $row['stock']); 
			}
		    return $response;
		}
		else {
			return(array("statusTxt" => "Product Not Found"));
		}
	}

	function editProductAction($id, $name, $price, $imageDir, $stock){
		$conn = connect();

		if ($conn->connect_error) {
			return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "UPDATE PRODUCT SET name = '$name', price = $price, imageDir = '$imageDir', stock = $stock WHERE id = '$id'"; 
		if (mysqli_query($conn, $sql)) 
    	{
    		return (array("statusTxt" => "SUCCESS"));
		}
		else
		{
			return(array("statusTxt" => "Employee was not updated"));
		}
	}

	function uQuantityAction($id,$quantity){
		$conn = connect();

		if ($conn->connect_error) {
			return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "UPDATE PRODUCT SET stock = '$quantity' WHERE id = '$id'"; 
		if (mysqli_query($conn, $sql)) 
    	{
    		return (array("statusTxt" => "SUCCESS"));
		}
		else
		{
			return(array("statusTxt" => "Employee was not updated"));
		}
	}

	function addDetailAction($idQuotation, $idProduct, $quantity, $price, $total){
		$conn = connect();

		if ($conn->connect_error) 
		{
		    header('HTTP/1.1 500 Bad connection to Database');
		    return (array("statusTxt" => "Database Not Found", "code" => 1337));
		}
		else
		{
			$sql = "INSERT INTO DETAIL (idQuotation, idProduct, quantity, price, total) VALUES ($idQuotation, $idProduct, $quantity, $price, $total)";
		    	
	    	if (mysqli_query($conn, $sql)) 
	    	{
			    return (array("statusTxt" => "SUCCESS"));
			} 
			else 
			{
				return (array("statusTxt" => "FAIL"));
			}
		} 
	}

	function detailProductAction($idProduct){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT d.idQuotation as idQuotation, q.date as qDate, d.quantity as quantity, d.price as price, d.total as total, FROM DETAIL d, QUOTATION q WHERE d.idProduct = '$idProduct' AND d.idQuotation = q.id";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			$response = array();
			while($row = $result->fetch_assoc()) {
				$product = array('idQuotation' => $row['idQuotation'], 'qDate' => $row['qDate'], 'quantity' => $row['quantity'],
							'price' => $row['price'], 'total' => $row['total']);
				array_push($response, $product);
			}
			$stsTxt = array("statusTxt" => "SUCCESS","array" => $response);

			return ($stsTxt);
		}
		else {
			return(array("statusTxt" => "Details Not Found"));
		}
	}

	function detailQuotationAction($idQuotation){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT p.idProduct as idProduct,p.name as name, d.quantity as quantity, d.price as price, d.total as total, FROM DETAIL d, PRODUCT p WHERE d.idQuotation = '$idQuotation' AND d.idProduct = p.id";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			$response = array();
			while($row = $result->fetch_assoc()) {
				$product = array('idProduct' => $row['idProduct'],'name' => $row['name'], 'quantity' => $row['quantity'],
							'price' => $row['price'], 'total' => $row['total']);
				array_push($response, $product);
			}
			$stsTxt = array("statusTxt" => "SUCCESS","array" => $response);

			return ($stsTxt);
		}
		else {
			return(array("statusTxt" => "Details Not Found"));
		}
	}

	function dailyQuotationAdminAction(){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT q.id as id, e.name as name, e.lastname as lastname, q.card as card, q.total as total FROM QUOTATION q, EMPLOYEE e WHERE q.idEmployee = e.id AND q.date = CURDATE()";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			$response = array();
			while($row = $result->fetch_assoc()) {
				$quotation = array('id' => $row['id'],'name' => $row['name'], 'lastname' => $row['lastname'],
							'card' => $row['card'], 'total' => $row['total']);
				array_push($response, $quotation);
			}
			$stsTxt = array("statusTxt" => "SUCCESS","array" => $response);

			return ($stsTxt);
		}
		else {
			return(array("statusTxt" => "Quotations Not Found"));
		}
	}

	function dailyQuotationEmployeeAction($id){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT id, card, total FROM QUOTATION WHERE date = CURDATE() AND idEmployee = '$id'";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			$response = array();
			while($row = $result->fetch_assoc()) {
				$quotation = array('id' => $row['id'],'card' => $row['card'], 'total' => $row['total']);
				array_push($response, $quotation);
			}
			$stsTxt = array("statusTxt" => "SUCCESS","array" => $response);

			return ($stsTxt);
		}
		else {
			return(array("statusTxt" => "Quotations Not Found"));
		}
	}

	function addQuotationAction($id,$card,$total){
		$conn = connect();

		if ($conn->connect_error) 
		{
		    header('HTTP/1.1 500 Bad connection to Database');
		    return (array("statusTxt" => "Database Not Found", "code" => 1337));
		}
		else
		{
			$sql = "INSERT INTO QUOTATION (idEmployee, card, date, total) VALUES ('$id', '$card', CURDATE(), '$total')";
		    	
	    	if (mysqli_query($conn, $sql)) 
	    	{
	    		$sql = "SELECT MAX(id) as id FROM QUOTATION";
	    		$result = $conn->query($sql);
	    		if($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) 
				    {
					    $response = array("statusTxt" => "SUCCESS", 'id' => $row['id']); 
					}
				    return $response;
				}
			} 
			else 
			{
				return (array("statusTxt" => "FAIL"));
			}
		} 
	}

	function loadQuotations(){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT q.id as id, e.name as name, e.lastname as lastname, q.card as card, q.total as total FROM QUOTATION q, EMPLOYEE e WHERE q.idEmployee = e.id ORDER BY date DESC";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			$response = array();
			while($row = $result->fetch_assoc()) {
				$quotation = array('id' => $row['id'],'name' => $row['name'], 'lastname' => $row['lastname'],
							'card' => $row['card'], 'total' => $row['total']);
				array_push($response, $quotation);
			}
			$stsTxt = array("statusTxt" => "SUCCESS","array" => $response);

			return ($stsTxt);
		}
		else {
			return(array("statusTxt" => "Quotations Not Found"));
		}
	}

		function loadEmployeesAction(){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT * FROM EMPLOYEE ORDER BY name";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			$response = array();
			while($row = $result->fetch_assoc()) {
				$product = array('id'=>$row['id'],'name' => $row['name'], 'lastname' => $row['lastname'],
							'birthdate' => $row['birthdate'], 'phone' => $row['phone'],'address' => $row['address']);
				array_push($response, $product);
			}
			$stsTxt = array("statusTxt" => "SUCCESS","array" => $response);

			return ($stsTxt);
		}
		else {
			return(array("statusTxt" => "Employees Not Found"));
		}
	}

	function profileAction($id){
		$conn = connect();

		if ($conn != null)
		{
			$sql = "SELECT * FROM EMPLOYEE WHERE id = '$id'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0)
			{
				
				while($row = $result->fetch_assoc()) 
		    	{
			    	$response = array("statusTxt" => "SUCCESS", 'id' => $row['id'], 'password' => $row['password'], "type" => $row['type'], "name" => $row['name'], "lastname" => $row['lastname'],"birthdate"=>$row['birthdate'], "phone" =>$row['phone'], "address"=>$row['address']); 
				}
		    	return $response;
			}
			else{
				$response = array("statusTxt" => "ID Not Found", "data" =>$row); 
			return $response;
			}
			$conn->close();
		}
		else{
			$response = array("statusTxt" => "Could Not Find Database", "data" =>$row); 
			return $response;
		}
	}

	function loadEmployeeAction($id){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT * FROM EMPLOYEE WHERE id = '$id'";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) 
		    {
			    $response = array("statusTxt" => "SUCCESS", 'id' => $row['id'], 'password' => $row['password'],
			     "name" => $row['name'], "lastname" => $row['lastname'], "birthdate" => $row['birthdate'], "phone" => $row['phone'],
			      "address" => $row['address']); 
			}
		    return $response;
		}
		else {
			return(array("statusTxt" => "Product Not Found"));
		}
	}

	function addEmployeeAction($id, $password, $name, $lastname, $birthdate, $telephone, $address){
		$conn = connect();

		if ($conn->connect_error) 
		{
		    header('HTTP/1.1 500 Bad connection to Database');
		    return (array("statusTxt" => "Database Not Found", "code" => 1337));
		}
		else
		{
			$sql = "INSERT INTO EMPLOYEE (id, password, type, name, lastname, birthdate, phone, address) 
					VALUES ('$id','$password', 'E', '$name', '$lastname', '$birthdate', '$telephone', '$address')";
		    	
	    	if (mysqli_query($conn, $sql)) 
	    	{
			    return (array("statusTxt" => "SUCCESS"));
			} 
			else 
			{
				return (array("statusTxt" => "FAIL"));
			}
		} 
	}

	function updateEmployAction($id, $password, $name, $lastname, $birthdate, $telephone, $address){
		$conn = connect();

		if ($conn->connect_error) {
			return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "UPDATE EMPLOYEE SET password = '$password', name = '$name', lastname = '$lastname',
				birthdate = '$birthdate', phone = '$telephone', address = '$address' WHERE id = '$id'"; 
		if (mysqli_query($conn, $sql)) 
    	{
    		return (array("statusTxt" => "SUCCESS"));
		}
		else
		{
			return(array("statusTxt" => "Employee was not updated"));
		}
	}


	function loadAllQuotations(){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT * FROM QUOTATION WHERE date=CURDATE() ORDER BY date";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			$response = array();
			while($row = $result->fetch_assoc()) {
				$product = array('id'=>$row['id'],'idEmployee' => $row['idEmployee'], 'card' => $row['card'],
							'date' => $row['date'], 'total' => $row['total']);
				array_push($response, $product);
			}
			$stsTxt = array("statusTxt" => "SUCCESS","array" => $response);

			return ($stsTxt);
		}
		else {
			return(array("statusTxt" => "Qutations Not Found"));
		}
	}

		function loadAllQuotationsA(){
		$conn = connect();

		if ($conn->connect_error) 
		{
		   	return(array("statusTxt" => "Database Not Found"));
		}
		$sql = "SELECT * FROM QUOTATION ORDER BY date";
		$result = $conn->query($sql);
		if($result->num_rows > 0) {
			$response = array();
			while($row = $result->fetch_assoc()) {
				$product = array('id'=>$row['id'],'idEmployee' => $row['idEmployee'], 'card' => $row['card'],
							'date' => $row['date'], 'total' => $row['total']);
				array_push($response, $product);
			}
			$stsTxt = array("statusTxt" => "SUCCESS","array" => $response);

			return ($stsTxt);
		}
		else {
			return(array("statusTxt" => "Qutations Not Found"));
		}
	}

	
?>
