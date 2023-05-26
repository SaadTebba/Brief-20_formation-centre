<?php include "connection.php"; ?>

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

                <h3 class="text-center my-3 pt-2">Join now and start exploring!</h3>

                <form method="POST" class="row g-3 needs-validation px-5 mt-1">

                    <div class="col-md-6">
                        <label for="FirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="FirstName" name="FirstName" required>
                    </div>
                    <div class="col-md-6">
                        <label for="LastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="LastName" name="LastName" required>
                    </div>


                    <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col-md-6">
                        <label for="confirmPassword" class="form-label">Confirm password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                            <label class="form-check-label" for="invalidCheck">By checking the box, I acknowledge that I have read and agree to the <a type="button" class="text-decoration-underline" data-bs-toggle="modal" data-bs-target="#exampleModal">rules</a>.</label>
                        </div>
                    </div>

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Rules</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <ul>
                                        <li>A person cannot borrow or reserve more than three books at the same time.</li>
                                        <li>A borrowing operation must be preceded by a reservation.</li>
                                        <li>A torn item cannot be reserved or borrowed.</li>
                                        <li>A reservation is only made for a book actually available in the library and which is not subject to a current reservation.</li>
                                        <li>The validity of a reservation is limited to 24 hours.</li>
                                        <li>The loan period must not exceed 15 days.</li>
                                        <li>A person who submits a book after 15 days receives a penalty.</li>
                                        <li>A person who accumulates more than 3 penalties does not have the right to continue to borrow the books. And his account will be immediately locked.</li>
                                        <li>No operation will be possible without authentication, even a simple consultation.</li>
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary col-12" type="submit" name="submit">Submit</button>
                        <p class="text-decoration-none text-dark text-center mt-3">Already have an account? <a href="signin.php" class="link-primary text-decoration-underline pe-auto signinhere fw-bolder">Sign in</a>.</p>
                    </div>

                </form>

                <?php

                if (isset($_POST["submit"])) {

                    $FirstName = $_POST["FirstName"];
                    $LastName = $_POST["LastName"];
                    $Email = $_POST["email"];
                    $Password = $_POST["password"];

                    $hashed_password = password_hash($Password, PASSWORD_DEFAULT);

                    $statement = $conn->prepare("INSERT INTO `members` (`FirstName`, `LastName`, `Email`, `Password`) VALUES (?, ?, ?, ?)");
                    $statement->execute([$FirstName, $LastName, $Email, $hashed_password]);
                }

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