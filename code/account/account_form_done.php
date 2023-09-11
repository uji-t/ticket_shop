
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>会員登録画面</title>
</head>
<body>

<?php

  try{
  require_once('../common/common.php');

  $post = sanitize($_POST);

  $account_name1 = $post['account_name1'];
  $account_name2 = $post['account_name2'];
  $account_furigana1 = $post['account_furigana1'];
  $account_furigana2 = $post['account_furigana2'];
  $account_sex = $post['account_sex'];
  $account_tel = $post['account_tel'];
  $account_postal1 = $post['account_postal1'];
  $account_postal2 = $post['account_postal2'];
  $account_address = $post['account_address'];
  $account_email = $post['account_email'];
  $account_pass = $post['account_pass'];

  $account_pass = md5($account_pass);

  print $account_name1."&nbsp".'様<br/>';
  print 'ご登録ありがとうございました。</br>';
  print '確認メールを'.$account_email.'送りましたのでご確認ください。<br/>';

  /****************************************** */
  /***************データベース処理************ */
  /****************************************** */

  $dsn ='mysql:dbname=ticket_shop;host=localhost;charset=utf8';
  $user ='root';
  $password = '';
  $dbh = new PDO($dsn,$user,$password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  $sql='BEGIN;
          INSERT INTO
          accounts(
            account_name1,
            account_name2,
            account_furigana1,
            account_furigana2,
            account_sex,
            account_tel,
            account_postal1,
            account_postal2,
            account_address,
            account_email,
            account_pass
          )
          VALUES(?,?,?,?,?,?,?,?,?,?,?);
        COMMIT;';
  $stmt =$dbh->prepare($sql);
  $data[] = $account_name1;
  $data[] = $account_name2;
  $data[] = $account_furigana1;
  $data[] = $account_furigana2;
  $data[] = $account_sex;
  $data[] = $account_tel;
  $data[] = $account_postal1;
  $data[] = $account_postal2;
  $data[] = $account_address;
  $data[] = $account_email;
  $data[] = $account_pass;
  $stmt->execute($data);

  $dbh = null;
  /************************************************** */

}catch(Exception $e)
{
  print 'ただいま障害により大変ご迷惑をおかけしております。<br/>';
  print '<br/>';
}

?>

<br/>
<a href="../login/account_login.html">ログイン画面へ</a>

</body>
</html>