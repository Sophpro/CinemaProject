<!DOCTYPE html>
<html lang="en">
<head>
<title>MZ Cinema</title>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
</head>
<?php
error_reporting(0);
@ $db = new mysqli('localhost', 'root', '', 'mzcinema');
if (mysqli_connect_errno()) {
   echo "<br>Error: Could not connect to database.  Please try again later.";
   exit;
}
// echo "<br><br><br><br><br><br><br><br>";

$getid = $_POST['movie-page-card'];
$getcinema = $_POST['movie-page-cinema'];
$getdate = $_POST['movie-page-date'];
$gettime = $_POST['movie-page-time'];
$getprice = $_POST['movie'];
$seatsString = $_POST['seats'];
$getseats = explode(',', $seatsString);
$numseat = $_POST['seatnum'];
$total = $numseat * $getprice;

$moviequery = "SELECT * FROM `movies` WHERE `id` = '".$getid."'";
$moviedetails = $db->query($moviequery)->fetch_object();
$moviename = $moviedetails->movie_name; 
$movieurl = $moviedetails->picture_url; 

$cinemaquery = "SELECT * FROM `cinemas` WHERE `id` = '".$getcinema."'";
$cinemadetails = $db->query($cinemaquery)->fetch_object();
$cinemaname = $cinemadetails->cinema_name; 

$seatnum = "";
$seatnumArray = [];
$sessionnum = "";
$sessionnumArray = [];
foreach ($getseats as $seat){
  $query = "SELECT * FROM `movsessions` WHERE `movie_id` = '".$getid."' AND `cinema_id` = '".$getcinema."' AND `date` = '".$getdate."' AND `time` = '".$gettime."' AND `seat_id` = '".$seat."'";
  $moviesessions = $db->query($query)->fetch_object();
  if ($moviesessions) {
    $session = $moviesessions->id;
    $seatnumArray[] = $seat;
    $sessionnumArray[] = $session;
  }
}
sort($seatnumArray);
$seatnum = implode(',', $seatnumArray);
sort($sessionnumArray);
$sessionnum = implode(',', $sessionnumArray);

$seats = "";
$query_all = "SELECT * FROM `movsessions` WHERE `movie_id` = '".$getid."' AND `cinema_id` = '".$getcinema."' AND `date` = '".$getdate."' AND `time` = '".$gettime."'";
$moviesessions_all = $db->query($query_all);
$no_records_all = $moviesessions_all->num_rows;
for ($j=0; $j<$no_records_all; $j++){
  $seatrow = $moviesessions_all->fetch_assoc();
  //start row
  if (in_array($j, [0, 10, 20, 30, 40])){
    $seats = $seats.'<div class="row">
    ';
  }
  //set seat
  if ($seatrow['status']=="Available"){
    if (in_array($seatrow['seat_id'], $getseats)){
      $seats = $seats.'<div class="seat selected" id="'.$seatrow['seat_id'].'"></div>
      ';
    }
    else{
      $seats = $seats.'<div class="seat" id="'.$seatrow['seat_id'].'"></div>
      ';      
    }
  }
  else{
    $seats = $seats.'<div class="seat occupied" id="'.$seatrow['seat_id'].'"></div>
    ';
  }
  //end row
  if (in_array($j, [9, 19, 29, 39, 49])){
    $seats = $seats.'</div>
    ';
  }
}

?>
<body>

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
            <li><a href="MinePage.php">Mine</a></li>
        </ul>
    </div>
    <div class="top-right">
    <div class="log">
        <div class="logbutton">
            <a href="LoginPage.php">Log in</a>
        </div>
    </div>
    <div class="cart">
        <a href="CartPage.php"><img src="./img/cart.png" width="40" height="40"></a>
    </div>
    </div>
  </div>
  <div class="main">
    <div class="topBack">
        <a href="javascript:history.back(-1)"><— Back</a>
    </div>
    <div class="midView">
        <?php 
        date_default_timezone_set('Asia/Singapore');
        $user_id = $_POST['user_id'];
        $movsession_string = $_POST['movsession_id'];
        $subtotal = $_POST['subtotal'];
        if ($user_id && $movsession_string && $subtotal){
          $movsession_id = explode(',', $movsession_string);
          foreach($movsession_id as $session){
            $query_update = "UPDATE movsessions SET `status` = 'Occupied' WHERE `id` = '".$session."' AND `status` = 'Available'";
            $result = $db->query($query_update);
            if (!$result) {
              echo '<div class="mid_img">
                      <img class="bookingsuccessful" alt="booking unsuccessful" src="./img/bookingunsuccessful.png"> 
                      <p>Payment Unsuccessful</p>
                      <p>Please try again!</p>
                      <div class="backbutton">
                        <a href="javascript:history.back(-1)">Back to Booking Page</a>
                      </div>
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
              ';
            $db->close();
            exit;
            }
            $query_check = "SELECT * FROM orders WHERE movsession_id = '".$session."'";
            $result_check = $db->query($query_check);
            if ($result_check->num_rows == 0) {
              $query_order = "INSERT into orders value('', '".$user_id."', '".$session."', '".date('Y-m-d H-m-s')."', '".$subtotal."', '', '')";
              $result = $db->query($query_order);
                if (!$result) {
                  echo '<div class="mid_img">
                          <img class="bookingsuccessful" alt="booking unsuccessful" src="./img/bookingunsuccessful.png"> 
                          <p>Payment Unsuccessful</p>
                          <p>Please try again!</p>
                          <div class="backbutton">
                            <a href="javascript:history.back(-1)">Back to Booking Page</a>
                          </div>
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
                  ';
                $db->close();
                exit;
                }
            }
          }
              echo '<div class="mid_img">
                      <img class="bookingsuccessful" alt="booking successful" src="./img/bookingsuccessful.png"> 
                      <p>Thank you for booking!</p>
                      <p>An email will be sent to you!</p>
                      <div class="backbutton">
                        <a href="index.php">Back to Home Page</a>
                      </div>
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
              ';
          $db->close();
          exit;
        }
        ?>
        <div class="leftcolumn">
            <div class="movie-poster">
                <img class="booking-poster" alt="movie poster" src=".<?=$movieurl?>">
            </div>
        </div>
        <div class="main-right">
            <div class="movie_container">
                <h2>You are booking:</h2>
                <p>Movie Name: <?=$moviename?></p>
                <p>Cinema Name: MZ <?=$cinemaname?></p>
                <p>Date: <?=$getdate?></p>
                <p>Time: <?=$gettime?></p>
                <p>Seat(s) Number: <?=$seatnum?></p>
                <p>Total Price: S$ <?=$total?></p>
                <div class="container">
                  <div class="screen">
                  </div>
                  <?=$seats?>
                </div>
                <p>Please double check and click the button below after payment!</p>
                <div class="confirm">
                  <form id="confirm_booking" method="post" action="">
                    <input id="user_id" name="user_id" value="test" style="display: none;"/> <!-- to be replaced with login info -->
                    <input id="movsession_id" name="movsession_id" value="<?=$sessionnum?>" style="display: none;"/>
                    <input id="subtotal" name="subtotal" value="<?=$getprice?>" style="display: none;"/>
                    <button class="confirmbutton" onclick="submitbooking('PaymentSuccessfulPage.php')">Payment Done</button>
                  </form>
                </div>
            </div>
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
<script>
  function submitbooking(action) {
    document.getElementById("confirm_booking").action = action;
    document.getElementById("confirm_booking").submit();
  }
</script>
</body>
</html>
