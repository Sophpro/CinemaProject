<!DOCTYPE html>
<html lang="en">
<head>
<title>MZ Cinema Movies</title>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
</head>
<?php
$username="f32ee"; //to be replaced with login info

error_reporting(0);
date_default_timezone_set('Asia/Singapore');
@ $db = new mysqli('localhost', 'root', '', 'mzcinema');
if (mysqli_connect_errno()) {
   echo "<br>Error: Could not connect to database.  Please try again later.";
   exit;
}
//echo "<br><br><br><br><br><br><br><br>";
$query_user = "SELECT * FROM `users` WHERE `username` = '".$username."'";
$users = $db->query($query_user)->fetch_object();
$user_id = $users->id;
$user_email = $users->email;

$query_orders = "SELECT * FROM `orders` WHERE `user_id` = '".$user_id."'";
$orders = $db->query($query_orders);
$no_records = $orders->num_rows;
if ($no_records>0){
  $upcoming = "";
  $past = "";
  for ($i=0; $i<$no_records; $i++) {
    $row = $orders->fetch_assoc();
    $query_session = 'SELECT * FROM `movsessions` WHERE `id` = "'.$row['movsession_id'].'"';
    $movsession = $db->query($query_session)->fetch_object();

    //movie name and url
    $movie_id = $movsession->movie_id;
    $query_movie = "SELECT * FROM `movies` WHERE `id` = '".$movie_id."'";
    $moviedetails = $db->query($query_movie)->fetch_object();
    $moviename = $moviedetails->movie_name; 
    $movieurl = $moviedetails->picture_url;
    //cinema name
    $cinema_id = $movsession->cinema_id;
    $query_cinema = "SELECT * FROM `cinemas` WHERE `id` = '".$cinema_id."'";
    $cinemadetails = $db->query($query_cinema)->fetch_object();
    $cinemaname = $cinemadetails->cinema_name;

    $date = $movsession->date;
    $time = $movsession->time;
    $hall = $movsession->hall;
    $seat_id = $movsession->seat_id;
    $ticket = '
    <div class="ticket">
      <div class="ticket_left">
        <img class="ticket_poster" alt="movie poster" src=".'.$movieurl.'">
      </div>
      <div class="ticket_right">
        <p class="movie-name">'.$moviename.'</p>
        <p class="movie-description">'.$moviedetails->genre1.', '.$moviedetails->genre2.' | '.$moviedetails->runtime.' | '.$moviedetails->language.'</p>
        <p class="movie-description">Cinema: MZ '.$cinemaname.' Hall '.$hall.'</p>
        <p class="movie-description">Date&amp;Time: '.$date.' '.$time.'</p>
        <p class="movie-description">Seat Number: '.$seat_id.'</p>
      </div>
    </div>
    ';
    if ($date>=date('Y-m-d')){
      $upcoming = $upcoming.$ticket;
    }
    else{
      $past = $past.$ticket;
    }
}
}
$nofound = "<p>No tickets are found. Let's ZOOM to MOVIE now! </p>";


$db->close();
?>
<body>
<script>

</script>
<div id="wrapper">
  <div class="top">
    <div class="logo">
        <img src="./img/logo.png" width="120" height="40">
    </div>
    <div class="document">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="MoviesPage.php">Movies</a></li>
            <li><a href="CinemasPage.php">Cinemas</a></li>
            <li><a href="PromotionPage.php">Promotions</a></li>
            <li><a href="DiningPage.php">Dining</a></li> 
            <li><a href="MinePage.php" class="active">Mine</a></li>
        </ul>
    </div>
    <div class="top-right">
    <div class="log">
        <div class="logbutton">
            <a href="LoginPage.html">Log in</a>
        </div>
    </div>
    <div class="cart">
        <a href="CartPage.php"><img src="./img/cart.png" width="40" height="40"></a>
    </div>
    </div>
  </div>
  <div class="main">
      <div class="midView">
          <div class="upcoming">
              <h2>Your Upcoming Tickets:</h2>
              <?php
                if($upcoming){
                  echo $upcoming;
                }
                else{
                  echo $nofound;
                }
              ?>
          </div>
          <div class="past">
              <h2>Your Past Tickets:</h2>
              <?php
                if($past){
                  echo $past;
                }
                else{
                  echo $nofound;
                }
              ?>
          </div>
      </div>
  </div>
  <div class="footer">
    <footer>
      <small><i><br>Copyright &copy; Movie Zoomer Cinema Pte Ltd</i>
        <br><br>Email us: <a href="mailto:MZCinema@mz.com">MZCinema@mz.com</a>
        <br><br>Follow us: <img src="./img/iglogo.png" width="30" height="30">  <img src="./img/facebooklogo.png" width="30" height="30">  <img src="./img/tiktoklogo.png" width="30" height="30"></small>
    </footer>
  </div>
</div>

</body>
</html>
