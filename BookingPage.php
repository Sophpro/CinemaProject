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
// echo "<br><br><br><br>";

// echo $_POST['movie-page-time'];
if (!$_POST['movie-page-card']) {
    $getid = $_POST['movie-card'];
}
else if (!$_POST['movie-card']){
    $getid = $_POST['movie-page-card'];
}
else{
    echo "error in getting movie index";
    exit;
}
$getcinema = $_POST['movie-page-cinema'];
if ($_POST['movie-page-date'] != ""){
    $getdate = $_POST['movie-page-date'];
}
if ($_POST['movie-page-time'] != ""){
    $gettime = $_POST['movie-page-time'];
}

$cinemaquery = "SELECT DISTINCT `cinema_id` FROM `movsessions` WHERE `movie_id` = '".$getid."' ORDER BY `cinema_id`";
$moviesessions_cinema = $db->query($cinemaquery);
$no_records_cinema = $moviesessions_cinema->num_rows;
for ($i=0; $i<$no_records_cinema; $i++) {
    $row = $moviesessions_cinema->fetch_assoc();
    if ($i == 0){
        $default_cinema = $row['cinema_id']; //默认为第一个
    }
}

//所有这个电影有的日期
if(!$getcinema){
    $datequery = "SELECT DISTINCT `date` FROM `movsessions` WHERE `movie_id` = '".$getid."' AND `cinema_id` = '".$default_cinema."' ORDER BY `date`";
}
else{
    $datequery = "SELECT DISTINCT `date` FROM `movsessions` WHERE `movie_id` = '".$getid."' AND `cinema_id` = '".$getcinema."' ORDER BY `date`";
}
$moviesessions_date = $db->query($datequery);
$no_records_date = $moviesessions_date->num_rows;
$select_date = "";
for ($i=0; $i<$no_records_date; $i++) {
    $row = $moviesessions_date->fetch_assoc();
    if (strpos($select_date, $row['date']) === false){
        $select_date = $select_date.'<option value="'.$row['date'].'">'.$row['date'].'</option>';
    }
    if ($i == 0){
        $default_date = $row['date']; //默认为第一个
    }
}
// echo "<br>select date: ".$select_date;

//所有这个日期的时间
if (!$getdate){
    if(!$getcinema){
        $timequery = "SELECT DISTINCT `time` FROM `movsessions` WHERE `movie_id` = '".$getid."' AND `date` = '".$default_date."' ORDER BY `time`";
    }
    else{
        $timequery = "SELECT DISTINCT `time` FROM `movsessions` WHERE `movie_id` = '".$getid."' AND `cinema_id` = '".$getcinema."' AND `date` = '".$default_date."' ORDER BY `time`";
    }
    //$getdate = $default_date;
}
else{
    if(!$getcinema){
        $timequery = "SELECT DISTINCT `time` FROM `movsessions` WHERE `movie_id` = '".$getid."' AND `date` = '".$getdate."' ORDER BY `time`";
    }
    else{
        $timequery = "SELECT DISTINCT `time` FROM `movsessions` WHERE `movie_id` = '".$getid."' AND `cinema_id` = '".$getcinema."' AND `date` = '".$getdate."' ORDER BY `time`";
    }
}

$moviesessions_time = $db->query($timequery);
$no_records_time = $moviesessions_time->num_rows;
$select_time = "";
for ($i=0; $i<$no_records_time; $i++) {
    $row = $moviesessions_time->fetch_assoc();
    if (strpos($select_time, $row['time']) === false){
        $select_time = $select_time.'<option value="'.$row['time'].'">'.$row['time'].'</option>';
    }
    if ($i == 0){
        $default_time = $row['time']; //默认为第一个
    }
}
// echo "<br>select time: ".$select_time;
// echo "<br>default time: ".$default_time;

//get movie name and url
$moviequery = "SELECT * FROM `movies` WHERE `id` = '".$getid."'";
$moviedetails = $db->query($moviequery)->fetch_object();
$moviename = $moviedetails->movie_name; 
$movieurl = $moviedetails->picture_url; 

$query = "SELECT * FROM `movsessions` WHERE `movie_id` = '".$getid."'";
if ($getcinema){
    $query = $query." AND `cinema_id` = '".$getcinema."'";
}
else{
    $query = $query." AND `cinema_id` = '".$default_cinema."'";
}
if ($getdate){
    $query = $query." AND `date` = '".$getdate."'";
}
else{
    $query = $query." AND `date` = '".$default_date."'";
}
if ($gettime){
    $query = $query." AND `time` = '".$gettime."'";
}
else{
    $query = $query." AND `time` = '".$default_time."'";
}

