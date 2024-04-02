<?php
include ('admin/database_connection.php');

session_start();

$teacher_emailid = '';
$teacher_password = '';
$error_teacher_emailid = '';
$error_teacher_password = '';
$error = 0;

if (empty ($_POST["teacher_emailid"])) {
	$error_teacher_emailid = 'Chưa nhập Email';
	$error++;
} else {
	$teacher_emailid = $_POST["teacher_emailid"];
}
if (empty ($_POST["teacher_password"])) {
	$error_teacher_password = 'Chưa nhập mật khẩu';
	$error++;
} else {
	$teacher_password = $_POST["teacher_password"];
}

if ($error == 0) { // Không có lỗi nào xảy ra
	$query = "SELECT * FROM tbl_teacher WHERE teacher_emailid=:teacher_emailid";

	$statement = $connect->prepare($query);
	$statement->bindParam(':teacher_emailid', $teacher_emailid);
	$statement->execute();

	$row = $statement->fetch(PDO::FETCH_ASSOC);

	if ($row) { // Tìm thấy người dùng với email này
		if (password_verify($teacher_password, $row['teacher_password'])) { // Sử dụng password_verify để kiểm tra mật khẩu đã hash
			$_SESSION["teacher_id"] = $row['teacher_id'];
		} else {
			$error_teacher_password = "Sai mật khẩu";
			$error++;
		}
	} else {
		$error_teacher_emailid = 'Sai Email';
		$error++;
	}
}

if ($error > 0) {
	$output = array(
		'error' => true,
		'error_teacher_emailid' => $error_teacher_emailid,
		'error_teacher_password' => $error_teacher_password
	);
} else {
	$output = array(
		'success' => true
	);
}

echo json_encode($output);
?>