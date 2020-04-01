<?php
session_start();
include 'leaderboardSys.php';

if (isset($_POST['loginsubmit']) && isset($_POST['user']) && isset($_POST['password'])) {

    $user = $_POST['user'];
    $password = $_POST['password'];

    if(empty($user) || empty($password) ) {        
        header("Location: ../index.php?error=emptyfields");
        exit();
    } else{

        if(checkLoginData($user, $password)) {

            $_SESSION['userId'] = $user;
            $_SESSION['allUsers'] = getSortedallUsers();
            getMatchHistory();
            header("Location: ../index.php?login=succes");
        } else{

            header("Location: ../index.php?error=wrongUserOrPass");
            exit();

        }
    }

} if (isset($_POST['logout-submit'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php?logout=success");
}