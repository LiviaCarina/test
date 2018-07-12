<?php

session_start();

include_once 'include/class.user.php';
$userr = new User();

$uid = $_SESSION['uid'];

if (!$userr->get_session()) {
    header("location:login.php");
}

if (isset($_GET['q'])) {
    $userr->user_logout();
    header("location:login.php");
}
$db = mysqli_connect('localhost', 'root', '', 'angajati');

// initialize variables
$nume = "";
$functie = "";
$departament = "";
$data = "";
$user = "";
$parola = "";
$cnp = "";
$telefon = "";
$email = "";
$adresa = "";
$error_cnp="";
$id = 0;
$update = false;


if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nume = test_input($_POST['nume']);
    $functie = test_input($_POST['functie']);
    $departament = test_input($_POST['departament']);
    $data = test_input($_POST['data_angajarii']);
    $user = test_input($_POST['username']);
    $parola = test_input($_POST['parola']);
    $cnp = test_input($_POST['cnp']);
    $telefon = test_input($_POST['telefon']);
    $email = test_input($_POST['email']);
    $adresa = test_input($_POST['adresa']);

    mysqli_query($db, "UPDATE `angajat` SET nume='$nume', functie='$functie', departament='$departament', data_angajarii='$data', username='$user', parola='$parola', cnp='$cnp', telefon='$telefon', email='$email', adresa='$adresa' WHERE id=$id");
    $_SESSION['message'] = "Inregistrarea a fost updatata!";
    header('location: lista_angajati.php');
}

if (isset($_POST['save'])) {

    $nume = test_input($_POST['nume']);
    $functie = test_input($_POST['functie']);
    $departament = test_input($_POST['departament']);
    $data = test_input($_POST['data_angajarii']);
    $user = test_input($_POST['username']);
    $parola = test_input($_POST['parola']);
    $cnp = test_input($_POST['cnp']);
    $telefon = test_input($_POST['telefon']);
    $email = test_input($_POST['email']);
    $adresa = test_input($_POST['adresa']);

    mysqli_query($db, "INSERT INTO `angajat` SET nume='$nume', functie='$functie', departament='$departament', data_angajarii='$data', username='$user', parola='$parola', cnp='$cnp', telefon='$telefon', email='$email', adresa='$adresa'");
//    if ($cnp == '' || !validate_cnp($cnp)) {
//
//        $error_cnp= 'CNP-ul introdus: ' . $cnp . ' nu este valid. Vă rugăm verificați datele din fișierul de import';
//    } else {
        $_SESSION['message'] = "Inregistrare salvata!";
        header('location: lista_angajati.php');
//    }
}

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    mysqli_query($db, "DELETE FROM `angajat` WHERE id=$id");
    $_SESSION['message'] = "Inregistrare stearsa!";
    header('location: lista_angajati.php');
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Functie de validare a unui CNP
 * @param int $cnp_primit
 *  CNP
 * @return boolean
 *  returneaza true daca cnp-ul este valid si false daca nu
 */
