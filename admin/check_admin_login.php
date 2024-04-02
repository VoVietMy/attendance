<?php
include ('database_connection.php');

session_start();

$admin_user_name = '';
$admin_password = '';
$error_admin_user_name = '';
$error_admin_password = '';
$error = 0;

if (empty ($_POST["admin_user_name"])) {
	$error_admin_user_name = 'User name is required';
	$error++;
} else {
	$admin_user_name = $_POST["admin_user_name"];
}
if (empty ($_POST["admin_password"])) {
	$error_admin_password = 'Password is required';
	$error++;
} else {
	$admin_password = $_POST["admin_password"];

}

if ($error == 0) { //khong co validate nao co loi xay ra
	$query = "SELECT * FROM tbl_admin WHERE admin_user_name='" . $admin_user_name . "'";

	$statement = $connect->prepare($query);


	if ($statement->execute()) {
		$total_row = $statement->rowCount();
		if ($total_row > 0) {
			$result = $statement->fetchAll();
			foreach ($result as $row) {
				if ($admin_password === $row['admin_password']) { // So sánh mật khẩu trực tiếp, không sử dụng password_verify
					$_SESSION["admin_id"] = $row['admin_id'];
				} else {
					$error_admin_password = "Sai mật khẩu";
					$error++;
				}
			}
		} else {
			$error_admin_user_name = 'Sai tên tài khoản';
			$error++;
		}
	}

}
if ($error > 0) {
	$output = array(
		'error' => true,
		'error_admin_user_name' => $error_admin_user_name,
		'error_admin_password' => $error_admin_password

	);
} else {
	$output = array(
		'success' => true,



	);
}

echo json_encode($output);


?>