<!DOCTYPE html>
<html lang="en">
<head>
<title>MZ Cinema</title>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div id="wrapper">
  <div class="top">
    <div class="logo">
        <img src="./img/logo.png" width="120" height="40">
    </div>
    <div class="document">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#">Movies</a></li>
            <li><a href="#">Cinemas</a></li>
            <li><a href="#">Promotions</a></li>
            <li><a href="#">Dining</a></li> 
            <li><a href="#">Mine</a></li>
        </ul>
    </div>
    <div class="top-right">
    <div class="log">
        <div class="logbutton">
            <a href="#">Log in</a>
        </div>
    </div>
    <div class="cart">
        <a href="#"><img src="./img/cart.png" width="40" height="40"></a>
    </div>
    </div>
  </div>
  <div class="main">
      <div class="midView">
        <div class="leftcolumn">
            <form id='filterform' method="post" action="movies.php">
              <table border="0" align="center" id="filtertable">
                <tr>
                  <th colspan="2" align="center">Filter</th>
                <tr>
                  <td>Name</td>
                  <td align="center"><input type="text" name="myname" id="myName" placeholder="Name Keywords"></td>
                </tr>
                <tr>
                  <td>Cinema</td>
                  <td align="center"><select name="mycinema" id="myCinema">
                        <option value = "Null" selected="selected">Select Cinema</option>
                        <option value = "1">MZ Marina</option>
                        <option value = "2">MZ Downtown</option>
                        <option value = "3">MZ Boonlay</option>
                      </select></td>
                </tr>
                <tr>
                  <td>Date</td>
                  <td align="center"><input type="date" name="mydate" id="myDate" min=""></td>
                </tr>
                <tr>
                  <td>Genre</td>
                  <td align="center"><select name="mygenre" id="myGenre">
                        <option value = "Null" selected="selected">Select Genre</option>
                        <option value = "Action">Action</option>
                        <option value = "Animation">Animation</option>
                        <option value = "Comedy">Comedy</option>
                        <option value = "Crime">Crime</option>
                        <option value = "Drama">Drama</option>
                        <option value = "Fantasy">Fantasy</option>
                        <option value = "Horror">Horror</option>
                        <option value = "Romance">Romance</option>
                        <option value = "Supernatural">Supernatural</option>
                        <option value = "Thriller">Thriller</option>
                      </select></td>
                </tr>
                <tr>
                  <td align="center"><input type="reset" value="Clear"></td>
                  <td align="center"><input type="submit" value="Search"></td>
                </tr>
              </table>
            </form>
        </div>
        <div class="rightcolumn">
        <?php
          //error_reporting(0);
          $name=Null;
          $cinema=Null;
          $date=Null;
          $genre=Null;
          $name=$_POST['myname'];
          $cinema=$_POST['mycinema'];
          $date=$_POST['mydate'];
          $genre=$_POST['mygenre'];
          echo "1".$name;
          echo "2".$cinema;
          echo "3".$date;
          echo "4".$genre;

          @ $db = new mysqli('localhost', 'root', '', 'mzcinema');
          if (mysqli_connect_errno()) {
                  echo "<br>Error: Could not connect to database. Please try again later.";
                  exit;
          }
          if (!$name && !$cinema && !$date && !$genre) {
            echo "<h2>What's on</h2><br>".'<div class="content">';
            $query = 'select * from movies';
            $movies = $db->query($query);
            $no_records = $movies->num_rows;
          }
          else{
            echo '<h2>Filtered Result</h2><br>
            <div class="content">';
            if ($name && ($genre != 'Null')){
              $query = "select * from `movies` where `movie_name` like '%".$name."%' and `genre1` = '".$genre."' or `genre2` = '".$genre."'";
            }
            else if ($name && ($genre == 'Null')){
              $query = "select * from `movies` where `movie_name` like '%".$name."%'";
            }
            else if (!$name && ($genre != 'Null')){
              $query = "select * from `movies` where `genre1` = '".$genre."' or `genre2` = '".$genre."'";
            }
            else{
              if ($date && ($cinema != 'Null')){
                $sessionquery = 'select DISTINCT `movie_id` from `movsessions` where `movie_id` = "'.$row['id'].'" and `date` = "'.$date.'" and `cinema_id` = "'.$cinema.'" ORDER BY `date`';
              }
              else if ($date && ($cinema == 'Null')){
                $sessionquery = 'select DISTINCT `movie_id` from `movsessions` where `movie_id` = "'.$row['id'].'" and `date` = "'.$date.'" ORDER BY `date`';
              }
              else if (!$date && ($cinema != 'Null')){
                $sessionquery = 'select DISTINCT `movie_id` from `movsessions` where `movie_id` = "'.$row['id'].'" and `cinema_id` = "'.$cinema.'" ORDER BY `date`';
              }
              else{
                $sessionquery = 'select DISTINCT `movie_id` from `movsessions` where `movie_id` = "'.$row['id'].'" ORDER BY `date`';
              }
              $query = 'select * from movies';
            }
            echo $query;
            $movies = $db->query($query);
            $no_records = $movies->num_rows;
          }
                for ($i=0; $i<$no_records; $i++) {
                    $row = $movies->fetch_assoc();
                    if ($date && ($cinema != 'Null')){
                      $sessionquery = 'select DISTINCT `cinema_id`, `date` from `movsessions` where `movie_id` = "'.$row['id'].'" and `date` = "'.$date.'" and `cinema_id` = "'.$cinema.'" ORDER BY `date`';
                    }
                    else if ($date && ($cinema == 'Null')){
                      $sessionquery = 'select DISTINCT `cinema_id`, `date` from `movsessions` where `movie_id` = "'.$row['id'].'" and `date` = "'.$date.'" ORDER BY `date`';
                    }
                    else if (!$date && ($cinema != 'Null')){
                      $sessionquery = 'select DISTINCT `cinema_id`, `date` from `movsessions` where `movie_id` = "'.$row['id'].'" and `cinema_id` = "'.$cinema.'" ORDER BY `date`';
                    }
                    else{
                      $sessionquery = 'select DISTINCT `cinema_id`, `date` from `movsessions` where `movie_id` = "'.$row['id'].'" ORDER BY `date`';
                    }
                    $sessions = $db->query($sessionquery);
                    $no_sessions = $sessions->num_rows;
                    $cinemas = 'Availabe Cinemas: ';
                    $dates = 'Availabe Dates: ';
                    for ($j=0; $j<$no_sessions; $j++) {
                      $session = $sessions->fetch_assoc();
                      switch ($session['cinema_id']) {
                        case '1':
                          if (strpos('MZ Marina', $cinemas) === false){
                            $cinemas = $cinemas.'MZ Marina';}
                          break;
                        case '2':
                          if (strpos('MZ Downtown', $cinemas) === false){
                            $cinemas = $cinemas.'MZ Downtown';}
                          break;
                        case '3':
                          if (strpos('MZ Boonlay', $cinemas) === false){
                            $cinemas = $cinemas.'MZ Boonlay';}
                          break;
                        default:
                            echo "Error in sessions of movie: ".$row['movie_name'];
                        }
                      if ($j != $no_sessions-1){
                        $cinemas = $cinemas.', ';
                      }
                      if (strpos($session['date'], $dates) === false){
                        $dates = $dates.$session['date'];}
                      if ($j != $no_sessions-1){
                        $dates = $dates.', ';
                      }
                    }

                    echo '
                        <div class="col-3">
                            <div class="movie-card">
                                <div class="movie-poster">
                                    <a href="#"><img class="poster" alt="movie poster" src=".'.$row['picture_url'].'"></a>
                                </div>
                                <div class="short-details">
                                    <p class="movie-name"><a href="#">'.$row['movie_name'].'</a></p>
                                    <p class="movie-description">'.$row['genre1'].', '.$row['genre2'].' | '.$row['runtime'].' | '.$row['language'].'</p>
                                    <p class="movie-description">'.$cinemas.'</p>
                                    <p class="movie-description">'.$dates.'</p>
                                </div>
                            </div>
                        </div>
                    ';
                }
          $db->close();
          ?>
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
