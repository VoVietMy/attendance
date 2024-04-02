<?php

//index.php

include ('header.php');

?>

<div class="container" style="margin-top:30px">
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-9">TỔNG QUAN KẾT QUẢ ĐIỂM DANH</div>
        <div class="col-md-3" align="right">

        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="student_table">
          <thead>
            <tr>
              <th>Tên sinh viên</th>
              <th>Mã sinh viên</th>
              <th>Lớp</th>
              <th>Tỷ lệ % điểm danh</th>
              <th>Báo cáo</th>
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

<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="css/datepicker.css" />

<style>
  .datepicker {
    z-index: 1600 !important;
    /* has to be larger than 1050 */
  }
</style>

<div class="modal" id="formModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Tạo báo cáo</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
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
        <input type="hidden" name="student_id" id="student_id" />
        <button type="button" name="create_report" id="create_report" class="btn btn-success btn-sm">Tạo báo
          cáo</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Đóng</button>
      </div>

    </div>
  </div>
</div>


<script>
  $(document).ready(function () {

    var dataTable = $('#student_table').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
        url: "attendance_action.php",
        type: "POST",
        data: { action: 'index_fetch' }
      }
    });

    $('.input-daterange').datepicker({
      todayBtn: "linked",
      format: "yyyy-mm-dd",
      autoclose: true,
      container: '#formModal modal-body'
    });

    $(document).on('click', '.report_button', function () {
      var student_id = $(this).attr('id');
      $('#student_id').val(student_id);
      $('#formModal').modal('show');
    });

    $('#create_report').click(function () {
      var student_id = $('#student_id').val();
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      var error = 0;
      if (from_date == '') {
        $('#error_from_date').text('Chưa chọn ngày');
        error++;
      }
      else {
        $('#error_from_date').text('');
      }
      if (to_date == '') {
        $('#error_to_date').text('Chưa chọn ngày');
        error++;
      }
      else {
        $('#error_to_date').text('');
      }

      if (error == 0) {
        $('#from_date').val('');
        $('#to_date').val('');
        $('#formModal').modal('hide');
        window.open("report.php?action=student_report&student_id=" + student_id + "&from_date=" + from_date + "&to_date=" + to_date);
      }
    });

  });
</script>