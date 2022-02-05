<style type="text/css">
  .tg {
    border-collapse: collapse;
    border-spacing: 0;
  }

  .tg td {
    border-color: black;
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 10px;
    overflow: hidden;
    padding: 5px 5px;
    word-break: normal;
  }

  .tg th {
    border-color: black;
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 10px;
    font-weight: normal;
    overflow: hidden;
    padding: 5px 5px;
    word-break: normal;
  }

  .tg .tg-pb0m {
    border-color: inherit;
    text-align: center;
    vertical-align: bottom
  }

  .tg .tg-c3ow {
    border-color: inherit;
    text-align: center;
    vertical-align: top
  }

  .tg .tg-0pky {
    border-color: inherit;
    text-align: left;
    vertical-align: top
  }

  .tg .tg-za14 {
    border-color: inherit;
    text-align: left;
    vertical-align: bottom
  }

  .tg .tg-center {
    border-color: inherit;
    text-align: center;
    vertical-align: middle
  }

  @media print {
    footer {
      page-break-after: always;
    }
  }

  td {
    height: 30px !important;
    padding: 5px 5px !important;
  }
</style>
<?php foreach ($students as $student) : ?>
  <pre>
    <?php $resultlist = $student['resultlist'] ?>
    <?php $quarter_list = $student['quarter_list'] ?>
    <?php $student_attendance = $student['student_attendance'] ?>
    <?php $student_conduct = $student['student_conduct'] ?>
  </pre>
  <table class="tg" style="width: 100%; line-height: .9" onload="window.print()">
    <thead>
      <tr>
        <th class="tg-pb0m" colspan="8" border="0">
          <img style="" src="http://campuscloudph.com/sics/resources/version_1/images/general/school.png" height="65px" width="50px">
          <div>San Isidro Catholic School</div>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="tg-pb0m" colspan="8">1830 Taft Avenue Pasay</td>
      </tr>
      <tr>
        <td class="tg-pb0m" colspan="8">Report Card</td>
      </tr>
      <tr>
        <td class="tg-0pky" colspan="2">Name</td>
        <td class="tg-za14" colspan="4"><?php echo ucfirst($student['lastname']) . ", " . ucfirst($student['firstname']) . " " . ucfirst($student['middlename']) . "."; ?></td>
        <td class="tg-za14">LRN</td>
        <td class="tg-za14"><?php echo $student['lrn_no']; ?></td>
      </tr>
      <tr>
        <td class="tg-za14" colspan="2">Grade &amp; Section</td>
        <td class="tg-za14" colspan="4"><?php echo $student['class']; ?> - <?php echo $student['section']; ?></td>
        <td class="tg-za14" colspan="2">SY&nbsp;&nbsp;&nbsp;2020 - 2021</td>
      </tr>
      <tr>
        <td class="tg-za14" colspan="2">Learning&nbsp;&nbsp;&nbsp;Areas</td>

        <?php foreach ($quarter_list as $row) : ?>

          <td class="tg-za14"><?php echo $row->description ?></td>

        <?php endforeach; ?>
        <td class="tg-za14">Final Rating</td>
        <td class="tg-za14">Action Taken</td>
      </tr>

      <?php foreach ($resultlist as $row) : ?>
        <?php
        $average = ($row->Q1 == 0 || $row->Q2 == 0 || $row->Q3 == 0 || $row->Q4 == 0) ? '' : $row->average;
        $final = ($row->Q1 == 0 || $row->Q2 == 0 || $row->Q3 == 0 || $row->Q4 == 0) ? '' : $row->final_grade;
        ?>
        <tr>
          <td class="tg-za14" colspan="2"><?php echo $row->Subjects ?></td>
          <td class="tg-pb0m"><?php echo ($row->Q1 == 0 ? '' : $row->Q1) ?></td>
          <td class="tg-pb0m"><?php echo ($row->Q2 == 0 ? '' : $row->Q2) ?></td>
          <td class="tg-pb0m"><?php echo ($row->Q3 == 0 ? '' : $row->Q3) ?></td>
          <td class="tg-pb0m"><?php echo ($row->Q4 == 0 ? '' : $row->Q4) ?></td>
          <td class="tg-pb0m"><?php echo ($final == 0 ? '' : $final) ?></td>
          <td class="tg-pb0m"><?php echo ($final == 0 ? "No Remarks" : ($final >= 75 ? "Passed" : "")) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if ($student['class_id'] == '14' || $student['class_id'] == '15') : ?>
        <tr>
          <td colspan="2" class="tg-7zrl">ACP</td>
          <td colspan="4" class="tg-center"></td>

          <td class="tg-center"><?php echo $student_attendance['acp'] ?></td>
          <td class="tg-center"></td>

        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <style type="text/css">
    .tg {
      border-collapse: collapse;
      border-spacing: 0;
    }

    .tg td {
      border-color: black;
      border-style: solid;
      border-width: 1px;
      font-family: Arial, sans-serif;
      font-size: 14px;
      overflow: hidden;
      padding: 10px 5px;
      word-break: normal;
    }

    .tg th {
      border-color: black;
      border-style: solid;
      border-width: 1px;
      font-family: Arial, sans-serif;
      font-size: 14px;
      font-weight: normal;
      overflow: hidden;
      padding: 10px 5px;
      word-break: normal;
    }

    .tg .tg-8d8j {
      text-align: center;
      vertical-align: bottom
    }

    .tg .tg-7zrl {
      text-align: left;
      vertical-align: bottom
    }
  </style>
  <table class="tg" width="100%">
    <thead>
      <tr>
        <th class="tg-8d8j" colspan="13">Report On Attendance</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="tg-7zrl"></td>
        <td class="tg-7zrl">Aug</td>
        <td class="tg-7zrl">Sep</td>
        <td class="tg-7zrl">Oct</td>
        <td class="tg-7zrl">Nov</td>
        <td class="tg-7zrl">Dec</td>
        <td class="tg-7zrl">Jan</td>
        <td class="tg-7zrl">Feb</td>
        <td class="tg-7zrl">Mar</td>
        <td class="tg-7zrl">Apr</td>
        <td class="tg-7zrl">Total</td>
      </tr>
      <tr>
        <td class="tg-7zrl">No. of School Days</td>
        <td class="tg-7zrl">21</td>
        <td class="tg-7zrl">26</td>
        <td class="tg-7zrl">26</td>
        <td class="tg-7zrl">23</td>
        <td class="tg-7zrl">15</td>
        <td class="tg-7zrl">24</td>
        <td class="tg-7zrl">22</td>
        <td class="tg-7zrl">27</td>
        <td class="tg-7zrl">22</td>
        <td class="tg-7zrl">206</td>
      </tr>
      <tr>
        <td class="tg-7zrl">No. of Days Present</td>
        <?php if ($student_attendance) : ?>
          <?php $attendance_total = 0; ?>
          <?php foreach (json_decode($student_attendance['attendance']) as $key => $value) : ?>
            <?php $attendance_total += $value; ?>
            <td class="tg-7zrl"><?php echo $value; ?></td>
          <?php endforeach; ?>
          <td class="tg-7zrl"><?php echo $attendance_total ?></td>
        <?php else : ?>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
        <?php endif; ?>

      </tr>
      <tr>
        <td class="tg-7zrl">No. of Days Absent</td>
        <?php if ($student_attendance) : ?>
          <?php $absent_total = 0; ?>
          <?php foreach (json_decode($student_attendance['absent']) as $key => $value) : ?>
            <?php $absent_total += $value; ?>
            <td class="tg-7zrl"><?php echo $value; ?></td>
          <?php endforeach; ?>
          <td class="tg-7zrl"><?php echo $absent_total ?></td>
        <?php else : ?>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
        <?php endif; ?>
      </tr>
      <tr>
        <td class="tg-7zrl">No. of Times Tardy</td>
        <?php if ($student_attendance) : ?>
          <?php $tardy_total = 0; ?>
          <?php foreach (json_decode($student_attendance['tardy']) as $key => $value) : ?>
            <?php $tardy_total += $value; ?>
            <td class="tg-7zrl"><?php echo $value; ?></td>
          <?php endforeach; ?>
          <td class="tg-7zrl"><?php echo $tardy_total ?></td>
        <?php else : ?>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
          <td class="tg-7zrl">0</td>
        <?php endif; ?>
      </tr>
    </tbody>
  </table>
  <center>
    <hr>
  </center>
  <center>
    <p>Certificate of Transfer</p>
  </center>
  <table width="100%">
    <tr>
      <td colspan="2">Egligible for transfer and admission to </td>
      <td>______________________________________</td>

      <td><span>Date</span><span>__________</span></td>
    </tr>
    <tr>
      <td colspan="2" style="padding-top: 25px">
        <center>____________________________</center>
      </td>
      <td colspan="2" style="padding-top: 25px">
        <center>____________________________</center>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <center>Principal</center>
      </td>
      <td colspan="2">
        <center>Registrar</center>
      </td>
    </tr>
  </table>
  <footer></footer>
  <style type="text/css">
    .tg {
      border-collapse: collapse;
      border-spacing: 0;
    }

    .tg td {
      border-color: black;
      border-style: solid;
      border-width: 1px;
      font-family: Arial, sans-serif;
      font-size: 14px;
      overflow: hidden;
      padding: 10px 5px;
      word-break: normal;
    }

    .tg th {
      border-color: black;
      border-style: solid;
      border-width: 1px;
      font-family: Arial, sans-serif;
      font-size: 14px;
      font-weight: normal;
      overflow: hidden;
      padding: 10px 5px;
      word-break: normal;
    }

    .tg .tg-8d8j {
      text-align: center;
      vertical-align: bottom
    }

    .tg .tg-7zrl {
      text-align: left;
      vertical-align: bottom
    }
  </style>
  <table width="100%" class="tg">
    <thead>
      <tr>
        <th class="tg-8d8j" colspan="7">Conduct Grades</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="tg-7zrl">Indicator ID</td>
        <td class="tg-7zrl">DepEd Indicator</td>
        <td class="tg-7zrl">Indicator</td>
        <td class="tg-7zrl">1st Qtr</td>
        <td class="tg-7zrl">2nd Qtr</td>
        <td class="tg-7zrl">3rd Qtr</td>
        <td class="tg-7zrl">4rth Qtr</td>
      </tr>
      <?php foreach ($student_conduct as $row) : ?>
        <tr>
          <td class="tg-7zrl"><?php echo $row->id; ?></td>
          <td class="tg-7zrl"><?php echo $row->deped_indicators; ?></td>
          <td class="tg-7zrl"><?php echo $row->indicators; ?></td>
          <td class="tg-7zrl"><?php echo $row->first_quarter; ?></td>
          <td class="tg-7zrl"><?php echo $row->second_quarter; ?></td>
          <td class="tg-7zrl"><?php echo $row->third_quarter; ?></td>
          <td class="tg-7zrl"><?php echo $row->fourth_quarter; ?></td>
        </tr>
      <?php endforeach; ?>


    </tbody>
  </table>
  <footer></footer>
<?php endforeach; ?>
<script type="text/javascript">
  window.onload = function() {
    window.print();
  }
</script>