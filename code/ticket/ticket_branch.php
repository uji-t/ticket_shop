<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false)
{
  print 'ログインされていません。<br/>';
  print '<a href="../login/account_login.html">ログイン画面へ</a>';
  exit();
}

if(isset($_POST['disp'])==true)
{
  if(isset($_POST['ticket_id'])==false)
  {
    header('Location:ticket_ng.php');
    exit();
  }
  $ticket_id=$_POST['ticket_id'];
  header('Location:ticket_disp.php?ticket_id='.$ticket_id);
  exit();
}

if(isset($_POST['add'])==true)
{
  header('Location:ticket_form.html');
  exit();
}

if(isset($_POST['edit'])===true)
{
  if(isset($_POST['ticket_id'])==false)
  {
    header('Location:ticket_ng.php');
    exit();
  }
  $ticket_id=$_POST['ticket_id'];
  header('Location:ticket_edit.php?ticket_id='.$ticket_id);
  exit();
}

if(isset($_POST['delete'])===true)
{
  if(isset($_POST['ticket_id'])==false)
  {
    header('Location:ticket_ng.php');
    exit();
  }
  $ticket_id=$_POST['ticket_id'];
  header('Location:ticket_delete.php?ticket_id='.$ticket_id);
  exit();
}

?>
</body>
</html>