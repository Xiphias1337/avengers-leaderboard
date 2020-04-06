<?php
session_start();

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/iconLogo.jpg">
  <link rel="icon" type="image/png" href="./assets/img/iconLogo.jpg">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Gaming-Board</title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <link href="./assets/css/material-kit.css?v=2.1.1" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
</head>

<script>    document.getElementById("refreshButton").click();
</script>

<body class="index-page sidebar-collapse">
<!--   <nav class="navbar navbar-color-on-scroll navbar-transparent    fixed-top  navbar-expand-lg " color-on-scroll="100" id="sectionsNav">
    <div class="container">
      <div class="navbar-translate">
        <a class="navbar-brand" href="index.php">
          <img src="./assets/img/thomas.jpg" alt="Avengers" class="rounded img-fluid navBarLogo navbar-transparent"> </a> 
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="sr-only">Toggle navigation</span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a href="./index.php" class="nav-link">
              <i class="material-icons">home</i> Startseite
            </a>
          </li>
          <li class="dropdown nav-item">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
              <i class="material-icons">account_balance</i> Über uns
            </a>
            <div class="dropdown-menu dropdown-with-icons">
              <a href="./index.html#leistungen" class="dropdown-item">
                <i class="material-icons">build</i> Leistung
              </a>
              <a href="./index.html#team" class="dropdown-item">
                <i class="material-icons">people</i> Das Team
              </a>
              <a href="./stellenangebote.html" class="dropdown-item">
                <i class="material-icons">assignment</i> Stellenangebote
              </a>
            </div>
          </li>
          <li class="dropdown nav-item">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
              <i class="material-icons">format_quote</i> Referenzen
            </a>
            <div class="dropdown-menu dropdown-with-icons">
              <a href="./index.html#partner" class="dropdown-item">
                <i class="material-icons">supervised_user_circle</i> Unsere Partner
              </a>
              <a href="./index.html#referenzen" class="dropdown-item">
                <i class="material-icons">hourglass_empty</i> Aktuelle Bauvorhaben
              </a>
            </div>
          </li>
          <li class="nav-item">
            <a href="./kontakt.php" class="nav-link">
              <i class="material-icons">call</i> Kontakt
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav> -->
  <div class="page-header" data-parallax="true">
    <div class="pageHeaderCard card-raised ">
            <img class="d-block w-100" src="./assets/img/VS-Leaderboard.jpg" alt="Third slide">
    </div>
  </div>
  </div>


  <?php
  if (isset($_SESSION['userId'])) {
    $allUser = $_SESSION['allUsers'];
    echo '  <div class="main main-raised">
            <div class="section section-basic" id="stellenangebote">
              <div class="container">
                <div class="title">
                  <h2>Moin Meister</h2>
                </div>
                <div class="row">
                  <div class="col p" style="text-align: left">
                    Hier könnt ihr euren Skill vergleichen.
                  </div>             
                <div class="col p" style="text-align: right">
                  <form class="form" method="post" action="userSystem/loginSys.php">
                    <button type="submit" name="logout-submit" class="btn btn-primary btn-round">Abmelden</button>
                  </form>
                </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                  <h3>Leaderboard</h3>
                  <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th>Gamer</th>
                        <th>Elo</th>
                        <th>Wins</th>
                        <th>Losses</th>
                      </tr>
                    </thead>
                    <tbody>';
                    if(isset($_SESSION['allUsers'])){
                      foreach($allUser as $user){
                      echo '<tr> <td class="text-center">'.$user["placement"].'</td>
                      <td>'.$user["username"].'</td>
                      <td>'.$user["elo"].'</td>
                      <td>'.$user["wins"].'</td>
                      <td>'.$user["losses"].'</td> </tr>';
                    }
                    }
                   echo '</tbody>
                  </table><form class="form" method="post" action="userSystem/leaderboardSys.php">
                  <button type="submit" id="refreshButton" name="refreshPage" class="btn btn-primary btn-round">Refresh</button>
                </form>
                <button type="submit" data-toggle="modal" data-target="#addGamer" class="btn btn-primary btn-round">Gamer hinzufügen</button>
                </div>
                </div>
                <div class="col-md-6">
                <h3>Teambuilder</h3>
                <form class="form" method="post" action="userSystem/leaderboardSys.php">';

                if(isset($_SESSION['allUsers'])){
                  foreach($allUser as $user){
                  echo '<div class="form-check">
                  <label class="form-check-label">';
                  if(isset($_SESSION['teams'])) {
                    if(in_array($user["username"],$_SESSION['teams'][0]) || in_array($user["username"],$_SESSION['teams'][1])) {
                      echo '<input class="form-check-input" name="selectedUser[]" type="checkbox" value='.$user["username"].' checked>'.$user["username"].'<span class="form-check-sign">';
                    }else{
                      echo '<input class="form-check-input" name="selectedUser[]" type="checkbox" value='.$user["username"].'>'.$user["username"].'<span class="form-check-sign">';
                    }
                  }else{
                    echo '<input class="form-check-input" name="selectedUser[]" type="checkbox" value='.$user["username"].'>'.$user["username"].'<span class="form-check-sign">';
                  }
                  echo '<span class="check"></span>
                  </span>
                  </label>
                  </div>';
   
                }
                }
              
              echo '<div class="card-footer justify-content-center">
                        <button type="submit" name="generateTeams" class="btn btn-primary btn-round">Neue Teams
              </div>
              </form>
                </div>
                  </div>
                  <div class="row">
                  <div class="col-md-6">
                  <h3>Match History</h3>
                  <div class="table-wrapper-scroll-y my-custom-scrollbar">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>TeamA</th>
                        <th class="text-center">PunkteA</th>
                        <th>VS.</th>
                        <th class="text-center">PunkteB</th>
                        <th>TeamB</th>
                        <th>Datum</th>
                      </tr>
                    </thead>
                    <tbody>';
                    if(isset($_SESSION['matchHistory'])){
                      $matchHistory = $_SESSION['matchHistory'];
                      foreach($matchHistory as $match){
                      echo '<tr> <td>'.$match["teamA"].'</td>
                      <td style="text-align:right">'.$match["punkteA"].'</td>
                      <td style="text-align:center">:</td>
                      <td style="text-align:left">'.$match["punkteB"].'</td>
                      <td>'.$match["teamB"].'</td>
                      <td>'.$match["date"].'</td> </tr>';
                    }
                    }
                   echo '</tbody>
                  </table><form class="form" method="post" action="userSystem/leaderboardSys.php">
                </form>
                </div>
                </div>
                  <div class="col-md-6">
                  <h3>Teams</h3>
                  <form class="form" method="post" action="userSystem/leaderboardSys.php">
                  <table class="table">
                  <thead>
                      <tr>
                        <th><h3>Team A</h3> <div class="form-group has-default">
                        <input type="text" name="statsTeamA" class="form-control" placeholder="Ergebnis">
                      </div></th>
                        <th><h3>Team B</h3> <div class="form-group has-default">
                        <input type="text" name="statsTeamB" class="form-control" placeholder="Ergebnis">
                      </div></th>
                      </tr>
                    </thead>';
                   
                    if(isset($_SESSION['teams'])){echo '                
                    <tbody>';
                    //debug_to_console($_SESSION['teams']);
                      $teams = $_SESSION['teams'];
                      $counter = true;
                      foreach($teams as $team){
                        if($counter){
                          for($gamer = 0; $gamer < sizeof($team);$gamer++){
                            echo '<tr><td>'.$teams[0][$gamer].'</td><td>'.$teams[1][$gamer].'</td></tr>';
                        }
                        $counter = false;
                        }
                    }
                   echo '</tbody>
                  </table>
                        <button type="submit" name="submitStats" class="btn btn-primary btn-round">Ergebnisse abschicken

                  </form>';  }
                  else{
                    echo '<tbody>
                    <tr><td></td><td></td></tr>
                    </table>
                        <button type="submit" name="submitStats" class="btn btn-primary btn-round" disabled>Ergebnisse abschicken
                    </form>';
                  }
                  echo '
                  </div>
            </div>
          </div>
        </div>';
  } else {
    echo '<div class="main main-raised">
            <div class="section section-basic" id="stellenangebote">
              <div class="container">
                <div class="title">
                  <h2>Login</h2>
                </div>
                <div class="row">
                  <div class="col p" style="text-align: left">
                    Leaderboard
                  </div>
                </div>
                <form class="form" method="post" action="userSystem/loginSys.php">
                    <div class="card card-login card-hidden">
                      <div class="card-body ">
                        <p class="card-description text-center">Melde dich an</p>
                        <span class="bmd-form-group">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="material-icons">face</i>
                              </span>
                            </div>
                            <input name="user" type="text" class="form-control" placeholder="Nutzer">
                          </div>
                        </span>
                        <span class="bmd-form-group">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">
                                <i class="material-icons">lock_outline</i>
                              </span>
                            </div>
                            <input name="password" type="password" class="form-control" placeholder="Passwort">
                          </div>
                        </span>
                      </div>
                      <div class="card-footer justify-content-center">
                        <button type="submit" name="loginsubmit" class="btn btn-info btn-link btn-lg">Login
                      </div>
                    </div>
                  </form>
          </div>
        </div>';
  }
  function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
  ?>



</body>
<footer class="footer" data-background-color="black">
  <div class="container">
    <nav class="float-left">
      <ul>
        <li>
          <a href="impressum.html">
            Impressum
          </a>
        </li>
      </ul>
    </nav>
    <div class="copyright float-right">
      &copy;
      <script>
        document.write(new Date().getFullYear())
      </script>
    </div>
  </div>
</footer> 

<div class="modal fade" id="addGamer" tabindex="-1" role="dialog" data-backdrop="false">
  <div class="modal-dialog modal-notice" role="document">
    <div class="modal-content main-product">
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 col-sm-6">
            <form class="form" method="post" action="userSystem/leaderboardSys.php">
              <h2 class="title">Gamer hinzufügen</h2>
              <h3 class="main-price">Name des Gamers:</h3>
              <input name="newGamer" type="text" class="form-control" placeholder="Name">
              <div class="card-footer justify-content-center">
              <button type="submit" name="addGamer" class="btn btn-primary btn-round">Gamer hinzufügen</button>
              <button type="button" class="btn btn-primary btn-round" data-dismiss="modal">Zurück</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>




<!--   Core JS Files   -->
<script src="./assets/js/core/jquery.min.js" type="text/javascript"></script>
<script src="./assets/js/core/popper.min.js" type="text/javascript"></script>
<script src="./assets/js/core/bootstrap-material-design.min.js" type="text/javascript"></script>
<script src="./assets/js/plugins/moment.min.js"></script>
<!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
<script src="./assets/js/plugins/bootstrap-datetimepicker.js" type="text/javascript"></script>
<!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
<script src="./assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
<!--  Google Maps Plugin    -->
<!--<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>-->
<!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
<script src="./assets/js/plugins/bootstrap-tagsinput.js"></script>
<!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
<script src="./assets/js/plugins/bootstrap-selectpicker.js" type="text/javascript"></script>
<!--	Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
<script src="./assets/js/plugins/jasny-bootstrap.min.js" type="text/javascript"></script>
<!--	Plugin for Small Gallery in Product Page -->
<script src="./assets/js/plugins/jquery.flexisel.js" type="text/javascript"></script>
<!-- Plugins for presentation and navigation  -->
<script src="./assets/demo/modernizr.js" type="text/javascript"></script>
<script src="./assets/demo/vertical-nav.js" type="text/javascript"></script>
<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Kit: parallax effects, scripts for the example pages etc -->
<script src="./assets/js/material-kit.js?v=2.1.1" type="text/javascript"></script>

</html>