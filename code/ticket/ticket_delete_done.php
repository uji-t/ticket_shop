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
<title>削除ページ</title>
</head>
<body>

<?php

  try{
    $ticket_id = $_POST['ticket_id'];
    $ticket_day_id = $_POST['ticket_day_id'];
    $ticket_time_id = $_POST['ticket_time_id'];
    $place_id = $_POST['place_id'];
    $sheet_id = $_POST['sheet_id'];
    $chukanA_id = $_POST['chukanA_id'];
    $chukanB_id = $_POST['chukanB_id'];
    $chukanC_id = $_POST['chukanC_id'];
    $ticket_photo = $_POST['ticket_photo'];

  /*******************データベース処理************************************** */
  /*********************************************************************** */

    $dsn = 'mysql:dbname=ticket_shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $sql = 'BEGIN;
              DELETE FROM chukan_tickets_days WHERE chukanA_id=?;
              DELETE FROM chukan_tickets_times WHERE chukanB_id=?;
              DELETE FROM chukan_days_times WHERE chukanC_id=?;
              DELETE FROM tickets WHERE ticket_id=?;
              DELETE FROM places WHERE place_id=?;
              DELETE FROM sheets WHERE sheet_id=?;
              DELETE FROM ticket_days WHERE ticket_day_id=?;
              DELETE FROM ticket_times WHERE ticket_time_id=?;
              SELECT *  FROM tickets;
            COMMIT;';
    $stmt = $dbh->prepare($sql);
    $data[] = $chukanA_id;
    $data[] = $chukanB_id;
    $data[] = $chukanC_id;
    $data[] = $ticket_id;
    $data[] = $place_id;
    $data[] = $sheet_id;
    $data[] = $ticket_day_id;
    $data[] = $ticket_time_id;
    $stmt->execute($data);

    $dbh = null;

  /*********************************************************************** */

    if($ticket_photo != '')
    {
      unlink('./photo/'.$ticket_photo);
    }

  }catch(Exception $e)
  {
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    exit();
  }

  ?>

  削除しました。<br/>
  <br/>
  <a href="../view/index.php">戻る</a>

</body>
</html>