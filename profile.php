<?php

//profile.php

include ('header.php');

$teacher_name = '';
$teacher_address = '';
$teacher_emailid = '';
$teacher_password = '';
$teacher_grade_id = '';
$teacher_qualification = '';
$teacher_doj = '';
$teacher_image = '';
$error_teacher_name = '';
$error_teacher_address = '';
$error_teacher_emailid = '';
$error_teacher_grade_id = '';
$error_teacher_qualification = '';
$error_teacher_doj = '';
$error_teacher_image = '';
$error = 0;
$success = '';

if (isset ($_POST["button_action"])) {
	$teacher_image = $_POST["hidden_teacher_image"];
	if ($_FILES["teacher_image"]["name"] != '') {
		$file_name = $_FILES["teacher_image"]["name"];
		$tmp_name = $_FILES["teacher_image"]["tmp_name"];
		$extension_array = explode(".", $file_name);
		$extension = strtolower($extension_array[1]);
		$allowed_extension = array('jpg', 'png');
		if (!in_array($extension, $allowed_extension)) {
			$error_teacher_image = "Invalid Image Format";
			$error++;
		} else {
			$teacher_image = uniqid() . '.' . $extension;
			$upload_path = 'admin/teacher_image/' . $teacher_image;
			move_uploaded_file($tmp_name, $upload_path);
		}
	}

	if (empty ($_POST["teacher_name"])) {
		$error_teacher_name = "Thiếu của giáo viên!";
		$error++;
	} else {
		$teacher_name = $_POST["teacher_name"];
	}

	if (empty ($_POST["teacher_address"])) {
		$error_teacher_address = 'Thiếu địa chỉ!';
		$error++;
	} else {
		$teacher_address = $_POST["teacher_address"];
	}

	if (empty ($_POST["teacher_emailid"])) {
		$error_teacher_emailid = "Thiếu Email";
		$error++;
	} else {
		if (!filter_var($_POST["teacher_emailid"], FILTER_VALIDATE_EMAIL)) {
			$error_teacher_emailid = "Địa chỉ emai không hợp lệ";
			$error;
		} else {
			$teacher_emailid = $_POST["teacher_emailid"];
		}
	}
	if (!empty ($_POST["teacher_password"])) {
		$teacher_password = $_POST["teacher_password"];
	}

	if (empty ($_POST["teacher_grade_id"])) {
		$error_teacher_grade_id = 'Chưa chọn lớp';
		$error++;
	} else {
		$teacher_grade_id = $_POST["teacher_grade_id"];
	}

	if (empty ($_POST["teacher_qualification"])) {
		$error_teacher_qualification = "Chưa chon Khoa";
		$error++;
	} else {
		$teacher_qualification = $_POST["teacher_qualification"];
	}

	if (empty ($_POST["teacher_doj"])) {
		$error_teacher_doj = "Chưa chọn ngày vào làm";
		$error++;
	} else {
		$teacher_doj = $_POST["teacher_doj"];
	}

	if ($error == 0) {
		if ($teacher_password != '') {
			$data = array(
				':teacher_name' => $teacher_name,
				':teacher_address' => $teacher_address,
				':teacher_emailid' => $teacher_emailid,
				':teacher_password' => password_hash($teacher_password, PASSWORD_DEFAULT),
				':teacher_qualification' => $teacher_qualification,
				':teacher_doj' => $teacher_doj,
				':teacher_image' => $teacher_image,
				':teacher_grade_id' => $teacher_grade_id,
				':teacher_id' => $_POST["teacher_id"]
			);
			$query = "
			UPDATE tbl_teacher 
		      SET teacher_name = :teacher_name, 
		      teacher_address = :teacher_address, 
		      teacher_emailid = :teacher_emailid, 
		      teacher_password = :teacher_password, 
		      teacher_grade_id = :teacher_grade_id, 
		      teacher_qualification = :teacher_qualification, 
		      teacher_doj = :teacher_doj, 
		      teacher_image = :teacher_image 
		      WHERE teacher_id = :teacher_id
			";
		} else {
			$data = array(
				':teacher_name' => $teacher_name,
				':teacher_address' => $teacher_address,
				':teacher_emailid' => $teacher_emailid,
				':teacher_qualification' => $teacher_qualification,
				':teacher_doj' => $teacher_doj,
				':teacher_image' => $teacher_image,
				':teacher_grade_id' => $teacher_grade_id,
				':teacher_id' => $_POST["teacher_id"]
			);
			$query = "
			UPDATE tbl_teacher 
		      SET teacher_name = :teacher_name, 
		      teacher_address = :teacher_address, 
		      teacher_emailid = :teacher_emailid, 
		      teacher_grade_id = :teacher_grade_id, 
		      teacher_qualification = :teacher_qualification, 
		      teacher_doj = :teacher_doj, 
		      teacher_image = :teacher_image 
		      WHERE teacher_id = :teacher_id
			";
		}

		$statement = $connect->prepare($query);
		if ($statement->execute($data)) {
			$success = '<div class="alert alert-success">Thay đổi thông tin thành công</div>';
		}
	}
}


