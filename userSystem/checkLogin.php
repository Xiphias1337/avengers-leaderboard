<?php
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';

function checkLoginData($username, $password) {
    $loginDataFile = 'loginDaten.xlsx';
    $excelReader = PHPExcel_IOFactory::createReaderForFile($loginDataFile);
    $excelObj = $excelReader->load($loginDataFile);
    $worksheet = $excelObj->getSheet(0);
    $lastRow = $worksheet->getHighestRow();


    for ($row = 2; $row <= $lastRow; $row++) {
        if($username == $worksheet->getCell('B'.$row)->getValue()) {
           if($password == $worksheet->getCell('C'.$row)->getValue()) {
               return true;
           }
        }
        
  }
  return false;
}

function getNameOfUser($user) {
    $loginDataFile = 'loginDaten.xlsx';
    $excelReader = PHPExcel_IOFactory::createReaderForFile($loginDataFile);
    $excelObj = $excelReader->load($loginDataFile);
    $worksheet = $excelObj->getSheet(0);
    $lastRow = $worksheet->getHighestRow();

    for ($row = 2; $row <= $lastRow; $row++) {
        if($user == $worksheet->getCell('B'.$row)->getValue()) {
            return $worksheet->getCell('A'.$row)->getValue();
        }
        
  }
  return "Unknown";
}

function getAllUser() {
    $loginDataFile = 'leaderboard.xlsx';
    $excelReader = PHPExcel_IOFactory::createReaderForFile($loginDataFile);
    $excelObj = $excelReader->load($loginDataFile);
    $worksheet = $excelObj->getSheet(0);
    $lastRow = $worksheet->getHighestRow();
    $allUsers = array();

    for ($row = 2; $row <= $lastRow; $row++) {
        $user = array("username"=>$worksheet->getCell('A'.$row)->getValue(), "elo"=>$worksheet->getCell('B'.$row)->getValue(), "placement"=>$worksheet->getCell('C'.$row)->getValue(),
        "wins"=>$worksheet->getCell('D'.$row)->getValue(),"losses"=>$worksheet->getCell('E'.$row)->getValue() );
        $uniqid = uniqid();
        $allUsers[$uniqid] = $user;
    }
  return $allUsers;
}

function getSortedallUsers() {
    $sortedAllUsers = getAllUser();
    $newPlacement = 1;

    uasort($sortedAllUsers, function($a, $b) {
        return $a['elo'] < $b['elo'];
    });

    foreach($sortedAllUsers as &$user){
        $user["placement"] = $newPlacement;
        $newPlacement +=1;
    }
    
    return $sortedAllUsers;
}
