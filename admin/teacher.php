<?php

include ('header.php');

?>

<div class="container" style="margin-top:30px">
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-9">Danh sách giảng viên</div>
        <div class="col-md-3" align="right">
          <button type="button" id="add_button" class="btn btn-info btn-sm">THÊM MỚI</button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <span id="message_operation"></span>
        <table class="table table-striped table-bordered" id="teacher_table">
          <thead>
            <tr>
              <th>Hình</th>
              <th>Tên giảng viên</th>
              <th>Địa chỉ</th>
              <th>Lớp được phân công</th>
              <th>Xem hồ sơ</th>
              <th>Chỉnh sửa</th>
              <th>Xoá</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</body>

</html>

<script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="../css/datepicker.css" />

<style>
  .datepicker {
    z-index: 1600 !important;
    /* has to be larger than 1050 */
  }
</style>

<div class="modal" id="formModal">
  <div class="modal-dialog">
    <form method="post" id="teacher_form" enctype="multipart/form-data">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" id="modal_title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Tên giảng viên <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="teacher_name" id="teacher_name" class="form-control" />
                <span id="error_teacher_name" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Địa chỉ <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <textarea name="teacher_address" id="teacher_address" class="form-control"></textarea>
                <span id="error_teacher_address" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Email <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="teacher_emailid" id="teacher_emailid" class="form-control" />
                <span id="error_teacher_emailid" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Mật khẩu <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="password" name="teacher_password" id="teacher_password" class="form-control" />
                <span id="error_teacher_password" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Ngành <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="teacher_qualification" id="teacher_qualification" class="form-control" />
                <span id="error_teacher_qualification" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Lớp <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="teacher_grade_id" id="teacher_grade_id" class="form-control">
                  <option value="">Select Grade</option>
                  <?php
                  echo load_grade_list($connect);
                  ?>
                </select>
                <span id="error_teacher_grade_id" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Ngày vào làm <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="teacher_doj" id="teacher_doj" class="form-control" />
                <span id="error_teacher_doj" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Hình <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="file" name="teacher_image" id="teacher_image" />
                <span class="text-muted"></span><br />
                <span id="error_teacher_image" class="text-danger"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <input type="hidden" name="hidden_teacher_image" id="hidden_teacher_image" value="" />
          <input type="hidden" name="teacher_id" id="teacher_id" />
          <input type="hidden" name="action" id="action" value="Thêm" />
          <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
        </div>

      </div>
    </form>
  </div>
</div>

<div class="modal" id="viewModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Thông tin chi tiết</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="teacher_details">

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
      </div>

    </div>
  </div>
</div>

<div class="modal" id="deleteModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Xác nhận xoá</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <h3 align="center">Bạn muốn xoá giảng viên này?</h3>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="ok_button" id="ok_button" class="btn btn-primary btn-sm">Đồng ý</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
      </div>

    </div>
  </div>
</div>