$query = "
SELECT * FROM tbl_teacher 
WHERE teacher_id = '" . $_SESSION["teacher_id"] . "'
";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

?>

<div class="container" style="margin-top:30px">
	<span>
		<?php echo $success; ?>
	</span>
	<div class="card">
		<form method="post" id="profile_form" enctype="multipart/form-data">
			<div class="card-header">
				<div class="row">
					<div class="col-md-9">Hồ sơ</div>
					<div class="col-md-3" align="right">
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="form-group">
					<div class="row">
						<label class="col-md-4 text-right">Tên giáo viên <span class="text-danger">*</span></label>
						<div class="col-md-8">
							<input type="text" name="teacher_name" id="teacher_name" class="form-control" />
							<span class="text-danger">
								<?php echo $error_teacher_name; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<label class="col-md-4 text-right">Địa chỉ <span class="text-danger">*</span></label>
						<div class="col-md-8">
							<textarea name="teacher_address" id="teacher_address" class="form-control"></textarea>
							<span class="text-danger">
								<?php echo $error_teacher_address; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<label class="col-md-4 text-right">Email <span class="text-danger">*</span></label>
						<div class="col-md-8">
							<input type="text" name="teacher_emailid" id="teacher_emailid" class="form-control" />
							<span class="text-danger">
								<?php echo $error_teacher_emailid; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<label class="col-md-4 text-right">Mật khẩu <span class="text-danger">*</span></label>
						<div class="col-md-8">
							<input type="password" name="teacher_password" id="teacher_password" class="form-control"
								placeholder="Leave blank to not change it" />
							<span class="text-danger"></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<label class="col-md-4 text-right">Khoa <span class="text-danger">*</span></label>
						<div class="col-md-8">
							<input type="text" name="teacher_qualification" id="teacher_qualification"
								class="form-control" />
							<span class="text-danger">
								<?php echo $error_teacher_qualification; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<label class="col-md-4 text-right">Lớp <span class="text-danger">*</span></label>
						<div class="col-md-8">
							<select name="teacher_grade_id" id="teacher_grade_id" class="form-control">
								<option value="">Chọn lớp</option>
								<?php
								echo load_grade_list($connect);
								?>
							</select>
							<span class="text-danger">
								<?php echo $error_teacher_grade_id; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<label class="col-md-4 text-right">Ngày vào làm <span class="text-danger">*</span></label>
						<div class="col-md-8">
							<input type="text" name="teacher_doj" id="teacher_doj" class="form-control" readonly />
							<span class="text-danger">
								<?php echo $error_teacher_doj; ?>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<label class="col-md-4 text-right">Hình <span class="text-danger">*</span></label>
						<div class="col-md-8">
							<input type="file" name="teacher_image" id="teacher_image" />
							<span class="text-muted">Only .jpg and .png allowed</span><br />
							<span id="error_teacher_image" class="text-danger">
								<?php echo $error_teacher_image; ?>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer" align="center">
				<input type="hidden" name="hidden_teacher_image" id="hidden_teacher_image" />
				<input type="hidden" name="teacher_id" id="teacher_id" />
				<input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm"
					value="Save" />
			</div>
		</form>
	</div>
</div>
<br />
<br />
</body>

</html>

<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="css/datepicker.css" />

<style>
	.datepicker {
		z-index: 1600 !important;
		/* has to be larger than 1050 */
	}
</style>

<script>
	$(document).ready(function () {

		<?php
		foreach ($result as $row) {
			?>
			$('#teacher_name').val("<?php echo $row["teacher_name"]; ?>");
			$('#teacher_address').val("<?php echo $row["teacher_address"]; ?>");
			$('#teacher_emailid').val("<?php echo $row["teacher_emailid"]; ?>");
			$('#teacher_qualification').val("<?php echo $row["teacher_qualification"]; ?>");
			$('#teacher_grade_id').val("<?php echo $row["teacher_grade_id"]; ?>");
			$('#teacher_doj').val("<?php echo $row["teacher_doj"]; ?>");
			$('#error_teacher_image').html("<img src='admin/teacher_image/<?php echo $row['teacher_image']; ?>' class='img-thumbnail' width='100' />");
			$('#hidden_teacher_image').val('<?php echo $row["teacher_image"]; ?>');
			$('#teacher_id').val("<?php echo $row["teacher_id"]; ?>");

			<?php
		}
		?>

		$('#teacher_doj').datepicker({
			format: "yyyy-mm-dd",
			autoclose: true
		});

	});
</script>