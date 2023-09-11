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
<title>予約チェック</title>
</head>
<body>

  <h1>チケット予約確認ページ</h1>

<?php

require_once('../common/common.php');

//データの受け取りに失敗した際に処理を中断する
if (!isset($_POST['account_id']) || 
    !isset($_POST['ticket_id']) || 
    !isset($_POST['selected_date_code']) ||
    !isset($_POST['selected_sheet_code']) 
    ) {

    echo "入力に誤りがあります。";
    echo '<form>';
    echo '<input type="button" onclick="history.back()" value="戻る">';
    echo '</form>';
    exit; 
}

$post=sanitize($_POST);

$account_id=$post['account_id'];
$ticket_id=$post['ticket_id'];
$select_date_code=$post['selected_date_code'];
$day_start_value=$post['selected_date_'.$select_date_code];
$time_start_value=$post['selected_start_time_'.$select_date_code];
$time_end_value=$post['selected_end_time_'.$select_date_code];
$time_combined_value=$post['selected_combined_time_'.$select_date_code];
$select_sheet_code=$post['selected_sheet_code'];
$sheet_name_value=$post['selected_sheet_'.$select_sheet_code];
$sheet_price_value=$post['selected_price_'.$select_sheet_code];
$booking_quantity=$post['booking_quantity_'.$select_sheet_code];


$okflg=true;


/***************************データベース処理**************************************** */
/********************************************************************************* */
$dsn = 'mysql:dbname=ticket_shop;host=localhost;charset=utf8';
$user = 'root';
$password = '';
$dbh = new PDO($dsn,$user,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


$sql  = ' SELECT
            accounts.account_name1,
            accounts.account_name2,
            accounts.account_postal1,
            accounts.account_postal2,
            accounts.account_address,
            accounts.account_tel
          FROM `accounts`
          WHERE account_id = ?;';
$stmt = $dbh->prepare($sql);
$data[] = $account_id;
$stmt->execute($data);

$dbh = null;

$rec = $stmt->fetch(PDO::FETCH_ASSOC);

/********************************************************************************* */
/********************************************************************************* */
print '<h2>下記の内容で予約します</h2>';
print '<h2>会員情報</h2>';

print '<h3>お名前</h3>';
print $rec['account_name1'].'&nbsp'.$rec['account_name2'];
print '<br>';
print '<h3>電話番号</h3>';
print $rec['account_tel'];
print '<br>';

print '<h3>郵便番号</h3>';
print $rec['account_postal1'];
print '-';
print $rec['account_postal2'].'<br/>';
print '<h3>住所</h3>';
print $rec['account_address'];
print '<br>';
print '<br>';
print '<br>';


print '<h2>公演情報</h2>';

if($select_date_code=='')
{
  print'日程が選択されていません。<br/><br/>';
  $okflg=false;
}
else
{

  print '<table>';
  print '<th>開催日</th><th>公演時間</th>';
  print '<tr>';
  print '<td>'.$day_start_value.'</td>';
  print '<td>'.$time_combined_value.'</td>';
  print '</tr>';
  print '</table>';
  print '<br/>';
}

if($booking_quantity<=0)
{
  print '<b>※チケット予約枚数が0枚以下です。正しく入力してください。</b><br/>';
  $okflg = false;
}

if($select_sheet_code=='')
{
  print'シート詳細が選択されていません。<br/><br/>';
  $okflg=false;
}
else
{
  print '<table>';
  print '<th>シート名</th><th>価格</th><th>予約枚数</th>';
  print '<tr>';
  print '<td>'.$sheet_name_value.'</td>';
  print '<td>'.$sheet_price_value.'円</td>';
  print '<td style="text-align: right;">' .$booking_quantity.'枚</td>';
  print '</tr>';
  print '</table>';
  print '<br/>';
  $booking_price = $sheet_price_value * $booking_quantity;
  print '合計金額：'.$booking_price.'円';
  print '<br/><br/>';
}




/****************************************************************************** */
/****************************************************************************** */

if($okflg==true)
{
  

  print '<form method="post" action="book_form_done.php">';
  print '<input type="hidden" name="account_id" value="'.$account_id.'">';
  print '<input type="hidden" name="ticket_id" value="'.$ticket_id.'">';
  print '<input type="hidden" name="selected_date_code" value="'.$select_date_code.'">';
  print '<input type="hidden" name="day_start" value="'.$day_start_value.'">';
  print '<input type="hidden" name="time_start" value="'.$time_start_value.'">';
  print '<input type="hidden" name="time_end" value="'.$time_end_value.'">';
  print '<input type="hidden" name="time_combined" value="'.$time_combined_value.'">';
  print '<input type="hidden" name="selected_sheet_code" value="'.$select_sheet_code.'">';
  print '<input type="hidden" name="sheet_name" value="'.$sheet_name_value.'">';
  print '<input type="hidden" name="sheet_price" value="'.$sheet_price_value.'">';
  print '<input type="hidden" name="booking_quantity" value="'.$booking_quantity.'">';
  print '<input type="hidden" name="booking_price" value="'.$booking_price.'">';

  print '<input type="button" onclick="history.back()" value="戻る">';
  print '&nbsp';
  print '<input type="submit" value="予約する"><br/>';
  print '</form>';
}
else
{
  print '<form>';
  print '<input type="button" onclick="history.back()" value="戻る">';
  print '</form>';
}
?>

</body>
</html>