// echo $getid.'<br>';
// echo $getcinema.'<br>';
// echo $getdate.'<br>';
// echo $gettime.'<br>';
// echo "<br>".$query;
$moviesessions = $db->query($query);
$no_records = $moviesessions->num_rows;
$seats = "";
for ($j=0; $j<$no_records; $j++) {
    $seatrow = $moviesessions->fetch_assoc();
    //电影id，影院id，日期，时间已唯一确定一场电影
    if ($j=='0'){
        $price = $seatrow['price'];
        // echo "<br>".$price;
    }
    //start row
    if (in_array($j, [0, 10, 20, 30, 40])){
        $seats = $seats.'<div class="row">
        ';
    }
    //set seat
    if ($seatrow['status']=="Available"){
        $seats = $seats.'<div class="seat" id="'.$seatrow['seat_id'].'"></div>
        ';
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

$db->close();
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
        <div class="leftcolumn">
            <div class="movie-poster">
                <img class="booking-poster" alt="movie poster" src=".<?=$movieurl?>">
            </div>
        </div>
        <div class="main-right">
        <form id="bookingselection" method="post" action="">
            <div class="movie_container">
                <p class="movie-name"><a><?=$moviename?></a></p>
                <input id="movie-page-card" name="movie-page-card" value="<?=$getid?>" style="display: none;"/>
                <div class="booking-cinema-select">
                    <label for="movie-page-cinema">Cinema: </label>
                    <!-- <input id="movie-page-cinema" name="movie-page-cinema" value="" style="display: none;"/> -->
                    <select name="movie-page-cinema" id="movie-page-cinema" value="<?=$getcinema?>" onchange="submitmovieForm('BookingPage.php',this.value,'','')">
                        <option value="1">Marina</option>
                        <option value="2">Downtown</option>
                        <option value="3">Boonlay</option>
                    </select>
                </div>
                <div class="booking-date-select">
                    <label for="movie-page-date">Date: </label>
                    <!-- <input id="movie-page-cinema" name="movie-page-cinema" value="" style="display: none;"/> -->
                    <select name="movie-page-date" id="movie-page-date" value="<?=$getdate?>" onchange="submitmovieForm('BookingPage.php',<?=$getcinema?>,this.value,'')">
                        <?=$select_date?>
                    </select>
                </div>
                <div class="booking-time-select">
                    <label for="movie-page-time">Time: </label>
                    <!-- <input id="movie-page-cinema" name="movie-page-cinema" value="" style="display: none;"/> -->
                    <select name="movie-page-time" id="movie-page-time" value="<?=$gettime?>" onchange="submitmovieForm('BookingPage.php',<?=$getcinema?>,'<?=$getdate?>',this.value)">
                        <?=$select_time?>
                    </select>
                </div>

                <script>
                    function submitmovieForm(action,cinema,date,time) {
                        document.getElementById("movie-page-cinema").value = cinema;
                        document.getElementById("movie-page-date").value = date;
                        document.getElementById("movie-page-time").value = time;
                        document.getElementById("bookingselection").action = action;
                        document.getElementById("bookingselection").submit();
                    }
                </script>
            </div>

            <div>
                <input id="movie" name="movie" value="<?=$price?>" style="display: none;"/>
                <input id="seats" name="seats" value="" style="display: none;"/>
                <input id="seatnum" name="seatnum" value="" style="display: none;"/>
                <ul class="showcase">
                    <li>
                        <div class="seat"></div>
                        <i>Available</i>
                    </li>
                    <li>
                        <div class="seat selected"></div>
                        <i>Selected</i>
                    </li>
                    <li>
                        <div class="seat occupied"></div>
                        <i>Occupied/Unavailable</i>
                    </li>
                </ul>
            </div>
            
            <div class="container">
                <div class="screen">
                </div>
            
                <?=$seats?>
        
            </div>
            <div>
                <p>Your selected <span class="count">1</span> seats, totally S$ <span class="total">100</span></p>
            </div>
            <div class="proceed">
                <div class="pay">
                    <button class="paybutton" onclick="place_order('PaymentSuccessfulPage.php')">proceed to Pay</button>
                </div>
                <div class="addcart">
                    <a onclick="add_cart()"><img src="./img/cart.png" width="30" height="30"></a>
                </div>
            </div>
        </form>
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
    if (<?php echo (isset($getcinema)) ? 1 : 0 ?>){
        document.getElementById('movie-page-cinema').value = "<?= (int)$getcinema ?>";
    }
    else{
        document.getElementById('movie-page-cinema').value = "<?= (int)$default_cinema?>";
    }
    if (<?php echo (isset($getdate)) ? 1 : 0?>){
        document.getElementById('movie-page-date').value = "<?=$getdate?>";
    }
    else{
        document.getElementById('movie-page-date').value = "<?=$default_date?>";
    }
    if (<?php echo (isset($gettime)) ? 1 : 0?>){
        document.getElementById('movie-page-time').value = "<?=$gettime?>";
    }
    else{
        document.getElementById('movie-page-time').value = "<?=$default_time?>";
    }
</script>
<script src="./seatselect.js"></script>
<script src="./bookingproceed.js"></script>
</body>
</html>
