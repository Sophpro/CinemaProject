<!DOCTYPE html>
<html lang="en">
<head>
<title>MZ Cinema Movies</title>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
</head>
<body>
<script>
    function clearSelection() { //此处有1bug，从index page选择并进入movie page时不能clear
      document.getElementById('myName').value = "";
      document.getElementById('myCinema').value = "Null";
      document.getElementById('myDate').value = "";
      document.getElementById('myGenre').value = "Null";
    }
</script>
<div id="wrapper">
  <div class="top">
    <div class="logo">
        <img src="./img/logo.png" width="120" height="40">
    </div>
    <div class="document">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="MoviesPage.php" class="active">Movies</a></li>
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
      <div class="midView">
        <div class="leftcolumn">
            <form id='filterform' method="post" action="MoviesPage.php">
              <table border="0" align="center" id="filtertable">
                <tr>
                  <th colspan="2" align="center">Filter</th>
                <tr>
                  <td>Name</td>
                  <td align="center"><input type="text" name="myname" id="myName" placeholder="Name Keywords" value="<?php echo isset($_POST['myname']) ? htmlspecialchars($_POST['myname']) : ''; ?>"></td>
                </tr>
                <tr>
                  <td>Cinema</td>
                  <td align="center"><select name="mycinema" id="myCinema">
                        <option value = "Null" <?php if (isset($_POST['mycinema']) && $_POST['mycinema'] == 'Null') echo 'selected="selected"'; ?>>Select Cinema</option>
                        <option value = "1" <?php if (isset($_POST['mycinema']) && $_POST['mycinema'] == '1') echo 'selected="selected"'; ?>>MZ Marina</option>
                        <option value = "2" <?php if (isset($_POST['mycinema']) && $_POST['mycinema'] == '2') echo 'selected="selected"'; ?>>MZ Downtown</option>
                        <option value = "3" <?php if (isset($_POST['mycinema']) && $_POST['mycinema'] == '3') echo 'selected="selected"'; ?>>MZ Boonlay</option>
                      </select></td>
                </tr>
                <tr>
                  <td>Date</td>
                  <td align="center"><input type="date" name="mydate" id="myDate" min="<?=date('Y-m-d')?>" value="<?php echo isset($_POST['mydate']) ? htmlspecialchars($_POST['mydate']) : ''; ?>"></td>
                </tr>
                <tr>
                  <td>Genre</td>
                  <td align="center"><select name="mygenre" id="myGenre">
                        <option value = "Null" <?php if (isset($_POST['mygenre']) && $_POST['mygenre'] == 'Null') echo 'selected="selected"'; ?>>Select Genre</option>
                        <option value = "Action" <?php if (isset($_POST['mygenre']) && $_POST['mygenre'] == 'Action') echo 'selected="selected"'; ?>>Action</option>
                        <option value = "Animation" <?php if (isset($_POST['mygenre']) && $_POST['mygenre'] == 'Animation') echo 'selected="selected"'; ?>>Animation</option>
                        <option value = "Comedy" <?php if (isset($_POST['mygenre']) && $_POST['mygenre'] == 'Comedy') echo 'selected="selected"'; ?>>Comedy</option>
                        <option value = "Crime" <?php if (isset($_POST['mygenre']) && $_POST['mygenre'] == 'Crime') echo 'selected="selected"'; ?>>Crime</option>
                        <option value = "Drama" <?php if (isset($_POST['mygenre']) && $_POST['mygenre'] == 'Drama') echo 'selected="selected"'; ?>>Drama</option>
                        <option value = "Fantasy" <?php if (isset($_POST['mygenre']) && $_POST['mygenre'] == 'Fantasy') echo 'selected="selected"'; ?>>Fantasy</option>
                        <option value = "Horror" <?php if (isset($_POST['mygenre']) && $_POST['mygenre'] == 'Horror') echo 'selected="selected"'; ?>>Horror</option>
                        <option value = "Romance" <?php if (isset($_POST['mygenre']) && $_POST['mygenre'] == 'Romance') echo 'selected="selected"'; ?>>Romance</option>
                        <option value = "Supernatural" <?php if (isset($_POST['mygenre']) && $_POST['mygenre'] == 'Supernatural') echo 'selected="selected"'; ?>>Supernatural</option>
                        <option value = "Thriller" <?php if (isset($_POST['mygenre']) && $_POST['mygenre'] == 'Thriller') echo 'selected="selected"'; ?>>Thriller</option>
                      </select></td>
                </tr>
                <tr>
                  <td align="center"><button class="filterbutton" onclick="clearSelection()">Clear</button></td>
                  <td align="center"><button class="filterbutton" type="submit">Search</button></td>
                </tr>
              </table>
            </form>
        </div>
        <div class="rightcolumn">
        <?php
          error_reporting(0);
          date_default_timezone_set('Asia/Singapore');
          $name=Null;
          $cinema=Null;
          $date=Null;
          $genre=Null;
          $name=$_POST['myname'];
          $cinema=$_POST['mycinema'];
          $date=$_POST['mydate'];
          $genre=$_POST['mygenre'];
          // echo "1".$name;
          // echo "2".$cinema;
          // echo "3".$date;
          // echo "4".$genre;

          @ $db = new mysqli('localhost', 'root', '', 'mzcinema');
          if (mysqli_connect_errno()) {
                  echo "<br>Error: Could not connect to database. Please try again later.";
                  exit;
          }
          if ((!$name && !$cinema && !$date && !$genre)||(!$name && ($cinema=='Null') && !$date && ($genre=='Null'))) {
            echo '<h2>';
            $query = 'select * from movies';
            $movies = $db->query($query);
            $no_records = $movies->num_rows;
            echo $no_records.' movies are on showing!</h2><br><div class="content">';
          }
          else{
            echo '<h2>Filter Result in ';
            //有date或有cinema
            $subquery_add = '';
            if ($date || ($cinema != 'Null')){
              //有date，有cinema
              if ($date && ($cinema != 'Null')){
                $prequery = 'select DISTINCT `movie_id` from `movsessions` where `date` = "'.$date.'" and `cinema_id` = "'.$cinema.'" and `date` >= "'.date('Y-m-d').'" ORDER BY `movie_id`';
              }
              //有date，无cinema
              else if ($date && ($cinema == 'Null')){
                $prequery = 'select DISTINCT `movie_id` from `movsessions` where `date` = "'.$date.'" and `date` >= "'.date('Y-m-d').'" ORDER BY `movie_id`';
              }
              //无date，有cinema
              else if (!$date && ($cinema != 'Null')){
                $prequery = 'select DISTINCT `movie_id` from `movsessions` where `cinema_id` = "'.$cinema.'" and `date` >= "'.date('Y-m-d').'" ORDER BY `movie_id`';
              }
              $premovies = $db->query($prequery);
              $pre_no_records = $premovies->num_rows;
              if ($pre_no_records != '0'){
                $subquery = 'select * from `movies` where `id` = "';
                $subquery_add = '';
                for ($p=0; $p<$pre_no_records; $p++){
                  $pre_row = $premovies->fetch_assoc();
                  $subquery_add = $subquery_add.$pre_row['movie_id'].'"';
                  if ($p != $pre_no_records-1){
                    $subquery_add = $subquery_add.' or `id` = "';
                  }
                }
                $subquery = $subquery.$subquery_add;
              }
              else{
                $subquery_add = '0"';
                $subquery = 'select * from `movies` where `id` = "'.$subquery_add;
              }

            }
            //有name或有genre
            if ($name || ($genre != 'Null')){
              //有name，有genre
              if ($name && ($genre != 'Null')){
                if ($subquery_add != ''){
                  $query = 'select * from `movies` where (`id` = "'.$subquery_add.") and `movie_name` like '%".$name."%' and (`genre1` = '".$genre."' or `genre2` = '".$genre."')";
                }
                else{
                  $query = "select * from `movies` where `movie_name` like '%".$name."%' and (`genre1` = '".$genre."' or `genre2` = '".$genre."')";
                }
              }
              //有name，无genre
              else if ($name && ($genre == 'Null')){
                if ($subquery_add != ''){
                  $query = 'select * from `movies` where (`id` = "'.$subquery_add.") and `movie_name` like '%".$name."%'";
                }
                else{
                  $query = "select * from `movies` where `movie_name` like '%".$name."%'";
                }
              }
              //无name，有genre
              else if (!$name && ($genre != 'Null')){
                if ($subquery_add != ''){
                  $query = 'select * from `movies` where (`id` = "'.$subquery_add.") and (`genre1` = '".$genre."' or `genre2` = '".$genre."')";
                }
                else{
                  $query = "select * from `movies` where (`genre1` = '".$genre."' or `genre2` = '".$genre."')";
                }
                }
            }
            //无name无genre
            else{
              $query = $subquery;
            }
            //echo $query;
            $movies = $db->query($query);
            $no_records = $movies->num_rows;
            echo $no_records.' Movies</h2><br>
            <div class="content">';
          }
                for ($i=0; $i<$no_records; $i++) {
                    $row = $movies->fetch_assoc();
                    $sessionquery = 'select DISTINCT `cinema_id`, `date`, `time` from `movsessions` where `movie_id` = "'.$row['id'].'" and `date` >= "'.date('Y-m-d').'" ORDER BY `date`';
                    $sessions = $db->query($sessionquery);
                    $no_sessions = $sessions->num_rows;
                    $cinemas = 'Availabe Cinemas: ';
                    $dates = 'Availabe Dates: ';
                    $cinematable = '<tr style="background:#ffffff" id="select_row';
                    $table1 = '<tr><th colspan="100">Date and time choices at MZ Marina</th></tr>';
                    $table2 = '<tr><th colspan="100">Date and time choices at MZ Downtown</th></tr>';
                    $table3 = '<tr><th colspan="100">Date and time choices at MZ Boonlay</th></tr>';
                    for ($j=0; $j<$no_sessions; $j++) {
                      $session = $sessions->fetch_assoc();
                      switch ($session['cinema_id']) {
                        case '1':
                          //如果这个cinema还没出现过
                          if (strpos($cinemas, 'MZ Marina') === false){
                            //如果这不是第一个cinema
                            if ($j != '0'){
                              $cinemas = $cinemas.', ';
                            }
                            $cinemas = $cinemas.'MZ Marina';}
                          //add to table 1
                          //如果这个date还没出现过
                          if (strpos($table1, $session['date']) === false){
                            //如果是第一个date
                            if (substr($table1, strpos($table1, '</th></tr>')) === '</th></tr>'){
                              $table1=$table1.'<tr><td>'.$session['date'].'</td>';
                              if (strpos($table1, $session['time']) === false){
                                $table1=$table1.'<td class="time_selected" onclick="submitmovieForm'.$row['id'].'('."'".$session['cinema_id']."','".$session['date']."','".$session['time']."'".')">'.$session['time'].'</td>';
                              }
                            }
                            else{
                              $table1=$table1.'</tr><tr><td>'.$session['date'].'</td>';
                              if (strpos($table1, $session['time']) === false){
                                $table1=$table1.'<td class="time_selected" onclick="submitmovieForm'.$row['id'].'('."'".$session['cinema_id']."','".$session['date']."','".$session['time']."'".')">'.$session['time'].'</td>';
                              }
                            }
                          }
                          //如果这个date已经出现过，因为按date排序，所以这个time一定是同一天，直接加在后面
                          else if (strpos($table1, $session['time']) === false){
                            $table1=$table1.'<td class="time_selected" onclick="submitmovieForm'.$row['id'].'('."'".$session['cinema_id']."','".$session['date']."','".$session['time']."'".')">'.$session['time'].'</td>';
                          }
                          break;
                        case '2':
                          if (strpos($cinemas, 'MZ Downtown') === false){
                            if ($j != '0'){
                              $cinemas = $cinemas.', ';
                            }
                            $cinemas = $cinemas.'MZ Downtown';}
                          if (strpos($table2, $session['date']) === false){
                            if (substr($table2, strpos($table2, '</th></tr>')) === '</th></tr>'){
                              $table2=$table2.'<tr><td>'.$session['date'].'</td>';
                              if (strpos($table2, $session['time']) === false){
                                $table2=$table2.'<td class="time_selected" onclick="submitmovieForm'.$row['id'].'('."'".$session['cinema_id']."','".$session['date']."','".$session['time']."'".')">'.$session['time'].'</td>';
                              }
                            }
                            else{
                              $table2=$table2.'</tr><tr><td>'.$session['date'].'</td>';
                              if (strpos($table2, $session['time']) === false){
                                $table2=$table2.'<td class="time_selected" onclick="submitmovieForm'.$row['id'].'('."'".$session['cinema_id']."','".$session['date']."','".$session['time']."'".')">'.$session['time'].'</td>';
                              }
                            }
                          }
                          else if (strpos($table2, $session['time']) === false){
                            $table2=$table2.'<td class="time_selected" onclick="submitmovieForm'.$row['id'].'('."'".$session['cinema_id']."','".$session['date']."','".$session['time']."'".')">'.$session['time'].'</td>';
                          }
                          break;
                        case '3':
                          if (strpos($cinemas, 'MZ Boonlay') === false){
                            if ($j != '0'){
                              $cinemas = $cinemas.', ';
                            }
                            $cinemas = $cinemas.'MZ Boonlay';}
                            if (strpos($table3, $session['date']) === false){
                              if (substr($table3, strpos($table3, '</th></tr>')) === '</th></tr>'){
                                $table3=$table3.'<tr><td>'.$session['date'].'</td>';
                                if (strpos($table3, $session['time']) === false){
                                  $table3=$table3.'<td class="time_selected" onclick="submitmovieForm'.$row['id'].'('."'".$session['cinema_id']."','".$session['date']."','".$session['time']."'".')">'.$session['time'].'</td>';
                                }
                              }
                              else{
                                $table3=$table3.'</tr><tr><td>'.$session['date'].'</td>';
                                if (strpos($table3, $session['time']) === false){
                                  $table3=$table3.'<td class="time_selected" onclick="submitmovieForm'.$row['id'].'('."'".$session['cinema_id']."','".$session['date']."','".$session['time']."'".')">'.$session['time'].'</td>';
                                }
                              }
                            }
                            else if (strpos($table3, $session['time']) === false){
                              $table3=$table3.'<td class="time_selected" onclick="submitmovieForm'.$row['id'].'('."'".$session['cinema_id']."','".$session['date']."','".$session['time']."'".')">'.$session['time'].'</td>';
                            }
                          break;
                        default:
                            echo "Error in sessions of movie: ".$row['movie_name'];
                      }
                      if (strpos($dates, $session['date']) == false){
                        if ($j != '0'){
                          $dates = $dates.', ';
                        }
                        $dates = $dates.$session['date'];
                      }
                    }
                    
                    echo '
                        <div class="col-1">
                        <form id="movieform'.$row['id'].'" method="post" action="BookingPage.php">
                        <input name="movie-page-card" value="'.$row['id'].'" style="display: none;"/>
                        <input id="movie-page-cinema-'.$row['id'].'" name="movie-page-cinema" value="" style="display: none;"/>
                        <input id="movie-page-date-'.$row['id'].'" name="movie-page-date" value="" style="display: none;"/>
                        <input id="movie-page-time-'.$row['id'].'" name="movie-page-time" value="" style="display: none;"/>
                            <div class="movie-card-hori">
                                <div class="movie-poster-left">
                                    <img class="poster-left" alt="movie poster" src=".'.$row['picture_url'].'">
                                </div>
                                <div class="short-details-right">
                                    <p class="movie-name"><a>'.$row['movie_name'].'</a></p>
                                    <p class="movie-description">'.$row['genre1'].', '.$row['genre2'].' | '.$row['runtime'].' | '.$row['language'].'</p>
                                    <p class="movie-description">Cast: '.$row['actor'].'</p>
                                    <p class="movie-description">'.$cinemas.'</p>
                                    <p class="movie-description">'.$dates.'</p>
                                </div>
                            </div>
                            <div class="select-box">
                                  <div class="cinema-select">
                                      <table border="0" class="cinema-select-table">';
                    $none1 = (substr($table1, strpos($table1, '</th></tr>')) === '</th></tr>');
                    $none2 = (substr($table2, strpos($table2, '</th></tr>')) === '</th></tr>');
                    $none3 = (substr($table3, strpos($table3, '</th></tr>')) === '</th></tr>');
                    //如果1无场次
                    if ($none1){
                      $table1 = $table1.'</tr><tr><td>None</td>';
                      //1无场次，2也无场次，默认设为3
                      if ($none2){
                        $table2 = $table2.'</tr><tr><td>None</td>';
                        echo '<tr id="select_row1_'.$row['id'].'"><td><input type="button" value = "MZ Marina" class="cinema_selected" id="select_1_'.$row['id'].'" onclick="showTable_'.$row['id'].'('."'select_1_".$row['id']."'".')"/></td></tr>
                              <tr id="select_row2_'.$row['id'].'"><td><input type="button" value = "MZ Downtown" class="cinema_selected" id="select_2_'.$row['id'].'" onclick="showTable_'.$row['id'].'('."'select_2_".$row['id']."'".')"/></td></tr>
                              <tr style="background:#ffffff" id="select_row3_'.$row['id'].'"><td><input type="button" value = "MZ Boonlay" class="cinema_selected" id="select_3_'.$row['id'].'" onclick="showTable_'.$row['id'].'('."'select_3_".$row['id']."'".')"/></td></tr>
                              </table>
                                  </div>
                                  <div class="datetime-select">
                                      <table border="0" id="tableMZMarina_'.$row['id'].'" class="datetime-select-table" style="display: none;">
                                      '.$table1.'</tr>
                                      </table>
                                      <table border="0" id="tableMZDowntown_'.$row['id'].'" class="datetime-select-table" style="display: none;">
                                      '.$table2.'</tr>
                                      </table>
                                      <table border="0" id="tableMZBoonlay_'.$row['id'].'" class="datetime-select-table" style="display: inline;">
                                      '.$table3.'</tr>
                                      </table>
                                    </div>
                              </div>';
                      }
                      //1无场次，2有场次，默认设为2
                      else{
                        //提前判断1无场次，2有场次，3无场次
                        if ($none3){
                          $table3 = $table3.'</tr><tr><td>None</td>';
                        }
                        echo '<tr id="select_row1_'.$row['id'].'"><td><input type="button" value = "MZ Marina" class="cinema_selected" id="select_1_'.$row['id'].'" onclick="showTable_'.$row['id'].'('."'select_1_".$row['id']."'".')"/></td></tr>
                              <tr style="background:#ffffff" id="select_row2_'.$row['id'].'"><td><input type="button" value = "MZ Downtown" class="cinema_selected" id="select_2_'.$row['id'].'" onclick="showTable_'.$row['id'].'('."'select_2_".$row['id']."'".')"/></td></tr>
                              <tr id="select_row3_'.$row['id'].'"><td><input type="button" value = "MZ Boonlay" class="cinema_selected" id="select_3_'.$row['id'].'" onclick="showTable_'.$row['id'].'('."'select_3_".$row['id']."'".')"/></td></tr>
                              </table>
                                  </div>
                                  <div class="datetime-select">
                                      <table border="0" id="tableMZMarina_'.$row['id'].'" class="datetime-select-table" style="display: none;">
                                      '.$table1.'</tr>
                                      </table>
                                      <table border="0" id="tableMZDowntown_'.$row['id'].'" class="datetime-select-table" style="display: inline;">
                                      '.$table2.'</tr>
                                      </table>
                                      <table border="0" id="tableMZBoonlay_'.$row['id'].'" class="datetime-select-table" style="display: none;">
                                      '.$table3.'</tr>
                                      </table>
                                    </div>
                              </div>';
                      }
                    }
                    //1有场次，默认设为1
                    else{
                      if ($none2){
                        $table2 = $table2.'</tr><tr><td>None</td>';
                      }
                      if ($none3){
                        $table3 = $table3.'</tr><tr><td>None</td>';
                      }
                      echo '<tr style="background:#ffffff" id="select_row1_'.$row['id'].'"><td><input type="button" value = "MZ Marina" class="cinema_selected" id="select_1_'.$row['id'].'" onclick="showTable_'.$row['id'].'('."'select_1_".$row['id']."'".')"/></td></tr>
                                        <tr id="select_row2_'.$row['id'].'"><td><input type="button" value = "MZ Downtown" class="cinema_selected" id="select_2_'.$row['id'].'" onclick="showTable_'.$row['id'].'('."'select_2_".$row['id']."'".')"/></td></tr>
                                        <tr id="select_row3_'.$row['id'].'"><td><input type="button" value = "MZ Boonlay" class="cinema_selected" id="select_3_'.$row['id'].'" onclick="showTable_'.$row['id'].'('."'select_3_".$row['id']."'".')"/></td></tr>
                                      </table>
                                  </div>
                                  <div class="datetime-select">
                                      <table border="0" id="tableMZMarina_'.$row['id'].'" class="datetime-select-table" style="display: inline;">
                                      '.$table1.'</tr>
                                      </table>
                                      <table border="0" id="tableMZDowntown_'.$row['id'].'" class="datetime-select-table" style="display: none;">
                                      '.$table2.'</tr>
                                      </table>
                                      <table border="0" id="tableMZBoonlay_'.$row['id'].'" class="datetime-select-table" style="display: none;">
                                      '.$table3.'</tr>
                                      </table>
                                    </div>
                              </div>';

                    }
                    
                    echo '
                            </form>            
                              <script>
                                  function showTable_'.$row['id'].'(tableid){
                                    var selected = document.getElementById(tableid);
                                    var cinema1 = document.getElementById("tableMZMarina_'.$row['id'].'");
                                    var cinema2 = document.getElementById("tableMZDowntown_'.$row['id'].'");
                                    var cinema3 = document.getElementById("tableMZBoonlay_'.$row['id'].'");
                                    var row1 = document.getElementById("select_row1_'.$row['id'].'");
                                    var row2 = document.getElementById("select_row2_'.$row['id'].'");
                                    var row3 = document.getElementById("select_row3_'.$row['id'].'");
                                    if(selected.value=="MZ Marina"){
                                      cinema1.style.display = "inline";
                                      cinema2.style.display = "none";
                                      cinema3.style.display = "none";
                                      row1.style.background = "#ffffff";
                                      row2.style.background = "#bbbbce";
                                      row3.style.background = "#bbbbce";
                                    }
                                    else if (selected.value=="MZ Downtown"){
                                      cinema2.style.display = "inline";
                                      cinema1.style.display = "none";
                                      cinema3.style.display = "none";	
                                      row2.style.background = "#ffffff";
                                      row1.style.background = "#bbbbce";
                                      row3.style.background = "#bbbbce";	
                                    }
                                    else if (selected.value=="MZ Boonlay"){
                                      cinema3.style.display = "inline";
                                      cinema1.style.display = "none";
                                      cinema2.style.display = "none";	
                                      row3.style.background = "#ffffff";
                                      row1.style.background = "#bbbbce";
                                      row2.style.background = "#bbbbce";	
                                    }
                                    else{
                                      cinema1.style.display = "inline";
                                      cinema2.style.display = "none";
                                      cinema3.style.display = "none";	
                                      row1.style.background = "#ffffff";
                                      row2.style.background = "#bbbbce";
                                      row3.style.background = "#bbbbce";
                                    }
                                  }
                                  function submitmovieForm'.$row['id'].'(cinema, date, time) {
                                    document.getElementById("movie-page-cinema-'.$row['id'].'").value = cinema;
                                    document.getElementById("movie-page-date-'.$row['id'].'").value = date;
                                    document.getElementById("movie-page-time-'.$row['id'].'").value = time;
                                    document.getElementById("movieform'.$row['id'].'").submit();
                                  }
                              </script>
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
