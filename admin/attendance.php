<?php

//attendance.php

include ('header.php');

?>

<div class="container" style="margin-top:30px">
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-9">Danh sách điểm danh</div>
        <div class="col-md-3" align="right">
          <button type="button" id="chart_button" class="btn btn-primary btn-sm">Xuất biểu đồ</button>
          <button type="button" id="report_button" class="btn btn-danger btn-sm">Xuất báo cáo</button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="attendance_table">
          <thead>
            <tr>
              <th>Tên sinh viên</th>
              <th>Mã sinh viên</th>
              <th>Lớp</th>
              <th>Kết quả điểm danh</th>
              <th>Ngày điểm danh</th>
              <th>Giảng viên</th>
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

<div class="modal" id="reportModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Xuất thống kê</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="form-group">
          <select name="grade_id" id="grade_id" class="form-control">
            <option value="">Chọn lớp</option>
            <?php
            echo load_grade_list($connect);
            ?>
          </select>
          <span id="error_grade_id" class="text-danger"></span>
        </div>
        <div class="form-group">
          <div class="input-daterange">
            <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly />
            <span id="error_from_date" class="text-danger"></span>
            <br />
            <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly />
            <span id="error_to_date" class="text-danger"></span>
          </div>
        </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="create_report" id="create_report" class="btn btn-success btn-sm">Xuất báo cáo
        </button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
      </div>

    </div>
  </div>
</div>

<div class="modal" id="chartModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Tạo biểu đồ lớp</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="form-group">
          <select name="chart_grade_id" id="chart_grade_id" class="form-control">
            <option value="">Chọn lớp</option>
            <?php
            echo load_grade_list($connect);
            ?>
          </select>
          <span id="error_chart_grade_id" class="text-danger"></span>
        </div>
        <div class="form-group">
          <div class="input-daterange">
            <input type="text" name="attendance_date" id="attendance_date" class="form-control" placeholder="Chọn ngày"
              readonly />
            <span id="error_attendance_date" class="text-danger"></span>
          </div>
        </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="create_chart" id="create_chart" class="btn btn-success btn-sm">Tạo biểu đồ</button>
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
        type: "POST",
        data: { action: 'fetch' }
      }
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
      var grade_id = $('#grade_id').val();
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      var error = 0;

      if (grade_id == '') {
        $('#error_grade_id').text('Grade is Required');
        error++;
      }
      else {
        $('#error_grade_id').text('');
      }

      if (from_date == '') {
        $('#error_from_date').text('From Date is Required');
        error++;
      }
      else {
        $('#error_from_date').text('');
      }

      if (to_date == '') {
        $('#error_to_date').text("To Date is Required");
        error++;
      }
      else {
        $('#error_to_date').text('');
      }

      if (error == 0) {
        $('#from_date').val('');
        $('#to_date').val('');
        $('#formModal').modal('hide');
        window.open("report.php?action=attendance_report&grade_id=" + grade_id + "&from_date=" + from_date + "&to_date=" + to_date);
      }

    });

    $('#chart_button').click(function () {
      $('#chart_grade_id').val('');
      $('#attendance_date').val('');
      $('#chartModal').modal('show');
    });

    $('#create_chart').click(function () {
      var grade_id = $('#chart_grade_id').val();
      var attendance_date = $('#attendance_date').val();
      var error = 0;
      if (grade_id == '') {
        $('#error_chart_grade_id').text('Grade is Required');
        error++;
      }
      else {
        $('#error_chart_grade_id').text('');
      }
      if (attendance_date == '') {
        $('#error_attendance_date').text('Date is Required');
        $error++;
      }
      else {
        $('#error_attendance_date').text('');
      }

      if (error == 0) {
        $('#attendance_date').val('');
        $('#chart_grade_id').val('');
        $('#chartModal').modal('show');
        window.open("chart1.php?action=attendance_report&grade_id=" + grade_id + "&date=" + attendance_date);
      }

    });

  });
</script>