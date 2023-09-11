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
<title>チケット登録</title>
</head>
<body>

<?php

try{
  require_once('../common/common.php');

  $post=sanitize($_POST);

  $ticket_name=$post['ticket_name'];
  $ticket_memo=$post['ticket_memo'];
  $day_start=$post['day_start'];
  $time_start=$post['time_start'];
  $time_end=$post['time_end'];
  $place_name=$post['place_name'];
  $place_address=$post['place_address'];
  $sheet_name=$post['sheet_name'];
  $sheet_quantity=$post['sheet_quantity'];
  $price=$post['price'];
  $ticket_photo=$post['ticket_photo'];

  /******文字列に変換された変数を再度配列に変換する*******/

  $days_start = explode(',',$day_start);
  $times_start = explode(',',$time_start);
  $times_end = explode(',',$time_end);
  $sheets_name = explode(',',$sheet_name);
  $sheets_quantity = explode(',',$sheet_quantity);
  $prices = explode(',',$price);

  /************************************************** */

  /******空白行の検出とデフォルト値の設定**************/

  for($i = 0;$i < count($days_start);$i++)
  {
    if(empty($days_start[$i]))
    {
      $days_start[$i] = null;
    }
  }
  for($i = 0;$i < count($times_start);$i++)
  {
    if(empty($times_start[$i]))
    {
      $times_start[$i] = null;
    }
  }
  for($i = 0;$i < count($times_end);$i++)
  {
    if(empty($times_end[$i]))
    {
      $times_end[$i] = null;
    }
  }
  for($i = 0;$i < count($sheets_name);$i++)
  {
    if(empty($sheets_name[$i]))
    {
      $sheets_name[$i] = null;
    }
  }
  for($i = 0;$i < count($sheets_quantity);$i++)
  {
    if(empty($sheets_quantity[$i]))
    {
      $sheets_quantity[$i] = null;
    }
  }
  for($i = 0;$i < count($prices);$i++)
  {
    if(empty($prices[$i]))
    {
      $prices[$i] = null;
    }
  }
  /******配列を分解して変数に代入する********************/

  $day_start1 =$days_start[0];
  $day_start2 =$days_start[1];
  $day_start3 =$days_start[2];
  $day_start4 =$days_start[3];
  $day_start5 =$days_start[4];
  $day_start6 =$days_start[5];
  $day_start7 =$days_start[6];
  $day_start8 =$days_start[7];
  $day_start9 =$days_start[8];

  $time_start1 =$times_start[0];
  $time_start2 =$times_start[1];
  $time_start3 =$times_start[2];
  $time_start4 =$times_start[3];
  $time_start5 =$times_start[4];
  $time_start6 =$times_start[5];
  $time_start7 =$times_start[6];
  $time_start8 =$times_start[7];
  $time_start9 =$times_start[8];

  $time_end1 =$times_end[0];
  $time_end2 =$times_end[1];
  $time_end3 =$times_end[2];
  $time_end4 =$times_end[3];
  $time_end5 =$times_end[4];
  $time_end6 =$times_end[5];
  $time_end7 =$times_end[6];
  $time_end8 =$times_end[7];
  $time_end9 =$times_end[8];

  $sheet_name =$sheets_name[0];
  $sheet_name2 =$sheets_name[1];
  $sheet_name3 =$sheets_name[2];

  $sheet_quantity =$sheets_quantity[0];
  $sheet_quantity2 =$sheets_quantity[1];
  $sheet_quantity3 =$sheets_quantity[2];

  $price = $prices[0];
  $price2 = $prices[1];
  $price3 = $prices[2];

  /************************************************** */

  /****************************************** */


    print 'チケットを登録しました。チケット内容を変更・キャンセルしたい場合はマイページか下記のリンクよりお願いします<br/><br/>';
    
  /****************************************** */
  /***************データベース処理************ */
  /****************************************** */

  //データベースの接続
    $dsn ='mysql:dbname=ticket_shop;host=localhost;charset=utf8';
    $user ='root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    /*
    //テーブルのロック
    $sql = 'LOCK TABLES tickets WRITE, ticket_days WRITE, places WRITE,sheets WRITE';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
*/

    //シートテーブルにフォームの登録

    $sql = 'INSERT INTO sheets(sheet_name,sheet_name2,sheet_name3,sheet_quantity,sheet_quantity2,sheet_quantity3,sheet_stock1,sheet_stock2,sheet_stock3,price,price2,price3) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $sheet_name);
    $stmt->bindValue(2, $sheet_name2);
    $stmt->bindValue(3, $sheet_name3);
    $stmt->bindValue(4, $sheet_quantity);
    $stmt->bindValue(5, $sheet_quantity2);
    $stmt->bindValue(6, $sheet_quantity3);
    $stmt->bindValue(7, $sheet_quantity);
    $stmt->bindValue(8, $sheet_quantity2);
    $stmt->bindValue(9, $sheet_quantity3);
    $stmt->bindValue(10, $price);
    $stmt->bindValue(11, $price2);
    $stmt->bindValue(12, $price3);
    $stmt->execute();

    $lastSheetId = $dbh->lastInsertId(); //シートテーブルIDの取得


    //会場テーブルにフォームの登録

    $sql = 'INSERT INTO places(sheet_id,place_name,place_address) VALUES(?,?,?)';
    $stmt = $dbh->prepare($sql);
    $data = array();
    $data[] = $lastSheetId;
    $data[] = $place_name;
    $data[] = $place_address;
    $stmt->execute($data);

    $lastPlaceId = $dbh->lastInsertId();//会場テーブルIDの取得  


    //チケットテーブルにフォームの登録
    
    $sql = 'INSERT INTO tickets(place_id,ticket_name,ticket_memo,ticket_photo)VALUES(?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $data = array();
    $data[] = $lastPlaceId;
    $data[] = $ticket_name;
    $data[] = $ticket_memo;
    $data[] = $ticket_photo;
    $stmt->execute($data);

    $lastTicketId = $dbh->lastInsertId();//チケットテーブルIDの取得

    //チケット日付テーブルにフォームの登録
    
    $sql = 'INSERT INTO ticket_days(day_start1,day_start2,day_start3,day_start4,day_start5,day_start6,day_start7,day_start8,day_start9) VALUES(?,?,?,?,?,?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $day_start1);
    $stmt->bindValue(2, $day_start2);
    $stmt->bindValue(3, $day_start3);
    $stmt->bindValue(4, $day_start4);
    $stmt->bindValue(5, $day_start5);
    $stmt->bindValue(6, $day_start6);
    $stmt->bindValue(7, $day_start7);
    $stmt->bindValue(8, $day_start8);
    $stmt->bindValue(9, $day_start9);
    $stmt->execute();

    $lastDayId = $dbh->lastInsertId();//チケット日付テーブルIDの取得


    //チケット時間テーブルにフォームの登録

    $sql = 'INSERT INTO ticket_times(time_start1,time_start2,time_start3,time_start4,time_start5,time_start6,time_start7,time_start8,time_start9,time_end1,time_end2,time_end3,time_end4,time_end5,time_end6,time_end7,time_end8,time_end9) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $time_start1);
    $stmt->bindValue(2, $time_start2);
    $stmt->bindValue(3, $time_start3);
    $stmt->bindValue(4, $time_start4);
    $stmt->bindValue(5, $time_start5);
    $stmt->bindValue(6, $time_start6);
    $stmt->bindValue(7, $time_start7);
    $stmt->bindValue(8, $time_start8);
    $stmt->bindValue(9, $time_start9);
    $stmt->bindValue(10, $time_end1);
    $stmt->bindValue(11, $time_end2);
    $stmt->bindValue(12, $time_end3);
    $stmt->bindValue(13, $time_end4);
    $stmt->bindValue(14, $time_end5);
    $stmt->bindValue(15, $time_end6);
    $stmt->bindValue(16, $time_end7);
    $stmt->bindValue(17, $time_end8);
    $stmt->bindValue(18, $time_end9);
    $stmt->execute();
    $lastTimeId = $dbh->lastInsertId();//チケット時間テーブルIDの取得

    //中間テーブル(days,times)に外部キーの値を登録

    $sql = 'INSERT INTO chukan_days_times(ticket_day_id,ticket_time_id)VALUES(?,?)';
    $stmt = $dbh->prepare($sql);
    $data = array();
    $data[] = $lastDayId;
    $data[] = $lastTimeId;
    $stmt->execute($data);

    //中間テーブル(tickets,days)に外部キーの値を登録

    $sql = 'INSERT INTO chukan_tickets_days(ticket_id,ticket_day_id)VALUES(?,?)';
    $stmt = $dbh->prepare($sql);
    $data = array();
    $data[] = $lastTicketId;
    $data[] = $lastDayId;
    $stmt->execute($data);

    //中間テーブル(tickets,times)に外部キーの値を登録
    
    $sql = 'INSERT INTO chukan_tickets_times(ticket_id,ticket_time_id)VALUES(?,?)';
    $stmt = $dbh->prepare($sql);
    $data = array();
    $data[] = $lastTicketId;
    $data[] = $lastTimeId;
    $stmt->execute($data);


/*
    $sql = 'UNLOCK TABLES';
    $stmt =$dbh->prepare($sql);
    $stmt->execute();
*/
    $dbh = null;
    /*　***************************************** 　*/
    /*　*****************************************　 */
    /*　***************************************** 　*/

}catch(Exception $e)
{
  echo 'エラーメッセージ: ' . $e->getMessage() . '<br>';
  echo 'ファイル名: ' . $e->getFile() . '<br>';
  echo '行番号: ' . $e->getLine() . '<br>';
  print 'ただいま障害により大変ご迷惑をおかけしております。<br/>';
  print '<br/>';
}

?>

<br/>
<a href="../view/index.php">トップページへ</a>

</body>
</html>