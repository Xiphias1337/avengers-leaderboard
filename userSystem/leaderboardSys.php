<?php
session_start();
include 'checkLogin.php';

require_once dirname(__FILE__) . '/Classes/PHPExcel.php';



if (isset($_POST['refreshLeaderboard'])) {
    refreshLeaderboard();
    header("Refresh:0; url=../index.php");

}

if (isset($_POST['refreshMatchhistory'])) {
    getMatchHistory();
    header("Refresh:0; url=../index.php");

}

function refreshLeaderboard() {
    $_SESSION['allUsers'] = getSortedallUsers();
}

if (isset($_POST['submitStats'])) {
    saveMatchToHistory($_SESSION['teams'][0],$_SESSION['teams'][1],$_POST['statsTeamA'], $_POST['statsTeamB']);
    if($_POST['statsTeamA'] > $_POST['statsTeamB']) {
        ranking($_SESSION['teams'][0],true);
        header("Refresh:0; url=../index.php");
    }else if($_POST['statsTeamA'] < $_POST['statsTeamB']){
        ranking($_SESSION['teams'][1],true);
        header("Refresh:0; url=../index.php");
    }else {
        ranking($_SESSION['teams'][0],false);
        ranking($_SESSION['teams'][1],false);
        header("Refresh:0; url=../index.php");
    }
    refreshLeaderboard();
}

function saveMatchToHistory($teamA, $teamB, $pointsA, $pointsB) {
    $loginDataFile = 'matchhistory.xlsx';
    $excelReader = PHPExcel_IOFactory::createReaderForFile($loginDataFile);
    $excelObj = $excelReader->load($loginDataFile);
    $worksheet = $excelObj->getSheet(0);
    $lastRow = $worksheet->getHighestRow();
    $usersA = "";
    $usersB = "";
    $lastElementA = end($teamA);
    $lastElementB = end($teamB);

    foreach($teamA as $userA) {
        if(!$lastElementA) {
            $usersA .= $userA.",";
        }else{
            $usersA .= $userA;
        }
    }
    foreach($teamB as $userB) {
        if(!$lastElementB) {
            $usersB .= $userB.",";

        }else{
            $usersB .= $userB;
        }
    }
    $worksheet->setCellValueByColumnAndRow(0,$lastRow+1,$usersA);
    $worksheet->setCellValueByColumnAndRow(1,$lastRow+1,$pointsA);
    $worksheet->setCellValueByColumnAndRow(2,$lastRow+1,$pointsB);
    $worksheet->setCellValueByColumnAndRow(3,$lastRow+1,$usersB);
    $worksheet->setCellValueByColumnAndRow(4,$lastRow+1,time());

    $objWriter = PHPExcel_IOFactory::createWriter($excelObj, 'Excel2007');
    $objWriter->save('matchhistory.xlsx');
}

function getMatchHistory() {
    $loginDataFile = 'matchhistory.xlsx';
    $excelReader = PHPExcel_IOFactory::createReaderForFile($loginDataFile);
    $excelObj = $excelReader->load($loginDataFile);
    $worksheet = $excelObj->getSheet(0);
    $lastRow = $worksheet->getHighestRow();
    $matchHistory = array();
    for ($row = 2; $row <= $lastRow; $row++) {
        $match = array("teamA"=>$worksheet->getCell('A'.$row)->getValue(), "punkteA"=>$worksheet->getCell('B'.$row)->getValue(), "punkteB"=>$worksheet->getCell('C'.$row)->getValue(), 
        "teamB"=>$worksheet->getCell('D'.$row)->getValue(), "date"=>date('d.m.y H:i:s', $worksheet->getCell('E'.$row)->getValue()));  
        $matchHistory[] = $match;
    }
    $_SESSION['matchHistory'] = $matchHistory;
    return $matchHistory;
}

if (isset($_POST['addGamer'])) {
    $loginDataFile = 'leaderboard.xlsx';
    $excelReader = PHPExcel_IOFactory::createReaderForFile($loginDataFile);
    $excelObj = $excelReader->load($loginDataFile);
    $worksheet = $excelObj->getSheet(0);
    $lastRow = $worksheet->getHighestRow();
    if(isset($_POST['newGamer'])) {
        for ($row = 2; $row <= $lastRow; $row++) {
            if($_POST['newGamer'] != $worksheet->getCell('A'.$row)->getValue()) {
                $worksheet->setCellValueByColumnAndRow(0,$lastRow+1,$_POST['newGamer']);
                $worksheet->setCellValueByColumnAndRow(1,$lastRow+1,0);
                $worksheet->setCellValueByColumnAndRow(2,$lastRow+1,0);
                $objWriter = PHPExcel_IOFactory::createWriter($excelObj, 'Excel2007');
                $objWriter->save('leaderboard.xlsx');
                header("Location: ../index.php?addGamer=success");
            } 
        }
    }else {
        header("Location: ../index.php?addGamer=failed");
    }
    refreshLeaderboard();
}

function ranking($team, $winner){
    $loginDataFile = 'leaderboard.xlsx';
    $excelReader = PHPExcel_IOFactory::createReaderForFile($loginDataFile);
    $excelObj = $excelReader->load($loginDataFile);
    $worksheet = $excelObj->getSheet(0);
    $lastRow = $worksheet->getHighestRow();
    $winElo = 3;
    if(!$winner) {
        $winElo = 1;
    }
    foreach($team as &$gamer) {
        for ($row = 2; $row <= $lastRow; $row++) {
            if($gamer == $worksheet->getCell('A'.$row)->getValue()) {
                 $worksheet->setCellValueByColumnAndRow(1,$row,$worksheet->getCell('B'.$row)->getValue()+$winElo);
                 //  echo "<pre>"; var_dump($worksheet->getCell('B'.$row)->getValue()); echo "</pre>";
            }
        }
    }
    $objWriter = PHPExcel_IOFactory::createWriter($excelObj, 'Excel2007');
    $objWriter->save('leaderboard.xlsx');
}



if (isset($_POST['generateTeams']) && isset($_POST['selectedUser']) && !empty($_POST['selectedUser'])) {
    $allSelectedUsers = array();
    $teams = array();
    foreach ($_POST['selectedUser'] as $selecUser) {
        $allSelectedUsers[] = $selecUser;
    }
    if(shuffle($allSelectedUsers)) {
        $teams = array_chunk($allSelectedUsers,sizeof($allSelectedUsers)/2);
        $_SESSION['teams'] = $teams;
        header("Location: ../index.php?teamsGenerated=success");
    }else{
        header("Location: ../index.php?teamsGenerated=failed");
    }
}


function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

//        echo "<pre>"; var_dump($teams); echo "</pre>";
