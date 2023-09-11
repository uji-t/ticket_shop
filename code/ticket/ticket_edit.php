<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false)
{
  print 'ログインされていません。<br/>';
  print '<a href="../login/account_login.html">ログイン画面へ</a>';
  exit();
}
else
{
  print $_SESSION['account_name1'];
  print 'さんログイン中<br/>';
  print '<br/>';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../view/html.css">
<title>チケット修正</title>
</head>
<body>

<?php

try
{
  $ticket_id = $_GET['ticket_id'];

/******************データベース処理**************************** */
/************************************************************* */

  $dsn = 'mysql:dbname=ticket_shop;host=localhost;charset=utf8';
  $user = 'root';
  $password = '';
  $dbh = new PDO($dsn,$user,$password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

  $sql = 'SELECT 
              tickets.ticket_id,
              tickets.ticket_name,
              tickets.ticket_memo,
              ticket_days.ticket_day_id,
              ticket_days.day_start1,
              ticket_days.day_start2,
              ticket_days.day_start3,
              ticket_days.day_start4,
              ticket_days.day_start5,
              ticket_days.day_start6,
              ticket_days.day_start7,
              ticket_days.day_start8,
              ticket_days.day_start9,
              ticket_times.ticket_time_id,
              ticket_times.time_start1,
              ticket_times.time_start2,
              ticket_times.time_start3,
              ticket_times.time_start4,
              ticket_times.time_start5,
              ticket_times.time_start6,
              ticket_times.time_start7,
              ticket_times.time_start8,
              ticket_times.time_start9,
              ticket_times.time_end1,
              ticket_times.time_end2,
              ticket_times.time_end3,
              ticket_times.time_end4,
              ticket_times.time_end5,
              ticket_times.time_end6,
              ticket_times.time_end7,
              ticket_times.time_end8,
              ticket_times.time_end9,
              places.place_id,
              places.place_name,
              places.place_address,
              sheets.sheet_id,
              sheets.sheet_name,
              sheets.sheet_name2,
              sheets.sheet_name3,
              sheets.sheet_quantity,
              sheets.sheet_quantity2,
              sheets.sheet_quantity3,
              sheets.price,
              sheets.price2,
              sheets.price3,
              tickets.ticket_photo
          FROM `tickets`
          JOIN places
            ON places.place_id = tickets.place_id
          JOIN sheets
            ON sheets.sheet_id = places.sheet_id
          JOIN chukan_tickets_days
            ON tickets.ticket_id = chukan_tickets_days.ticket_id
          JOIN ticket_days
            ON chukan_tickets_days.ticket_day_id = ticket_days.ticket_day_id
          JOIN chukan_tickets_times
            ON tickets.ticket_id = chukan_tickets_times.ticket_id
          JOIN chukan_days_times
            ON chukan_days_times.ticket_time_id = chukan_tickets_times.ticket_time_id
          JOIN ticket_times
            ON ticket_times.ticket_time_id = chukan_days_times.ticket_time_id
          WHERE tickets.ticket_id=?';

  $stmt = $dbh->prepare($sql);
  $data[] = $ticket_id;
  $stmt->execute($data);

  $rec = $stmt->fetch(PDO::FETCH_ASSOC);

  $ticket_name = $rec['ticket_name'];
  $ticket_memo = $rec['ticket_memo'];

  $ticket_day_id = $rec['ticket_day_id'];
  $days_start = array();
  $days_start[] = $rec['day_start1'];
  $days_start[] = $rec['day_start2'];
  $days_start[] = $rec['day_start3'];
  $days_start[] = $rec['day_start4'];
  $days_start[] = $rec['day_start5'];
  $days_start[] = $rec['day_start6'];
  $days_start[] = $rec['day_start7'];
  $days_start[] = $rec['day_start8'];
  $days_start[] = $rec['day_start9'];

  $ticket_time_id = $rec['ticket_time_id'];
  $times_start = array();
  $times_start[] = $rec['time_start1'];
  $times_start[] = $rec['time_start2'];
  $times_start[] = $rec['time_start3'];
  $times_start[] = $rec['time_start4'];
  $times_start[] = $rec['time_start5'];
  $times_start[] = $rec['time_start6'];
  $times_start[] = $rec['time_start7'];
  $times_start[] = $rec['time_start8'];
  $times_start[] = $rec['time_start9'];

  $times_end = array();
  $times_end[] = $rec['time_end1'];
  $times_end[] = $rec['time_end2'];
  $times_end[] = $rec['time_end3'];
  $times_end[] = $rec['time_end4'];
  $times_end[] = $rec['time_end5'];
  $times_end[] = $rec['time_end6'];
  $times_end[] = $rec['time_end7'];
  $times_end[] = $rec['time_end8'];
  $times_end[] = $rec['time_end9'];

  $place_id = $rec['place_id'];
  $place_name = $rec['place_name'];
  $place_address = $rec['place_address'];

  $sheet_id = $rec['sheet_id'];
  $sheets_name = array();
  $sheets_name[] = $rec['sheet_name'];
  $sheets_name[] = $rec['sheet_name2'];
  $sheets_name[] = $rec['sheet_name3'];

  $sheets_quantity = array();
  $sheets_quantity[] = $rec['sheet_quantity'];
  $sheets_quantity[] = $rec['sheet_quantity2'];
  $sheets_quantity[] = $rec['sheet_quantity3'];

  $prices = array();
  $prices[] = $rec['price'];
  $prices[] = $rec['price2'];
  $prices[] = $rec['price3'];

  $ticket_photo_name_old = $rec['ticket_photo'];
  $dbh = null;


/************************************************************* */
/************************************************************* */

}catch(Exception $e){
  print 'ただいま障害により大変ご迷惑をおかけしております';
  exit();
}

?>

<h1>チケット修正</h1>
<br/>
<br/>

<form method="post" action="ticket_edit_check.php"enctype="multipart/form-data">
<!--=============================各種ID=============================-->
<!--================================================================-->

  <input type="hidden" name="ticket_id" value="<?php print $ticket_id?>">
  <input type="hidden" name="ticket_day_id" value="<?php print $ticket_day_id?>">
  <input type="hidden" name="ticket_time_id" value="<?php print $ticket_time_id?>">
  <input type="hidden" name="place_id" value="<?php print $place_id?>">
  <input type="hidden" name="sheet_id" value="<?php print $sheet_id?>">

<!--================================================================-->
<!--================================================================-->

  <h2>公演名</h2>
  <br/>
  <input type="text" name="ticket_name" style="width:200px" value="<?php print $ticket_name?>"><br/>

  <h2>公演紹介文</h2>
  <input type="text" name="ticket_memo" style="width:200px" value="<?php print $ticket_memo?>"><br/>

  <h2>日程</h2>
  <table>
    <th>開催日</th>
    <th>開始時間</th>
    <th>終了時間</th>
      <tr>
        <td>  
          <?php
            for($i = 0; $i < count($days_start);$i++)
            {
                print $i+1 .":";
                print '<input type="text" name="days_start[]" style="width:200px" value="'.$days_start[$i].'">';
                print '<br/>';
            }
          ?>
        </td>
        <td>
          <?php
            for($i = 0; $i < count($days_start);$i++)
            {
                print '<input type="text" name="times_start[]" style="width:200px" value="'.$times_start[$i].'">';
                print '<br/>';
            }
          ?>
        </td>
        <td>
            <?php
            for($i = 0; $i < count($times_end);$i++)
            {
                print '<input type="text" name="times_end[]" style="width:200px" value="'.$times_end[$i].'">';
                print '<br/>';
            }
          ?>
        </td>
      </tr>
  </table>

  <h2>会場名</h2>
  <input type="text" name="place_name" style="width:200px" value="<?php print $place_name?>"><br/>

  <h2>住所</h2>
  <input type="text" name="place_address" style="width:200px" value="<?php print $place_address?>"><br/>
  
  <h2>シート設定</h2>
  <table>
    <th>シート名</th>
    <th>シート数</th>
    <th>金額</th>
      <tr>
        <td>
          <?php
            for($i = 0; $i < count($sheets_name);$i++)
            {
                print $i+1 .":";
                print '<input type="text" name="sheets_name[]" style="width:200px" value="'.$sheets_name[$i].'">';
                print '<br/>';
            }
          ?>
        </td>
        <td>
          <?php
            for($i = 0; $i < count($sheets_name);$i++)
            {
                print '<input type="text" name="sheets_quantity[]" style="width:200px" value="'.$sheets_quantity[$i].'">';
                print '<br/>';
            }
          ?>
        </td>
        <td>
          <?php
            for($i = 0; $i < count($sheets_name);$i++)
            {
                print '<input type="text" name="prices[]" style="width:200px" value="'.$prices[$i].'">';
                print '<br/>';
            }
          ?>
        </td>
      </tr>
  </table>
  
  <h2>チケット写真</h2>
  <?php
    if($ticket_photo_name_old == "") 
    {
      $ticket_photo = "";
    } 
    else 
    {
      $ticket_photo = print'<img src="./photo/'.$ticket_photo_name_old.'">';
      print '<br/>';
    }
  ?>
  <br/>
  <input type="hidden" name="ticket_photo_name_old" value="<?php print $ticket_photo_name_old;?>"> 
  <input type="file" name="ticket_photo" style="width:400px" value="<?php print $ticket_photo;?>">
  <br/>
  <br/>


  <input type="button" onclick="history.back()" value="戻る">
  <input type="submit" value="OK">
</form>


</body>
</html>