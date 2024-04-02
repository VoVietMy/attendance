<?php
//login.php

include ('database_connection.php');
session_start();

if (isset ($_SESSION["admin_id"])) {
  header('Location:index.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ĐIỂM DANH SINH VIÊN</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>

</head>

<body>
  <div class="jumbotron text-center" style="margin-bottom:0">
    <h1>HỆ THỐNG ĐIỂM DANH SINH VIÊN</h1>
  </div>


  <div class="container">
    <div class="row">
      <div class="col-md-4">

      </div>
      <div class="col-md-4" style="margin-top:20px;">
        <div class="card">
          <div class="card-header">Đăng nhập Admin</div>
          <div class="card-body">
            <form method="post" id="admin_login_form">
              <div class="form-group">
                <label>Nhập tên người dùng</label>
                <input type="text" name="admin_user_name" id="admin_user_name" class="form-control" />
                <span id="error_admin_user_name" class="text-danger"></span>
              </div>
              <div class="form-group">
                <label>Nhập mật khẩu</label>
                <input type="password" name="admin_password" id="admin_password" class="form-control" />
                <span id="error_admin_password" class="text-danger"></span>
              </div>
              <div class="form-group">
                <input type="submit" name="admin_login" id="admin_login" class="btn btn-info" value="Đăng nhập" /> |
                <a class="btn btn-info" href="\attendanceFINAL\login_teacher.php">Trang giáo viên</a>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-4">

      </div>
    </div>
  </div>

</body>

</html>
</body>

</html>
<script>
  $(document).ready(function () {
    $('#admin_login_form').on('submit', function (event) {
      event.preventDefault();
      $.ajax({
        url: "check_admin_login.php",
        method: "POST",
        data: $(this).serialize(),
        dataType: "json",
        beforeSend: function () {
          $('#admin_login').val('Validate...');
          $('#admin_login').attr('disabled', 'disabled');
        },
        success: function (data) {
          if (data.success) {
            location.href = 'index_admin.php';
          }
          if (data.error) {
            $('#admin_login').val('Login');
            $('#admin_login').attr('disabled', false);
            if (data.error_admin_user_name != '') {
              $('#error_admin_user_name').text(data.error_admin_user_name);
            } else {
              $('#error_admin_user_name').text('');
            }
            if (data.error_admin_password != '') {
              $('#error_admin_password').text(data.error_admin_password);
            } else {
              $('#error_admin_password').text('');
            }
          }
        }
      });
    });
  });
</script>