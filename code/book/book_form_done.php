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

$account_id=$post['account_id'];
$ticket_id=$post['ticket_id'];
$select_date_code=$post['selected_date_code'];
$day_start_value=$post['day_start'];
$time_start_value=$post['time_start'];
$time_end_value=$post['time_end'];
$time_combined_value=$post['time_combined'];

$select_sheet_code=$post['selected_sheet_code'];
$sheet_name_value=$post['sheet_name'];
$sheet_price_value=$post['sheet_price'];
$booking_quantity=$post['booking_quantity'];
$booking_price=$post['booking_price'];


  /****************************************** */


    print 'チケットを予約しました。<br/><br/>';
    
  /****************************************** */
  /***************データベース処理************ */
  /****************************************** */

  //データベースの接続
    $dsn ='mysql:dbname=ticket_shop;host=localhost;charset=utf8';
    $user ='root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
  //トランザクション処理
    $dbh->beginTransaction();

  //sheet_テーブルからIDの取得
    $sql='SELECT 
            tickets.ticket_id,
            sheets.sheet_id
          FROM `tickets`
          JOIN places
            ON places.place_id = tickets.place_id
          JOIN sheets
            ON sheets.sheet_id = places.sheet_id 
          WHERE tickets.ticket_id=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $ticket_id;
    $stmt->execute($data);
    

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

  //booksテーブルの追加
    $sql='INSERT INTO books
            (
              account_id,
              ticket_id,
              book_sheet_name,
              book_day,
              book_time,
              booking_quantity
            )
          Value(?,?,?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $data = [];
    $data[] = $account_id;
    $data[] = $ticket_id;
    $data[] = $sheet_name_value;
    $data[] = $day_start_value;
    $data[] = $time_combined_value;
    $data[] = $booking_quantity;
    $stmt->execute($data);

    $lastBookId = $dbh->lastInsertId();

  //chukanDテーブルの追加
    $sql='INSERT INTO chukan_accounts_books
            (
              account_id,
              book_id
            )
          VALUE(?,?)';
    $stmt = $dbh->prepare($sql);
    $data = [];
    $data[] = $account_id;
    $data[] = $lastBookId;
    $stmt->execute($data);

    $lastChukanId = $dbh->lastInsertId();

  //座席数の減算
  switch ($select_sheet_code) {
    case 0:
        $update_stock = "UPDATE sheets
                        SET sheet_stock1 = sheet_stock1 - :booking_quantity 
                        WHERE sheet_id = :sheet_id
                        ";
        break;
    case 1:
        $update_stock = "UPDATE sheets
                        SET sheet_stock2 = sheet_stock2 - :booking_quantity 
                        WHERE sheet_id = :sheet_id
                        ";
        break;
    case 2:
        $update_stock = "UPDATE sheets
                        SET sheet_stock3 = sheet_stock3 - :booking_quantity 
                        WHERE sheet_id = :sheet_id
                        ";
        break;
    default:
              break;
    }
    $stmt = $dbh->prepare($update_stock);
    $stmt->bindParam(':booking_quantity', $booking_quantity, PDO::PARAM_INT);
    $stmt->bindParam(':sheet_id', $rec['sheet_id'], PDO::PARAM_INT);
    $stmt->execute();

  //paymentテーブルの追加
    $sql='INSERT INTO payments(
            account_id,
            book_id,
            price)
          VALUE(?,?,?)';
    $stmt = $dbh->prepare($sql);
    $data = [];
    $data[] = $account_id;
    $data[] = $lastBookId;
    $data[] = $booking_price;
    $stmt->execute($data);

  //結果にコミット
    $dbh->commit();
    $dbh = null;


    /*　***************************************** 　*/
    /*　*****************************************　 */
    /*　***************************************** 　*/

}catch(Exception $e)
{
  //エラー時ロールバック
  $dbh->rollBack();
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