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
<title>登録チケット一覧</title>
</head>
<body>

<?php

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
                ticket_days.day_start1,
                places.place_name
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
            LIMIT 0, 25;';

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $dbh = null;

/************************************************************* */
/************************************************************* */

    print '<h1>登録チケット一覧</h1><br/>';
    

    print '<form method="post" action="ticket_branch.php">';

    while(true)
    {
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      if($rec==false)
      {
        break;
      }
      print '<table border="1">
              <th></th>
              <th>公演名</th>
              <th>公演紹介文</th>
              <th>開催日</th>
              <th>会場名</th>
                <tr>
                  <td><input type="radio" name="ticket_id" value="'.$rec['ticket_id'].'"></td>
                  <td>'.$rec['ticket_name'].'</td>
                  <td>'.$rec['ticket_memo'].'</td>
                  <td>'.$rec['day_start1'].'</td>
                  <td>'.$rec['place_name'].'</td>
                </tr>
            </table>';
      print '<br/>';
    }
  print '<input type="submit" name="disp" value="参照">';
  print '<input type="submit" name="add" value="追加">';
  print '<input type="submit" name="edit" value="修正">';
  print '<input type="submit" name="delete" value="削除">';
  print '</form>';

  ?>

<br/>
<a href="../view/index.php">トップメニューへ</a><br/>


</body>
</html>