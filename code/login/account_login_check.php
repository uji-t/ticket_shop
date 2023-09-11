
<?php

try
{
  
  require_once('../common/common.php');

  $post=sanitize($_POST);

  $account_email = $post['email'];
  $account_pass = $post['pass'];

  $account_pass=md5($account_pass);
/***************************データベース処理******************************* */
/************************************************************************ */
  
  $dsn='mysql:dbname=ticket_shop;host=localhost;charset=utf8';
  $user='root';
  $password='';
  $dbh=new PDO($dsn,$user,$password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

  $sql='SELECT 
          accounts.account_id,
          accounts.account_name1 
        FROM accounts 
        WHERE account_email=? AND account_pass=?';
        
  $stmt = $dbh->prepare($sql);
  $data[]=$account_email;
  $data[]=$account_pass;

  $stmt->execute($data);

  $rec=$stmt->fetch(PDO::FETCH_ASSOC);


  $dbh=null;

/************************************************************************ */

  if($rec==false)
  {
    print 'メールアドレスかパスワードが間違っています。<br/>';
    print '<a href="account_login.html">戻る</a>';
  }
  else
  {
  
    session_start();
    $_SESSION['login']=1;
    $_SESSION['account_id']=$rec['account_id'];
    $_SESSION['account_name1']=$rec['account_name1'];

    header('Location: ../view/index.php');
    exit();
  }
}
catch(Exception $e)
{
  print 'ただいま障害により大変ご迷惑をおかけしています';
  exit();
}

?>