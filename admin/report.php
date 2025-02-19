<?php

//report.php

if (isset ($_GET["action"])) {
	include ('database_connection.php');
	require_once 'pdf.php';
	session_start();
	$output = '';
	if ($_GET["action"] == 'attendance_report') {
		if (isset ($_GET["grade_id"], $_GET["from_date"], $_GET["to_date"])) {
			$pdf = new Pdf();
			$query = "
            SELECT tbl_attendance.attendance_date FROM tbl_attendance 
            INNER JOIN tbl_student 
            ON tbl_student.student_id = tbl_attendance.student_id 
            WHERE tbl_student.student_grade_id = '" . $_GET["grade_id"] . "' 
            AND (tbl_attendance.attendance_date BETWEEN '" . $_GET["from_date"] . "' AND '" . $_GET["to_date"] . "')
            GROUP BY tbl_attendance.attendance_date 
            ORDER BY tbl_attendance.attendance_date ASC
            ";
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			$output .= '
                <style>
                @page { margin: 20px; }
                body { font-family: "Arial Unicode MS", Arial, sans-serif; }
                </style>
                <p>&nbsp;</p>
                <h3 align="center">THONG KE DIEM DANH</h3><br />';
			foreach ($result as $row) {
				$output .= '
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tr>
                        <td><b>Date - ' . $row["attendance_date"] . '</b></td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" border="1" cellpadding="5" cellspacing="0">
                                <tr>
                                    <td><b>Tên sinh viên</b></td>
                                    <td><b>Mã sinh viên</b></td>
                                    <td><b>Lop</b></td>
                                    <td><b>Giang vien</b></td>
                                    <td><b>Ket qua diem danh</b></td>
                                </tr>
                ';
				$sub_query = "
                SELECT * FROM tbl_attendance 
                INNER JOIN tbl_student 
                ON tbl_student.student_id = tbl_attendance.student_id 
                INNER JOIN tbl_grade 
                ON tbl_grade.grade_id = tbl_student.student_grade_id 
                INNER JOIN tbl_teacher 
                ON tbl_teacher.teacher_grade_id = tbl_grade.grade_id 
                WHERE tbl_student.student_grade_id = '" . $_GET["grade_id"] . "' 
                AND tbl_attendance.attendance_date = '" . $row["attendance_date"] . "'
                ";

				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
				foreach ($sub_result as $sub_row) {
					$output .= '
                    <tr>
                        <td>' . $sub_row["student_name"] . '</td>
                        <td>' . $sub_row["student_roll_number"] . '</td>
                        <td>' . $sub_row["grade_name"] . '</td>
                        <td>' . $sub_row["teacher_name"] . '</td>
                        <td>' . $sub_row["attendance_status"] . '</td>
                    </tr>
                    ';
				}
				$output .=
					'</table>
                    </td>
                    </tr>
                </table><br />';
			}
			$file_name = 'Attendance Report.pdf';
			$pdf->loadHtml($output);
			$pdf->render();
			$pdf->stream($file_name, array("Attachment" => false));
			exit (0);
		}
	}

	if ($_GET["action"] == "student_report") {
		if (isset ($_GET["student_id"], $_GET["from_date"], $_GET["to_date"])) {
			$pdf = new Pdf();
			$query = "
            SELECT * FROM tbl_student 
            INNER JOIN tbl_grade 
            ON tbl_grade.grade_id = tbl_student.student_grade_id 
            WHERE tbl_student.student_id = '" . $_GET["student_id"] . "' 
            ";
			$statement = $connect->prepare($query);
			$statement->execute();
			$result = $statement->fetchAll();
			foreach ($result as $row) {
				$output .= '
                <style>
                @page { margin: 20px; }
                body { font-family: "Arial Unicode MS", Arial, sans-serif; }
                </style>
                <p>&nbsp;</p>
                <h3 align="center">Attendance Report</h3><br /><br />
                <table width="100%" border="0" cellpadding="5" cellspacing="0">
                    <tr>
                        <td width="25%"><b>Tên sinh viên</b></td>
                        <td width="75%">' . $row["student_name"] . '</td>
                    </tr>
                    <tr>
                        <td width="25%"><b>Mã sinh viênr</b></td>
                        <td width="75%">' . $row["student_roll_number"] . '</td>
                    </tr>
                    <tr>
                        <td width="25%"><b>Lớp</b></td>
                        <td width="75%">' . $row["grade_name"] . '</td>
                    </tr>
                    <tr>
                        <td colspan="2" height="5">
                            <h3 align="center">Chi tiết điểm danh</h3>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table width="100%" border="1" cellpadding="5" cellspacing="0">
                                <tr>
                                    <td><b>Ngày điểm danh</b></td>
                                    <td><b>Kết quả điểm danh</b></td>
                                </tr>
                ';
				$sub_query = "
                SELECT * FROM tbl_attendance 
                WHERE student_id = '" . $_GET["student_id"] . "' 
                AND (attendance_date BETWEEN '" . $_GET["from_date"] . "' AND '" . $_GET["to_date"] . "') 
                ORDER BY attendance_date ASC
                ";

				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$sub_result = $statement->fetchAll();
				foreach ($sub_result as $sub_row) {
					$output .= '
                    <tr>
                        <td>' . $sub_row["attendance_date"] . '</td>
                        <td>' . $sub_row["attendance_status"] . '</td>
                    </tr>
                    ';
				}
				$output .= '
                        </table>
                    </td>
                    </tr>
                </table>
                ';

				$file_name = "Attendance Report.pdf";
				$pdf->loadHtml($output);
				$pdf->render();
				$pdf->stream($file_name, array("Attachment" => false));
				exit (0);
			}
		}
	}
}

?>