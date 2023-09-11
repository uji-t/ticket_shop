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

  $ticket_id=$post['ticket_id'];
  $ticket_day_id=$post['ticket_day_id'];
  $ticket_time_id=$post['ticket_time_id'];
  $place_id=$post['place_id'];
  $sheet_id=$post['sheet_id'];

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

  $ticket_photo_name_old=$post['ticket_photo_name_old'];
  $ticket_photo=$post['ticket_photo'];


  /****************文字列→配列******************************/
  /********************************************************/

  $days_start = explode(',',$day_start);
  $times_start = explode(',',$time_start);
  $times_end = explode(',',$time_end);
  $sheets_name = explode(',',$sheet_name);
  $sheets_quantity = explode(',',$sheet_quantity);
  $prices = explode(',',$price);

  /************************************************** */
  /******************配列→変数****************************/

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

    print 'チケットを内容を修正しました。';
    
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

    //シートテーブルの修正

    $sql = 'UPDATE sheets SET 
                            sheet_name=?,
                            sheet_name2=?,
                            sheet_name3=?,
                            sheet_quantity=?,
                            sheet_quantity2=?,
                            sheet_quantity3=?,
                            price=?,
                            price2=?,
                            price3=?
                          WHERE 
                            sheet_id=?';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $sheet_name);
    $stmt->bindValue(2, $sheet_name2);
    $stmt->bindValue(3, $sheet_name3);
    $stmt->bindValue(4, $sheet_quantity);
    $stmt->bindValue(5, $sheet_quantity2);
    $stmt->bindValue(6, $sheet_quantity3);
    $stmt->bindValue(7, $price);
    $stmt->bindValue(8, $price2);
    $stmt->bindValue(9, $price3);
    $stmt->bindValue(10, $sheet_id);
    $stmt->execute();

    //会場テーブルにフォームの登録

    $sql = 'UPDATE places SET
                            place_name=?,
                            place_address=?
                          WHERE 
                            place_id=?';
    $stmt = $dbh->prepare($sql);
    $data = array();
    $data[] = $place_name;
    $data[] = $place_address;
    $data[] = $place_id;
    $stmt->execute($data);

    //チケットテーブルにフォームの登録
    
    $sql = 'UPDATE tickets  SET
                              ticket_name=?,
                              ticket_memo=?,
                              ticket_photo=?
                            WHERE 
                              ticket_id=?';
    $stmt = $dbh->prepare($sql);
    $data = array();
    $data[] = $ticket_name;
    $data[] = $ticket_memo;
    $data[] = $ticket_photo;
    $data[] = $ticket_id;
    $stmt->execute($data);

    //チケット日付テーブルにフォームの登録
    
    $sql = 'UPDATE  ticket_days SET
                                  day_start1=?,
                                  day_start2=?,
                                  day_start3=?,
                                  day_start4=?,
                                  day_start5=?,
                                  day_start6=?,
                                  day_start7=?,
                                  day_start8=?,
                                  day_start9=?
                                WHERE
                                  ticket_day_id=?';
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
    $stmt->bindValue(10, $ticket_day_id);
    $stmt->execute();

    //チケット時間テーブルにフォームの登録

    $sql = 'UPDATE  ticket_times  SET
                                    time_start1=?,
                                    time_start2=?,
                                    time_start3=?,
                                    time_start4=?,
                                    time_start5=?,
                                    time_start6=?,
                                    time_start7=?,
                                    time_start8=?,
                                    time_start9=?,
                                    time_end1=?,
                                    time_end2=?,
                                    time_end3=?,
                                    time_end4=?,
                                    time_end5=?,
                                    time_end6=?,
                                    time_end7=?,
                                    time_end8=?,
                                    time_end9=? 
                                  WHERE
                                    ticket_time_id=?';
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
    $stmt->bindValue(19, $ticket_time_id);
    $stmt->execute();
    $lastTimeId = $dbh->lastInsertId();//チケット時間テーブルIDの取得

/*
    $sql = 'UNLOCK TABLES';
    $stmt =$dbh->prepare($sql);
    $stmt->execute();
*/
    $dbh = null;
/*　***************************************************************** 　*/
/*　***************************************************************** 　*/

  if($ticket_photo_name_old != "")
  {
    unlink('./photo/'.$ticket_photo_name_old);
  }


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