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
<title>削除ページ</title>
</head>
<body>

<?php

try
{
  $ticket_id = $_GET['ticket_id'];

  /*******************データベース処理************************************** */
  /*********************************************************************** */
  $dsn = 'mysql:dbname=ticket_shop;host=localhost;charset=utf8';
  $user = 'root';
  $password = '';
  $dbh = new PDO($dsn,$user,$password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

  $sql = 'SELECT 
              tickets.ticket_id,
              tickets.ticket_name,
              tickets.ticket_memo,
              tickets.ticket_photo,
              ticket_days.ticket_day_id,
              ticket_times.ticket_time_id,
              places.place_id,
              sheets.sheet_id,
              chukan_tickets_days.chukanA_id,
              chukan_tickets_times.chukanB_id,
              chukan_days_times.chukanC_id
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
  $ticket_id = $rec['ticket_id'];
  $ticket_name = $rec['ticket_name'];
  $ticket_memo = $rec['ticket_memo'];
  $ticket_day_id = $rec['ticket_day_id'];
  $ticket_time_id = $rec['ticket_time_id'];
  $place_id = $rec['place_id'];
  $sheet_id = $rec['sheet_id'];
  $chukanA_id = $rec['chukanA_id'];
  $chukanB_id = $rec['chukanB_id'];
  $chukanC_id = $rec['chukanC_id'];
  $ticket_photo = $rec['ticket_photo'];

  $dbh = null;

  /*********************************************************************** */
  /*********************************************************************** */  

}catch(Exception $e){
  print 'ただいま障害により大変ご迷惑をおかけしております';
  exit();
}

?>

登録チケット削除<br/>
<br/>
チケットID<br/>
<?php print $ticket_id;?>
<br/>
公演名</br>
<?php print $ticket_name;?>
<br/>
<?php print $ticket_memo;?>
<br/>

このチケットを削除してよろしいですか?<br/>
<form method="post" action="ticket_delete_done.php">
<input type="hidden" name="ticket_id" value="<?php print $ticket_id?>">
<input type="hidden" name="ticket_day_id" value="<?php print $ticket_day_id?>">
<input type="hidden" name="ticket_time_id" value="<?php print $ticket_time_id?>">
<input type="hidden" name="place_id" value="<?php print $place_id?>">
<input type="hidden" name="sheet_id" value="<?php print $sheet_id?>">
<input type="hidden" name="chukanA_id" value="<?php print $chukanA_id?>">
<input type="hidden" name="chukanB_id" value="<?php print $chukanB_id?>">
<input type="hidden" name="chukanC_id" value="<?php print $chukanC_id?>">
<input type="hidden" name="ticket_photo" value="<?php print $ticket_photo;?>">
<input type="button" onclick="history.back()" value="戻る">
<input type="submit" value="OK">
</form>


</body>
</html>