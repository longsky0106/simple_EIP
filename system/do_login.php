<?php
require_once('../functions/MyPDO.php');
require_once('../system/MyConfig.php');	

$data = $_POST['data'];
parse_str($_REQUEST['data'], $data); 
// echo '<pre>';print_r($data);echo '</pre>';
// echo $data;	

$btn_login = strip_tags($data['btn_login']);

if(isset($btn_login)){
	
    $username = strip_tags($data['username']);
    $password = strip_tags($data['password']);
    $remeber = strip_tags($data['remeber']);

	
	
	if(empty($username)){
		echo "帳號未輸入！";
		exit();
	}else if(empty($password)){
		echo "密碼未輸入！";
		exit();
	}else{
		// 查詢帳號是否存在
		$sql = "SELECT * FROM PCT.dbo.PCTUSER WHERE username = :username";
		$pdo = new MyPDO;
		$query = $pdo->bindQuery($sql,[
			':username' => $username
		]);
	
		$row_count = count($query);
		if($row_count){
			foreach($query as $row){
				$hash_encrypt = $row['password'];
			}
	
			if (password_verify($password, $hash_encrypt)) {
				echo "登入成功，即將轉回登入前頁面";
				session_start();
				$_SESSION['user'] = $username;
			}else{
				echo "密碼錯誤";
			}
		}else{
			echo "帳號不存在";
		}
		$query=null;
	
	}



}







?>