<script>
  $(document).ready(function () {
    var dataTable = $('#teacher_table').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        url: "teacher_action.php",
        type: "POST",
        data: { action: 'fetch' }
      },
      "columnDefs": [
        {
          "targets": [0, 4, 5, 6],
          "orderable": false,
        },
      ],
    });

    $('#teacher_doj').datepicker({
      format: "yyyy-mm-dd",
      autoclose: true,
      container: '#formModal modal-body'
    });

    function clear_field() {
      $('#teacher_form')[0].reset();
      $('#error_teacher_name').text('');
      $('#error_teacher_address').text('');
      $('#error_teacher_emailid').text('');
      $('#error_teacher_password').text('');
      $('#error_teacher_qualification').text('');
      $('#error_teacher_doj').text('');
      $('#error_teacher_image').text('');
      $('#error_teacher_grade_id').text('');
    }

    $('#add_button').click(function () {
      $('#modal_title').text("Add Teacher");
      $('#button_action').val('Add');
      $('#action').val('Add');
      $('#formModal').modal('show');
      clear_field();
    });

    $('#teacher_form').on('submit', function (event) {
      event.preventDefault();
      $.ajax({
        url: "teacher_action.php",
        method: "POST",
        data: new FormData(this),
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function () {
          $('#button_action').val('Validate...');
          $('#button_action').attr('disabled', 'disabled');
        },
        success: function (data) {
          $('#button_action').attr('disabled', false);
          $('#button_action').val($('#action').val());
          if (data.success) {
            $('#message_operation').html('<div class="alert alert-success">' + data.success + '</div>');
            clear_field();
            $('#formModal').modal('hide');
            dataTable.ajax.reload();
          }
          if (data.error) {
            if (data.error_teacher_name != '') {
              $('#error_teacher_name').text(data.error_teacher_name);
            }
            else {
              $('#error_teacher_name').text('');
            }
            if (data.error_teacher_address != '') {
              $('#error_teacher_address').text(data.error_teacher_address);
            }
            else {
              $('#error_teacher_address').text('');
            }
            if (data.error_teacher_emailid != '') {
              $('#error_teacher_emailid').text(data.error_teacher_emailid);
            }
            else {
              $('#error_teacher_emailid').text('');
            }
            if (data.error_teacher_password != '') {
              $('#error_teacher_password').text(data.error_teacher_password);
            }
            else {
              $('#error_teacher_password').text('');
            }
            if (data.error_teacher_grade_id != '') {
              $('#error_teacher_grade_id').text(data.error_teacher_grade_id);
            }
            else {
              $('#error_teacher_grade_id').text('');
            }
            if (data.error_teacher_qualification != '') {
              $('#error_teacher_qualification').text(data.error_teacher_qualification);
            }
            else {
              $('#error_teacher_qualification').text('');
            }
            if (data.error_teacher_doj != '') {
              $('#error_teacher_doj').text(data.error_teacher_doj);
            }
            else {
              $('#error_teacher_doj').text('');
            }
            if (data.error_teacher_image != '') {
              $('#error_teacher_image').text(data.error_teacher_image);
            }
            else {
              $('#error_teacher_image').text('');
            }
          }
        }
      });
    });

    var teacher_id = '';

    $(document).on('click', '.view_teacher', function () {
      teacher_id = $(this).attr('id');
      $.ajax({
        url: "teacher_action.php",
        method: "POST",
        data: { action: 'single_fetch', teacher_id: teacher_id },
        success: function (data) {
          $('#viewModal').modal('show');
          $('#teacher_details').html(data);
        }
      });
    });

    $(document).on('click', '.edit_teacher', function () {
      teacher_id = $(this).attr('id');
      clear_field();
      $.ajax({
        url: "teacher_action.php",
        method: "POST",
        data: { action: 'edit_fetch', teacher_id: teacher_id },
        dataType: "json",
        success: function (data) {
          $('#teacher_name').val(data.teacher_name);
          $('#teacher_address').val(data.teacher_address);
          $('#teacher_grade_id').val(data.teacher_grade_id);
          $('#teacher_qualification').val(data.teacher_qualification);
          $('#teacher_doj').val(data.teacher_doj);
          $('#error_teacher_image').html('<img src="teacher_image/' + data.teacher_image + '" class="img-thumbnail" width="50" />');
          $('#hidden_teacher_image').val(data.teacher_image);
          $('#teacher_id').val(data.teacher_id);
          $('#modal_title').text('Edit Teacher');
          $('#button_action').val('Edit');
          $('#action').val('Edit');
          $('#formModal').modal('show');
        }
      });
    });

    $(document).on('click', '.delete_teacher', function () {
      teacher_id = $(this).attr('id');
      $('#deleteModal').modal('show');
    });

    $('#ok_button').click(function () {
      $.ajax({
        url: "teacher_action.php",
        method: "POST",
        data: { teacher_id: teacher_id, action: 'delete' },
        success: function (data) {
          $('#message_operation').html('<div class="alert alert-success">' + data + '</div>');
          $('#deleteModal').modal('hide');
          dataTable.ajax.reload();
        }
      })
    });

  });
</script>