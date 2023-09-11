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
<title>修正確認ページ</title>
</head>
<body>

  <h1>チケット修正確認ページ</h1>
<?php

require_once('../common/common.php');

$post=sanitize($_POST);
$ticket_id=$post['ticket_id'];
$ticket_day_id=$post['ticket_day_id'];
$ticket_time_id=$post['ticket_time_id'];
$place_id=$post['place_id'];
$sheet_id=$post['sheet_id'];

$ticket_name=$post['ticket_name'];
$ticket_memo=$post['ticket_memo'];
$days_start=$post['days_start'];
$times_start=$post['times_start'];
$times_end=$post['times_end'];
$place_name=$post['place_name'];
$place_address=$post['place_address'];
$sheets_name=$post['sheets_name'];
$sheets_quantity=$post['sheets_quantity'];
$prices=$post['prices'];

$ticket_photo_name_old=$post['ticket_photo_name_old'];
$ticket_photo=$_FILES['ticket_photo'];


$okflg=true;

if($ticket_name=='')
{
  print'公演名が入力されていません。<br/><br/>';
  $okflg=false;
}
else
{
  print '公演名<br/>';
  print $ticket_name;
  print '<br/><br/>';
}

if($ticket_memo=='')
{
  print'公演紹介文が記載されていません。<br/><br/>';
  $okflg=false;
}
else
{
  print '公演紹介文<br/>';
  print $ticket_memo;
  print '<br/><br/>';
}



if(empty($days_start[0]) || empty($times_start[0])|| empty($times_end[0]))
{
  print '開始日①と開始時間①と終了時間①は入力必須です。入力してください';
  $okflg=false;
  print '<br/><br/>';
}
else
{
  $count = count($days_start);

  //部分的な空白のチェック
  for($i = 1; $i < $count; $i++)
  {
    if(!empty($days_start[$i]) && (empty($times_start[$i]) || empty($times_end[$i])))
    {
      print '開始時間と終了時間はセットで入力してください';
      $okflg=false;
      break;
    }
    elseif($times_start[$i] > $times_end[$i])
    {
      print '開始時間と終了時間が逆転しています。正しい時間を入力してください';
      $okflg=false;
      print '<br/><br/>';
      break;
    }
  }
  print '開催日程<br/>';
  for($i = 0;$i < count($days_start);$i++ )
  {
    if(!empty($days_start[$i])){
      print $i + 1 .'.'.$days_start[$i].' '.'開催時間：'.$times_start[$i].' '.'終了時間：'.$times_end[$i];
      print '<br/><br/>';
    }
  }
}

if($place_name == '')
{
  print '会場名を入力してください';
  $okflg=false;
  print '<br/><br/>';
}
else
{
  print '会場名<br/>';
  print $place_name;
  print '<br/><br/>';
}


if($place_address == '')
{
  print '住所が入力されていません';
  $okflg=false;
  print '<br/><br/>';
}
else
{
  print '住所<br/>';
  print $place_address;
  print '<br/><br/>';
}




if(empty($sheets_name[0]) || empty($sheets_quantity[0] || empty($prices[0])))
{
  print '座席名、座席数、金額は必須項目です。正しく入力してください';
  $okflg = false;
}
else
{
  for($i = 1;$i < count($sheets_name); $i++)
  {
    if(!empty($sheets_name[$i]) && (empty($sheets_quantity[$i] )|| empty($prices[$i] )))
    {
    print 'シート名、シート数、金額はセットで入力してください';
    $okflg = false;
    }
  }
  print 'シート設定';
  print '<br/>';
  for($i = 0;$i < count($sheets_name);$i++ )
  {
    if(!empty($sheets_name[$i])){
      print 'シート名：'.$sheets_name[$i].' '.'シート数：'.$sheets_quantity[$i].' '.'金額：'.$prices[$i];
      print '<br/><br/>';
    }
  }

  if(empty($ticket_photo))
  {
    $ticket_photo = null;
  }

  if($ticket_photo['size'] > 1000000)
  {
    print '画像が大きすぎます';
    $okflg = false;
  }
  else
  {
    move_uploaded_file($ticket_photo['tmp_name'],'./photo/'.$ticket_photo['name']);
    print '<img src="./photo/'.$ticket_photo['name'].'">';
    print '</br>';
  }
}



if($okflg==true)
{
  ///////////配列→文字列に変換//////////////////////////////////
  
  $day_start = implode(',', $days_start);
  $time_start = implode(',', $times_start);
  $time_end = implode(',', $times_end);
  $sheet_name = implode(',', $sheets_name);
  $sheet_quantity = implode(',', $sheets_quantity);
  $price = implode(',', $prices);

  ////////////////////////////////////////////////////////////

  print '<form method="post" action="ticket_edit_done.php">';
  print '<input type="hidden" name="ticket_id" value="'.$ticket_id.'">';
  print '<input type="hidden" name="ticket_day_id" value="'.$ticket_day_id.'">';
  print '<input type="hidden" name="ticket_time_id" value="'.$ticket_time_id.'">';
  print '<input type="hidden" name="place_id" value="'.$place_id.'">';
  print '<input type="hidden" name="sheet_id" value="'.$sheet_id.'">';
  

  print '<input type="hidden" name="ticket_name" value="'.$ticket_name.'">';
  print '<input type="hidden" name="ticket_memo" value="'.$ticket_memo.'">';
  print '<input type="hidden" name="day_start" value="'.$day_start.'">';
  print '<input type="hidden" name="time_start" value="'.$time_start.'">';
  print '<input type="hidden" name="time_end" value="'.$time_end.'">';
  print '<input type="hidden" name="ticket_photo_name_old" value="'.$ticket_photo_name_old.'">';
  print '<input type="hidden" name="ticket_photo" value="'.$ticket_photo['name'].'">';
  print '<input type="hidden" name="place_name" value="'.$place_name.'">';
  print '<input type="hidden" name="place_address" value="'.$place_address.'">';
  print '<input type="hidden" name="sheet_name" value="'.$sheet_name.'">';
  print '<input type="hidden" name="sheet_quantity" value="'.$sheet_quantity.'">';
  print '<input type="hidden" name="price" value="'.$price.'">';
  print '<input type="button" onclick="history.back()" value="戻る">';
  print '<input type="submit" value="OK"><br/>';
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