function validate_cnp($cnp_primit) {

    $cnp['cnp primit'] = $cnp_primit;

    if (strlen($cnp['cnp primit']) != 13) {
        $cifre = strlen($cnp['cnp primit']);
        return 'CNP-ul trebuie sa aiba 13 numere, cel introdus are doar ' . $cifre . ' !';
    }
    $today = new DateObject(time());
    $datetime = strtotime(str_replace('/', '-', insoft_user_get_birthdate($cnp['cnp primit'])));
    $date = new DateObject($datetime);

    if ($today->difference($date, 'years', true) < 16) {
        return t('Vârsta trebuie să fie mai mare de 16 ani!');
    }

    // prima cifra din cnp reprezinta sexul si nu poate fi decat 1,2,5,6 (pentru cetatenii romani) 
    // 1, 2 pentru cei nascuti intre anii 1900 si 1999
    // 5, 6 pentru cei nsacuti dupa anul 2000
    $cnp['sex'] = isset($cnp['cnp primit']{0}) ? $cnp['cnp primit']{0} : '';
    if ($cnp['sex'] == '') {//die('bbbb');
        return t('CNP-ul n-are corespondenta pentru sex!');
    }

    // cifrele 2 si 3 reprezinta anul nasterii
    $cnp['an'] = isset($cnp['cnp primit']{1}) && isset($cnp['cnp primit']{2}) ? $cnp['cnp primit']{1} . $cnp['cnp primit']{2} : '';
    if ($cnp['an'] == '') {
        return t('CNP-ul n-are corespondenta pentru an!');
    }

    // cifrele 4 si 5 reprezinta luna (nu poate fi decat intre 1 si 12) 
    $cnp['luna'] = isset($cnp['cnp primit']{3}) && isset($cnp['cnp primit']{4}) ? $cnp['cnp primit']{3} . $cnp['cnp primit']{4} : '';
    if ($cnp['luna'] == '') {
        return t('CNP-ul n-are corespondenta pentru luna!');
    }

    // cifrele 6 si 7 reprezinta ziua (nu poate fi decat intre 1 si 31)
    $cnp['zi'] = isset($cnp['cnp primit']{5}) && isset($cnp['cnp primit']{6}) ? $cnp['cnp primit']{5} . $cnp['cnp primit']{6} : '';
    if ($cnp['zi'] == '') {
        return t('CNP-ul n-are corespondenta pentru zi!');
    }

    // cifrele 8 si 9 reprezinta codul judetului (nu poate fi decat intre 1 si 52)
    $cnp['judet'] = isset($cnp['cnp primit']{7}) && isset($cnp['cnp primit']{8}) ? $cnp['cnp primit']{7} . $cnp['cnp primit']{8} : '';
    if ($cnp['judet'] == '') {
        return t('CNP-ul n-are corespondenta pentru judet!');
    }
    // cifrele 10,11,12 reprezinta un nr. poate fi intre 001 si 999. 
    // Numerele din acest interval se impart pe judete, 
    // birourilor de evidenta a populatiei, astfel inct un anumit numar din acel 
    // interval sa fie alocat unei singure persoane intr-o anumita zi.
    // cifra 13 reprezinta cifra de control aflata in relatie cu 
    // toate celelate 12 cifre ale CNP-ului.
    // fiecare cifra din CNP este inmultita cu cifra de pe aceeasi pozitie 
    // din numarul 279146358279; rezultatele sunt insumate, 
    // iar rezultatul final este impartit cu rest la 11. Daca restul este 10, 
    // atunci cifra de control este 1, altfel cifra de control este egala cu restul.
    $cnp['suma de control'] = $cnp['cnp primit']{0} * 2 + $cnp['cnp primit']{1} * 7 +
            $cnp['cnp primit']{2} * 9 + $cnp['cnp primit']{3} * 1 + $cnp['cnp primit']{4} * 4 +
            $cnp['cnp primit']{5} * 6 + $cnp['cnp primit']{6} * 3 + $cnp['cnp primit']{7} * 5 +
            $cnp['cnp primit']{8} * 8 + $cnp['cnp primit']{9} * 2 + $cnp['cnp primit']{10} * 7 +
            $cnp['cnp primit']{11} * 9;
    $cnp['rest'] = fmod($cnp['suma de control'], 11);

    if (empty($cnp['cnp primit'])) {
        return t('Campul CNP este gol!');
    } else {
        if (!is_numeric($cnp['cnp primit'])) {
            return t('CNP-ul este format doar din cifre!<br>');
        }
        if (strlen($cnp['cnp primit']) != 13) {
            $cifre = strlen($cnp['cnp primit']);
            return t('CNP-ul trebuie sa aiba 13 numere, cel introdus are doar ') . $cifre;
        }
        if ($cnp['sex'] != 1 && $cnp['sex'] != 2 && $cnp['sex'] != 5 && $cnp['sex'] != 6) {
            return t('Prima cifra din CNP - eronata!');
        }
        if ($cnp['luna'] > 12 || $cnp['luna'] == 0) {
            return t('Luna este incorecta!');
        }
        if ($cnp['zi'] > 31 || $cnp['zi'] == 0) {
            return t('Ziua este incorecta!');
        }
        if (is_numeric($cnp['luna']) && is_numeric($cnp['zi']) && is_numeric($cnp['an'])) {
            if (!checkdate($cnp['luna'], $cnp['zi'], $cnp['an'])) {
                return t('Data de nastere specificata este incorecta');
            }
        }

        if ($cnp['judet'] > 52 || $cnp['judet'] == 0) {
            return t('Codul judetului este eronat!');
        }
        if (($cnp['rest'] < 10 && $cnp['rest'] != $cnp['cnp primit']{12}) || ($cnp['rest'] >= 10 && $cnp['cnp primit']{12} != 1)) {
            return t('Cifra de control este gresita! (CNP-ul nu este valid)');
        }
    }

    return '';
}

?>