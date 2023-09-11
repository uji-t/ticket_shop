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
<link rel="stylesheet" href="../view/html.css">
<meta charset="UTF-8">
<title>チケット詳細ページ</title>
</head>
<body>

  <h1>チケット詳細ページ</h1>

<?php

require_once('../common/common.php');


$post=sanitize($_POST);
$ticket_id=$_GET['ticket_id'];
$account_id=$_SESSION['account_id'];

/***************************データベース処理**************************************** */
/********************************************************************************* */
$dsn = 'mysql:dbname=ticket_shop;host=localhost;charset=utf8';
$user = 'root';
$password = '';
$dbh = new PDO($dsn,$user,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


$sql = 'SELECT 
            tickets.ticket_name,
            tickets.ticket_memo,
            ticket_days.day_start1,
            ticket_days.day_start2,
            ticket_days.day_start3,
            ticket_days.day_start4,
            ticket_days.day_start5,
            ticket_days.day_start6,
            ticket_days.day_start7,
            ticket_days.day_start8,
            places.place_name,
            places.place_address,
            sheets.sheet_name,
            sheets.sheet_name2,
            sheets.sheet_name3,
            sheets.sheet_quantity,
            sheets.sheet_quantity2,
            sheets.sheet_quantity3,
            sheets.price,
            sheets.price2,
            sheets.price3,
            ticket_times.time_start1,
            ticket_times.time_start2,
            ticket_times.time_start3,
            ticket_times.time_start4,
            ticket_times.time_start5,
            ticket_times.time_start6,
            ticket_times.time_start7,
            ticket_times.time_start8,
            ticket_times.time_end1,
            ticket_times.time_end2,
            ticket_times.time_end3,
            ticket_times.time_end4,
            ticket_times.time_end5,
            ticket_times.time_end6,
            ticket_times.time_end7,
            ticket_times.time_end8,
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

$dbh = null;

$rec = $stmt->fetch(PDO::FETCH_ASSOC);

/********************************************************************************* */
/********************************************************************************* */
//変数
$ticket_name =$rec['ticket_name'];
$ticket_memo =$rec['ticket_memo'];
$place_name =$rec['place_name'];
$place_address =$rec['place_address'];
$sheet_name = array();
$sheet_name[] =$rec['sheet_name'];
$sheet_name[] =$rec['sheet_name2'];
$sheet_name[] =$rec['sheet_name3'];
$sheet_quantity = array();
$sheet_quantity[] =$rec['sheet_quantity'];
$sheet_quantity[] =$rec['sheet_quantity2'];
$sheet_quantity[] =$rec['sheet_quantity3'];
$price = array();
$price[] =$rec['price'];
$price[] =$rec['price2'];
$price[] =$rec['price3'];
$ticket_photo =$rec['ticket_photo'];

for ($i = 1; $i <= 8; $i++) {
  $days_start[] = $rec['day_start'.$i];
  if ($days_start[$i-1] <= '1111-11-11') {
    array_pop($days_start);
    break;
  }
}

$times_start = array();
$times_end = array();
for ($i = 1; $i <= 8; $i++) {
  $times_start[] = $rec['time_start' . $i];
  $times_end[] = $rec['time_end' . $i];
}
$combined_times = array();

for ($i = 0; $i < count($times_start); $i++) {
  $combined_times[] = $times_start[$i].'～'.$times_end[$i];
}

/********************************************************************************* */
/********************************************************************************* */
print '<img src="./photo/'.$ticket_photo.'">';
print '<h2>公演情報</h2>';
print '<h3>公演名</h3>';
print $ticket_name;
print '<h3>公演情報</h3>';
print $ticket_memo;
print '<h3>会場</h3>';
print $place_name;
print '<h3>住所</h3>';
print $place_address;
print '<h3>開催期間</h3>';
print '<table>';
print '<th>開催日</th><th>公演時間</th>';
for($i=0;$i<count($days_start);$i++)
{
  print '<tr>';
  print '<td>'.$days_start[$i].'</td>';
  print '<td>'.$combined_times[$i].'</td>';
  print '</tr>';
  print '</table>';
  print '<br/>';
}

print '<h3>シート詳細</h3>';
print '<table>';
print '<th>シート名</th><th>価格</th><th>座席数</th>';
for($i=0;$i<count($sheet_name);$i++)
{
  if($sheet_name[$i]=='')
  {
    break;
  }
  else
  {
    print '<tr>';
    print '<td>'.$sheet_name[$i].'</td>';
    print '<td>'.$price[$i].'円</td>';
    print '<td style="text-align: right;">' .$sheet_quantity[$i].'席</td>';
    print '</tr>';
  }
}
print '</table>';
print '<br/>';
print '<br/>';


  print '<form method="post" action="../book/book_form.php">';
  print '<input type="hidden" name="account_id" value="'.$account_id.'">';
  print '<input type="hidden" name="ticket_id" value="'.$ticket_id.'">';
  print '<input type="button" onclick="history.back()" value="戻る">';
  print '&nbsp';
  print '<input type="submit" value="予約する"><br/>';
  print '</form>';
  print '</form>';
?>

</body>
</html>