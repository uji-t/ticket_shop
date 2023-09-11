<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['login'])==false)
{
  print 'ログインされていません。<br/>';
  print '<a href="../login/account_login.html">ログイン画面へ</a>';
  exit();
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>bookingJapan</title>
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <div class="container">
      <h1>booking Japan</h1>
      <section class="information">
        <h2>日本中のliveがここに集まる</h2>
      </section>
    </div>
  </header>
  <div class="sidebar-left"></div>
  <div class="sidebar-right">
    <?php   print $_SESSION['account_name1'];
            print 'さんログイン中';
            print '<br/>';
    ?>
    <a href="../login/account_logout.php">ログアウト</a>
    <br>
    <br>
    <a href="../view/mypage.php">マイページへ</a>
    <br>
    <br>
    --------運営用---------
    <br>
    <a href="../ticket/ticket_list.php">チケット一覧</a>
    <br>
    <a href="../ticket/ticket_form.html">チケット登録</a>
    <br>
    -----------------------
  </div>
  <main>
    <h2 class="pick_up">pick up</h2>
    <div class="card-container">

      <?php
          $dsn ='mysql:dbname=ticket_shop;host=localhost;charset=utf8';
          $user ='root';
          $password = '';
          $dbh = new PDO($dsn,$user,$password);
          $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

          $sql = 'SELECT 
                    tickets.ticket_id,
                    tickets.ticket_photo,
                    tickets.ticket_name,
                    ticket_days.day_start1,
                    places.place_name
                  FROM
                    tickets
                  JOIN chukan_tickets_days
                    ON tickets.ticket_id = chukan_tickets_days.ticket_id
                  JOIN ticket_days
                    ON chukan_tickets_days.ticket_day_id = ticket_days.ticket_day_id
                  JOIN places
                    ON places.place_id = tickets.place_id
                  WHERE
                    day_start1 >= CURDATE()
                  ORDER BY day_start1 ASC
                  LIMIT 9'; 
          $stmt = $dbh->prepare($sql);
          $stmt->execute();
          $rec = $stmt->fetchAll(PDO::FETCH_ASSOC);


          foreach($rec as $row)
          {
            $ticket_id = $row['ticket_id'];
            $ticket_photo = $row['ticket_photo'];
            $ticket_name = $row['ticket_name'];
            $day_start1 = $row['day_start1'];
            $place_name = $row['place_name'];

            print '<div class="card" onclick="redirectToTicketDisp(' . $ticket_id . ')">';
            print '<div class="cardArea">';
            print '<img src="../ticket/photo/'.$ticket_photo.'"alt="img">';
            print '</div>';
            print '<div class="text">';
            print '<span><p">'.$ticket_name.'</p></span>';
            print '<p>会場名：'.$place_name.'</p>';
            print '<p>開催日：'.$day_start1.'~</p>';
            print '</div>';
            print '</div>';

            print '<script>';
            print 'function redirectToTicketDisp(ticketId) {';
            print '  window.location.href = "../ticket/ticket_disp.php?ticket_id=" + ticketId;';
            print '}';
            print '</script>';
          }          
      ?>
    </div>
  </main>
</body>
</html>