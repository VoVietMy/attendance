<?php

//attendance.php

include ('header.php');

?>

<div class="container mt-4">
  <h2>Danh sách điểm danh</h2>
  <div class="row mb-3">
    <div class="col-md-9">
      <button type="button" id="report_button" class="btn btn-danger btn-sm">BÁO CÁO</button>
      <button type="button" id="add_button" class="btn btn-info btn-sm">THÊM MỚI</button>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered" id="attendance_table" style="width:100%">
      <thead>
        <tr>
          <th>Tên sinh viên</th>
          <th>Mã sinh viên</th>
          <th>Lớp</th>
          <th>Kết quả điểm danh</th>
          <th>Ngày điểm danh</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>
</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="css/datepicker.css" />

<style>
  .datepicker {
    z-index: 1600 !important;
    /* has to be larger than 1050 */
  }
</style>

<?php

$query = "
SELECT * FROM tbl_grade WHERE grade_id = (SELECT teacher_grade_id FROM tbl_teacher 
    WHERE teacher_id = '" . $_SESSION["teacher_id"] . "')
";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

?>

<div class="modal" id="formModal">
  <div class="modal-dialog">
    <form method="post" id="attendance_form">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" id="modal_title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <?php
          foreach ($result as $row) {
            ?>
            <div class="form-group">
              <div class="row">
                <label class="col-md-4 text-right">Lớp <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <?php
                  echo '<label>' . $row["grade_name"] . '</label>';
                  ?>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <label class="col-md-4 text-right">Ngày điểm danh <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input type="text" name="attendance_date" id="attendance_date" class="form-control" readonly />
                  <span id="error_attendance_date" class="text-danger"></span>
                </div>
              </div>
            </div>
            <div class="form-group" id="student_details">
              <div class="table-responsive">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Mã sinh viên</th>
                      <th>Tên sinh viên</th>
                      <th>Có mặt</th>
                      <th>Vắng mặt</th>
                    </tr>
                  </thead>
                  <?php
                  $sub_query = "
                  SELECT * FROM tbl_student 
                  WHERE student_grade_id = '" . $row["grade_id"] . "'
                ";
                  $statement = $connect->prepare($sub_query);
                  $statement->execute();
                  $student_result = $statement->fetchAll();
                  foreach ($student_result as $student) {
                    ?>
                    <tr>
                      <td>
                        <?php echo $student["student_roll_number"]; ?>
                      </td>
                      <td>
                        <?php echo $student["student_name"]; ?>
                        <input type="hidden" name="student_id[]" value="<?php echo $student["student_id"]; ?>" />
                      </td>
                      <td>
                        <input type="radio" name="attendance_status<?php echo $student["student_id"]; ?>" value="Present" />
                      </td>
                      <td>
                        <input type="radio" name="attendance_status<?php echo $student["student_id"]; ?>" checked
                          value="Absent" />
                      </td>
                    </tr>
                    <?php
                  }
                  ?>
                </table>
              </div>
            </div>
            <?php
          }
          ?>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <input type="hidden" name="action" id="action" value="Add" />
          <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Thêm" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
        </div>

      </div>
    </form>
  </div>
</div>

<div class="modal" id="reportModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Xuất báo cáo</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="form-group">
          <div class="input-daterange">
            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="Từ ngày" readonly />
            <span id="error_from_date" class="text-danger"></span>
            <br />
            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="Đến ngày" readonly />
            <span id="error_to_date" class="text-danger"></span>
          </div>
        </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="create_report" id="create_report" class="btn btn-success btn-sm">Tạo báo
          cáo</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
      </div>

    </div>
  </div>
</div>

<script>
  $(document).ready(function () {

    var dataTable = $('#attendance_table').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        url: "attendance_action.php",
        method: "POST",
        data: { action: "fetch" }
      }
    });

    $('#attendance_date').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true,
      container: '#formModal modal-body'
    });

    function clear_field() {
      $('#attendance_form')[0].reset();
      $('#error_attendance_date').text('');
    }

    $('#add_button').click(function () {
      $('#modal_title').text("Thêm điểm danh");
      $('#formModal').modal('show');
      clear_field();
    });

    $('#attendance_form').on('submit', function (event) {
      event.preventDefault();
      $.ajax({
        url: "attendance_action.php",
        method: "POST",
        data: $(this).serialize(),
        dataType: "json",
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
            if (data.error_attendance_date != '') {
              $('#error_attendance_date').text(data.error_attendance_date);
            }
            else {
              $('#error_attendance_date').text('');
            }
          }
        }
      })
    });

    $('.input-daterange').datepicker({
      todayBtn: "linked",
      format: "yyyy-mm-dd",
      autoclose: true,
      container: '#formModal modal-body'
    });

    $(document).on('click', '#report_button', function () {
      $('#reportModal').modal('show');
    });

    $('#create_report').click(function () {
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      var error = 0;
      if (from_date == '') {
        $('#error_from_date').text('Cần ngày');
        error++;
      }
      else {
        $('#error_from_date').text('');
      }

      if (to_date == '') {
        $('#error_to_date').text("Cần ngày");
        error++;
      }
      else {
        $('#error_to_date').text('');
      }

      if (error == 0) {
        $('#from_date').val('');
        $('#to_date').val('');
        $('#formModal').modal('hide');
        window.open("report.php?action=attendance_report&from_date=" + from_date + "&to_date=" + to_date);
      }

    });

  });
</script>