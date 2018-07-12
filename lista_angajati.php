<?php
include('php_code.php');

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $record = mysqli_query($db, "SELECT * FROM `angajat` WHERE id=$id");

    if (count($record) == 1) {
        $n = mysqli_fetch_array($record);
        $nume = $n['nume'];
        $functie = $n['functie'];
        $departament = $n['departament'];
        $data = $n['data_angajarii'];
        $user = $n['username'];
        $parola = $n['parola'];
        $cnp = $n['cnp'];
        $telefon = $n['telefon'];
        $email = $n['email'];
        $adresa = $n['adresa'];
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script  type="text/javascript" src="css/script.js"></script>

        <title>Test angajati</title>

    </head>
    <body>
        <div class='col-lg-12 pull-right'>
            <a href="lista_angajati.php?q=logout">LOGOUT</a>
            <p>  Buna <?php $userr->get_fullname($uid); ?>   </p>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="msg">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
            </div>
        <?php endif ?>

        <div class="container">
            <div class='col-lg-12'>
                <?php $results = mysqli_query($db, "SELECT * FROM `angajat`"); ?>

                <table class="table" >
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nume</th>
                            <th>Functie</th>
                            <th>Departament</th>
                            <th>Data angajarii</th>
                            <th>User name</th>
                            <th>CNP</th>
                            <th>Telefon</th>
                            <th>Email</th>
                            <th>Adresa</th>
                            <?php
                            if ($_SESSION['uid'] == 2) {
                                ?>
                                <th>Actiuni</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <?php while ($list = mysqli_fetch_array($results)) { ?>
                        <tbody>
                            <tr>
                                <td><?php print $list['id']; ?></td>
                                <td><?php print $list['nume']; ?></td>
                                <td><?php print $list['functie']; ?></td>
                                <td><?php print $list['departament']; ?></td>
                                <td><?php print $list['data_angajarii']; ?></td>
                                <td><?php print $list['username']; ?></td>
                                <td><?php print $list['cnp']; ?></td>
                                <td><?php print $list['telefon']; ?></td>
                                <td><?php print $list['email']; ?></td>
                                <td><?php print $list['adresa']; ?></td>

                                <!--conditia cu if-->
                                <?php
                                if ($_SESSION['uid'] == 2) {
                                    ?>
                                    <td>   
                                        <a class="btn btn-info" role="button" href="lista_angajati.php?edit=<?php echo $list['id']; ?>"  >Modifica</a>
                                        <a class="btn btn-danger" role="button"  href="php_code.php?del=<?php echo $list['id']; ?>" >Sterge</a>
                                    </td>
                                <?php } ?>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

            </div>

            <div class="box1 col-lg-8">
                <h1>Adaugare angajat</h1>

                <form action="php_code.php" method="post">

                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="nume">Nume:</label>
                        <input type="text" name="nume" value="<?php echo $nume; ?>">
                    </div>
                    <div class="form-group">
                        <label for="functie">Functie:</label>
                        <input type="text" name="functie" value="<?php echo $functie; ?>">
                    </div>
                    <div class="form-group">
                        <label for="dep">Departament:</label>
                        <input type="text" name="departament" value="<?php echo $departament; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="example-date-input" >Data angajarii:</label>
                        <input class="form-control" id="example-date-input" type="data" name="data_angajarii" value="<?php echo $data; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="user">User:</label>
                        <input type="text" name="username" value="<?php echo $user; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="parola">Parola:</label>
                        <input type="password" class="form-control" id="parola" placeholder="Introduceti parola" name="parola" value="<?php echo $parola; ?>"/>   
                    </div>
                    <div class="form-group">
                        <span class="error">* <?php echo $error_cnp; ?></span>
                        <br><br>
                        <label for="cnp">CNP:</label>
                        <input type="text" name="cnp" onkeypress="return validareCNP(s)" value="<?php echo $cnp; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="tel">Telefon:</label>
                        <input class="form-control" type="tel" id="tel" name="telefon" value="<?php echo $telefon; ?>"/>
                    </div>
                    <div class="form-group"> 
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" placeholder="Introduceti email" name="email" value="<?php echo $email; ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="exampleTextarea">Adresa:</label>
                        <input type="text" class="form-control" id="exampleTextarea" value="<?php echo $adresa; ?>"/>

                    </div>

                    <?php if ($update == true): ?>
                        <button type="submit"  class="btn btn-info" name="update" >update</button>
                    <?php else: ?>
                        <button class="btn btn-default" type="submit" name="save" >Save</button>
                    <?php endif ?>   

                </form>
            </div>

        </div>

    </body>
</html>