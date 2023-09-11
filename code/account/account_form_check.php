
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>確認画面</title>
</head>
<body>

<h1>確認画面</h1>

<?php

require_once('../common/common.php');

$post=sanitize($_POST);

$account_name1=$post['account_name1'];
$account_name2=$post['account_name2'];
$account_furigana1=$post['account_furigana1'];
$account_furigana2=$post['account_furigana2'];
$account_email=$post['account_email'];
$account_postal1=$post['account_postal1'];
$account_postal2=$post['account_postal2'];
$account_address=$post['account_address'];
$account_tel=$post['account_tel'];
$account_pass=$post['account_pass'];
$account_pass2=$post['account_pass2'];
$account_sex=$post['account_sex'];

$okflg=true;

if($account_name1 == ''||$account_name2 == '')
{
  print 'お名前が入力されていません。<br/><br/>';
  $okflg=false;
}
else
{
  print 'お名前<br/>';
  print $account_name1."&nbsp".$account_name2;
  print '<br/><br/>';
}

if($account_furigana1 == '' || $account_furigana2 == '')
{
  print 'フリガナが入力されていません。<br/><br/>';
  $okflg=false;
}
elseif(preg_match('/^[ァ-ヴーｱ-ﾝﾞﾟ]+$/u',$account_furigana1)==0 || preg_match('/^[ァ-ヴーｱ-ﾝﾞﾟ]+$/u',$account_furigana2)==0)
{
  print'フリガナはカタカナで入力してください<br/><br/>';
  $okflg=false;
}
else
{
  print 'フリガナ<br/>';
  print $account_furigana1.'&nbsp'.$account_furigana2;
  print '<br/><br/>';
}

print '性別<br/>';
if($account_sex==1)
{
  print '男性';
}
else
{
  print '女性';
}
print '<br/><br/>';

if(preg_match('/\A\d{2,5}-?\d{2,5}-?\d{4,5}\z/',$account_tel)==0)
{
  print '電話番号を正確に入力してください。<br/><br/>';
  $okflg=false;
}
else
{
  print '電話番号<br/>';
  print $account_tel;
  print '<br/><br/>';
}


if(preg_match('/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/',$account_email)==0)
{
  print 'メールアドレスを正確に入力してください<br/><br/>';
  $okflg=false;
}
else
{
  print 'メールアドレス<br/>';
  print $account_email;
  print '<br/><br/>';
}

if(preg_match('/\A[0-9]+\z/',$account_postal1)==0)
{
  print '郵便番号は半角数字で入力してください<br/><br/>';
  $okflg=false;
}
elseif(preg_match('/\A[0-9]+\z/',$account_postal2)==0)
{
  print '郵便番号は半角数字で入力してください<br/><br/>';
  $okflg=false;
}
else
{
  print '郵便番号<br/>';
  print $account_postal1.'-'.$account_postal2;
  print '<br/><br/>';
}




if($account_address == '')
{
  print '住所が入力されていません<br/><br/>';
  $okflg=false;
}
else
{
  print '住所<br/>';
  print $account_address;
  print '<br/><br/>';
}



if($account_pass=='')
{
  print 'パスワードが入力されていません<br/><br/>';
  $okflg=false;
}

if($account_pass!=$account_pass2)
{
  print 'パスワードが一致しません<br/><br/>';
  $okflg=false;
}





if($okflg==true)
{
  print '<form method="post" action="account_form_done.php">';
  print '<input type="hidden" name="account_name1" value="'.$account_name1.'">';
  print '<input type="hidden" name="account_name2" value="'.$account_name2.'">';
  print '<input type="hidden" name="account_furigana1" value="'.$account_furigana1.'">';
  print '<input type="hidden" name="account_furigana2" value="'.$account_furigana2.'">';
  print '<input type="hidden" name="account_sex" value="'.$account_sex.'">';
  print '<input type="hidden" name="account_tel" value="'.$account_tel.'">';
  print '<input type="hidden" name="account_email" value="'.$account_email.'">';
  print '<input type="hidden" name="account_postal1" value="'.$account_postal1.'">';
  print '<input type="hidden" name="account_postal2" value="'.$account_postal2.'">';
  print '<input type="hidden" name="account_address" value="'.$account_address.'">';

  print '<input type="hidden" name="account_pass" value="'.$account_pass.'">';

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