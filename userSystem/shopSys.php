<?php
session_start();
include 'checkLogin.php';

//TODO Mengen implementieren; Wenn Produkt x in warenkorb und dann nochmal produkt x hinzugefügt wird, kein neuer artikel sondern Menge des bereits vorhandenen erhöhen

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/OAuth.php';



if(isset($_POST["order-submit"])){
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $cart = $_SESSION['cart'];
    $user = $_SESSION['userId'];
    $userFullName = getNameOfUser($user);
    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.web.de';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'janhuhsmann@web.de';                     // SMTP username
        $mail->Password   = 'sd823+DD1.';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('janhuhsmann@web.de', 'Guenter Bremer Bestellung von Arbeitskleidung');
        $mail->addAddress('janhuhsmann@web.de', 'Guenter Bremer Bestellung von Arbeitskleidung'); // Add a recipient
        //$mail->addAddress('info@bremer-trockenbau.de', 'Guenter Bremer Kontaktformular Webseite'); // Add a recipient

        $body ='Name des Bestellenden: '.$userFullName.'<br />'.
                'Bestellung: '.'<br />'.'<table style="border-collapse: collapse;border: 1px solid black;"><tr><th style="border: 1px solid black;">Artikel-Nummer</th><th style="border: 1px solid black;">Gr&ouml;&szlig;e</th><th style="border: 1px solid black;">Farbe</th><th style="border: 1px solid black;">Menge</th><th>Artikel-Name</th><th style="border: 1px solid black;">St&uuml;ckpreis</th></tr>';
      
        foreach ($cart as $art) {
            if (!empty($art) && $art["articleId"] != null) {
                $body .= "<tr><td style='border: 1px solid black;'>".$art["articleId"]."</td><td style='border: 1px solid black;'>".$art["size"]."</td><td style='border: 1px solid black;'>".$art["color"]."  </td><td style='border: 1px solid black;'>".$art["amount"]."</td><td style='border: 1px solid black;'>".$art["articleName"]."</td><td style='border: 1px solid black;'>".$art["price"]."</td></tr>"; 
            }
        };       
        $body .= "<br />"."Gesamtpreis: ".$cart['totalCost'];


        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "Neue Bestellung von ".$userFullName;
        $mail->Body = $body.'</table>';

        $mail->send();
        //echo 'Message has been sent';
        $success = "Wir haben Ihre Bestellung erhalten und werden sie so schnell wie möglich bearbeiten. <br>";
        header("Location: ../cart.php?order=success");
    } catch (Exception $e) {
        //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        $error = "Beim Versenden Ihrer Bestellung ist ein Fehler aufgetreten! Versuchen Sie es bitte später nocheinmal";
        header("Location: ../cart.php?order=failed");
    }
}

if (isset($_POST['addArticleToCart-submit'])) {
    
    //Get cart
    $cart = $_SESSION['cart'];
    $wasSameArticle = false;
    //New article 
    $articleId = $_POST['articleId'];
    $size = getSizeById($_POST['size']);
    $colorCode = $_POST['color'];
    $colorSplit = explode(':', $colorCode);
    if($colorSplit[1] == 0) {
        $color = "Wei&szlig;";
    }else {
        $color = $colorSplit[0];
    }
    $amount = $_POST['amount'];;
    $articleName = getArticleNameById($articleId);
    $price = getPriceById($articleId);   
    $article = array("articleId"=>$articleId, "size"=>$size, "color"=>$color, "amount"=>$amount, "articleName"=>$articleName, "price"=>$price);

    foreach($cart as &$art) {
        if($article["articleId"] == $art["articleId"] && $article["size"] == $art["size"] && 
            $article["color"] == $art["color"]) {
            $art["amount"] = $art["amount"] + $article["amount"];
            $wasSameArticle = true;
            break;
        }
    }
    unset($art);

    if(!$wasSameArticle) {
        $uniqid = uniqid();
        $cart[$uniqid] = $article;
    }
    $cart['totalCost'] = calcTotalCost($cart);

    $_SESSION['cart'] = $cart;

    header("Location: ../809j3408j2f98ß3j2f.php#shopAnchor");
    exit();
}

if (isset($_POST['deleteItem-submit'])) {
    //debug_to_console("YEEEEEET");
}


if (isset($_POST['order-submit'])) {
    $_SESSION['cart'] = $cart;
    if(!empty($cart)) {

        

    }
}

function isCartEmpty(){
    return $_SESSION['cart']['totalCost'] == 0;
}


function initNewCart(){
    $cartEmpty['totalCost'] = 0;
    $_SESSION['cart'] =  $cartEmpty;
}

function calcTotalCost($cart) {
    $totalPrice = 0;
    foreach ($cart as $art) {
        $totalPrice = $totalPrice + ($art["price"] * $art["amount"]);
    }
    unset($art);
    return  $totalPrice;
}

function getPriceById($id) {

    switch ($id) {
        case 3371206:
            return '49.90';
            break;
        case 3370978:
            return '24.90';
            break;
        case 3134603:
            return '11111.11111';
            break;
        case 3133013:
            return '19.90';
            break;
        case 3131688:
            return '49.90';
            break;
        case 3131193:
            return '49.90';
            break;  
    }
}

function getSizeById($id) {               

    switch ($id) {
        case 0:
            return 'XS';
            break;
        case 1:
            return 'S';
            break;
        case 2:
            return 'M';
            break;
        case 3:
            return 'L';
            break;
        case 4:
            return 'XL';
            break;  
        case 5:
            return 'XXL';
            break;
        case 6:
            return '3XL';
            break;
        case 7:
            return '4XL';
            break;
        case 8:
            return '5XL';
            break;
        case 11:
            return '37/38';
            break;
        case 22:
            return '39/40';
            break;
        case 33:
            return '41/42';
            break;
        case 44:
            return '43/44';
            break;
        case 55:
            return '45/46';
            break;
        case 66:
            return '47/48';
            break;
        case 911:
            return '32';
            break;
        case 922:
            return '34';
            break;
        case 933:
            return '36';
            break;
        case 944:
            return '38';
            break;
        case 955:
            return '40';
            break;
        case 966:
            return '42';
            break;
        case 977:
            return '44';
            break;
        case 988:
            return '46';
            break;
        case 999:
            return '48';
            break;
        case 9111:
            return '50';
            break;
    }
}


function getArticleNameById($id) {

    switch ($id) {
        case 3371206:
            return 'KORSAR Softshelljacke Dynamic 2';
            break;
        case 3370978:
            return 'KORSAR Crossover Fleeceshirt';
            break;
        case 3134603:
            return 'B&C T-Shirt';
            break;
        case 3133013:
            return 'B&C Pique-Polo';
            break;
        case 3131688:
            return 'OLYMP Businesshemd Luxor modern fit';
            break;  
        case 3131193:
            return 'OLYMP Business-Damenbluse Tendenz';
            break;  
    }
}







function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
