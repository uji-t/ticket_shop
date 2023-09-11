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
<link rel="stylesheet" href="html.css">
<title>マイページ</title>
</head>
<body>
<h1>マイページ</h1>
<?php

$account_id=$_SESSION['account_id'];


/******************データベース処理**************************** */
/************************************************************* */
    $dsn = 'mysql:dbname=ticket_shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT 
                books.book_id,
                books.book_sheet_name,
                books.book_day,
                books.book_time,
                tickets.ticket_name,
                places.place_name,
                places.place_address
            FROM `accounts`
            JOIN chukan_accounts_books
              ON chukan_accounts_books.account_id = accounts.account_id
            JOIN books
              ON books.book_id = chukan_accounts_books.book_id
            JOIN tickets
              ON tickets.ticket_id = books.ticket_id
            JOIN places
              ON places.place_id = tickets.place_id
            WHERE accounts.account_id = ?';

    $stmt = $dbh->prepare($sql);
    $data[] = $account_id;
    $stmt->execute($data);
    $dbh = null;

/************************************************************* */
/************************************************************* */

    print '<h2>予約チケット一覧</h2><br/><br/>';
    
    while(true)
    {
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      if($rec==false)
      {
        break;
      }
      print '<table  border="1">
              <tr>
              <th></th>
              <th>公演名</th>
              <th>予定日</th>
              <th>予定時間</th>
              </tr>
              <tr>
                <td><input type="radio" name="book_id" value="'.$rec['book_id'].'"></td>
                <td>'.$rec['ticket_name'].'</td>
                <td>'.$rec['book_day'].'</td>
                <td>'.$rec['book_time'].'</td>
              </tr>
              <tr>
                <th></th>
                <th>会場名</th>
                <th>住所</th>
              </tr>
              <tr>
                <td></td>
                <td>'.$rec['place_name'].'</td>
                <td>'.$rec['place_address'].'</td>
              </tr>
            </table>';
      print '<br/>';
    }
  print '<input type="submit" name="delete" value="予約の取消">';
  print '</form>';

  ?>

<br/>
<br/>
<a href="../view/index.php">トップメニューへ</a><br/>


</body>
</html>