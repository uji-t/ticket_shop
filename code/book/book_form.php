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
<title>チケット登録ページ</title>
</head>
<body>

<h1>チケット予約ページ</h1>

<?php

require_once('../common/common.php');

$post=sanitize($_POST);
$ticket_id=$post['ticket_id'];
$account_id=$_SESSION['account_id'];


$dsn = 'mysql:dbname=ticket_shop;host=localhost;charset=utf8';
$user = 'root';
$password = '';
$dbh = new PDO($dsn,$user,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


$sql  = ' SELECT 
            tickets.ticket_id,
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
            ticket_days.day_start9,
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
            sheets.price3
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
          WHERE tickets.ticket_id=?;';
$stmt = $dbh->prepare($sql);
$data = array();
$data[] = $ticket_id;
$stmt->execute($data);

$dbh = null;

$rec = $stmt->fetch(PDO::FETCH_ASSOC);

/************************************************************************* */
print '<h2>公演情報</h2>';
print '<h3>公演名</h3>';
print $rec['ticket_name'];
print '<br>';
print '<h3>会場</h3>';
print $rec['place_name'];
print '<br>';
print '<h3>開催日時</h3>';
print '<form method="post" action="book_form_check.php">';

$days_start = array();
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

print '<table>';
print '<th></th><th>開催日</th><th>公演時間</th>';
for ($i = 0; $i < count($days_start); $i++) {
  $select_date_code = $i;
  $day_start_value = $days_start[$i];
  $time_combined_value = $combined_times[$i];
  $time_start_value = $times_start[$i];
  $time_end_value = $times_end[$i];
  print '<tr>
          <td><input type="radio" name="selected_date_code" value="'.$select_date_code.'"></td>
          <td><input type="hidden" name="selected_date_'.$i.'" value="'.$day_start_value.'">'.$day_start_value.'</td>
          <td><input type="hidden" name="selected_combined_time_'.$i.'" value="'.$time_combined_value.'">'.$time_combined_value.'</td>
          <td><input type="hidden" name="selected_start_time_'.$i.'" value="'.$time_start_value.'"></td>
          <td><input type="hidden" name="selected_end_time_'.$i.'" value="'.$time_end_value.'"></td>
        </tr>';
}
print '</table>';

$sheets_name = array();
$sheets_name[] = $rec['sheet_name'];
$sheets_name[] = $rec['sheet_name2'];
$sheets_name[] = $rec['sheet_name3'];

for ($i = count($sheets_name) - 1; $i >= 0; $i--) {
  if ($sheets_name[$i] == '') {
    array_pop($sheets_name);
  } else {
    break;
  }
}

$sheets_price = array();
$sheets_price[] = $rec['price'];
$sheets_price[] = $rec['price2'];
$sheets_price[] = $rec['price3'];

print '<h3>シート詳細</h3>';
print "<table>
        <tr><th></th><th>シート名</th><th>価格</th><th>予約チケット枚数</th></tr>";
for ($i = 0; $i < count($sheets_name); $i++) {
  $select_sheet_code = $i;
  $sheet_name_value = $sheets_name[$i];
  $sheet_price_value = $sheets_price[$i];

  print '<tr>
          <td><input type="radio" name="selected_sheet_code" value="'.$select_sheet_code.'"></td>
          <td><input type="hidden" name="selected_sheet_'.$i.'" value="'.$sheet_name_value.'">'.$sheet_name_value.'</td>
          <td><input type="hidden" name="selected_price_'.$i.'" value="'.$sheet_price_value.'">'.$sheet_price_value.'円</td>
          <td><input type="number" name="booking_quantity_'.$i.'"></td>
        </tr>';

}
print '</table>';
print '<br>';
print '<input type="hidden" name="account_id" value="'.$account_id.'">';
print '<input type="hidden" name="ticket_id" value="'.$ticket_id.'">';
print '<input type="button" onclick="history.back()" value="戻る">';
print '&nbsp';
print '<input type="submit" value="次へ">';

print '</form>';
?>

</body>
</html>

