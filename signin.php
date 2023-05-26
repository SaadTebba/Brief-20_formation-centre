<?php

include "connection.php";
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoliLearn</title>
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css?<?= time(); ?>">
</head>

<body>

    <main>

        <div id="backgroundImg" class="d-flex align-items-center">

            <div class="card mx-auto col-md-5">

                <h3 class="text-center my-3 pt-2">Welcome back!</h3>

                <form method="POST" class="row g-3 needs-validation px-5 mt-1">

                    <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="col-md-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary col-12" type="submit" name="submit">Submit</button>
                        <p class="text-decoration-none text-dark text-center mt-3">Don't have an account yet? <a href="signup.php" class="link-primary text-decoration-underline pe-auto signinhere fw-bolder">Sign up</a>.</p>
                    </div>

                </form>

                <?php


                if (isset($_POST["submit"])) {

                    $email = $_POST["email"];
                    $password = $_POST["password"];

                    // $myquery = "SELECT * FROM (
                    //     SELECT * FROM `members` WHERE `$email` = `Email` AND `$password` = `Password`
                    //     UNION 
                    //     SELECT * FROM `admin` WHERE `$email` = `Email` AND `$password` = `Password`
                    //     UNION 
                    //     SELECT * FROM `trainer` WHERE `$email` = `Email` AND `$password` = `Password`
                    // ) AS combined_table";

                    // "SELECT * FROM `members` WHERE '$email' LIKE `Email` AND '$password' LIKE `Password`"

                    $queryMember = "SELECT * FROM `members` WHERE '$email' LIKE `Email` AND '$password' LIKE `Password`";
                    $queryTrainer = "SELECT * FROM `trainer` WHERE '$email' LIKE `Email` AND '$password' LIKE `Password`";
                    $queryAdmin = "SELECT * FROM `admin` WHERE '$email' LIKE `Email` AND '$password' LIKE `Password`";

                    $statementMember = $conn->prepare($queryMember);
                    $statementTrainer = $conn->prepare($queryTrainer);
                    $statementAdmin = $conn->prepare($queryAdmin);

                    $statementMember->execute();
                    $statementTrainer->execute();
                    $statementAdmin->execute();

                    $rowCountMember = $statementMember->rowCount();
                    $rowCountTrainer = $statementTrainer->rowCount();
                    $rowCountAdmin = $statementAdmin->rowCount();

                    if ($rowCountMember > 0) {

                        $result = $statementMember->fetch();

                        $_SESSION['ID'] = $result['ID_apprenant'];
                        $_SESSION['FirstName'] = $result['FirstName'];
                        $_SESSION['LastName'] = $result['LastName'];
                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $password;

                        header("Location: member.php");
                        die;

                    } elseif ($rowCountTrainer > 0) {
                        
                        $result = $statementTrainer->fetch();

                        $_SESSION['FirstName'] = $result['FirstName'];
                        $_SESSION['LastName'] = $result['LastName'];
                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $password;

                        header("Location: trainer.php");
                        die;

                    } elseif ($rowCountAdmin > 0) {
                        
                        $result = $statementAdmin->fetch();

                        $_SESSION['FirstName'] = $result['FirstName'];
                        $_SESSION['LastName'] = $result['LastName'];
                        $_SESSION['email'] = $email;
                        $_SESSION['password'] = $password;

                        header("Location: admin.php");
                        die;
                    } else {
                        echo '<p class="text-center link-danger fw-bolder h4 text-decoration-underline">Access denied due to incorrect login details.</p>';
                    }

                    ob_end_flush();
                    exit();
                };

                ?>

            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/165265fe22.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="script.js"></script>
</body>

</html>