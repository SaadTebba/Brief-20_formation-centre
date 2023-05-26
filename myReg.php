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

    <header>

        <nav class="navbar navbar-expand-lg navbar-light bg-light mx-2">
            <div class="container-fluid">
                <a class="navbar-brand" href="member.php"><img src="logo.png" alt="logo" class="d-inline-block align-text-top logo"> SoliLearn</a>
                <form>
                    <div class="d-flex justify-content-end">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <button class="btn btn-outline-danger" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo $_SESSION['FirstName']; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="myReg.php">My registrations</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="index.php">Log out</a></li>
                </ul>
            </div>
        </nav>

    </header>

    <main class="mx-5 my-4">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th scope="col">Session ID</th>
                    <th scope="col">Results</th>
                    <th scope="col">Results date</th>
                </tr>
            </thead>

            <tbody>

                <?php

                $id = $_SESSION['ID'];

                $statement = $conn->prepare("SELECT * FROM `registrations` WHERE `ID_apprenant` = :id");
                $statement->bindValue(':id', $id);
                $statement->execute();
                $regs = $statement->fetchAll();

                foreach ($regs as $reg) {

                ?>

                    <tr>
                        <td><?php echo $reg['ID_session']; ?></td>
                        <td><?php echo $reg['resultat']; ?></td>
                        <td><?php echo $reg['date_evaluation']; ?></td>
                    </tr>

                <?php

                };

                // if (isset($_POST['delete'])) {

                // };

                ?>

            </tbody>

        </table>

    </main>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/165265fe22.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="script.js"></script>
</body>

</html>