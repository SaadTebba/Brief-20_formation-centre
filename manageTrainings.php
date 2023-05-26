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
                <a class="navbar-brand" href="#"><img src="logo.png" alt="logo" class="d-inline-block align-text-top logo"> SoliLearn</a>
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
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Manage trainers</a></li>
                    <li><a class="dropdown-item" href="manageTrainings.php">Manage trainings & sessions</a></li>
                    <li><a class="dropdown-item" href="#">Assign sessions</a></li>
                    <li><a class="dropdown-item" href="#">Trainings summary</a></li>
                    <li><a class="dropdown-item" href="#">Statistics</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="index.php">Log out</a></li>
                </ul>
            </div>
        </nav>

    </header>

    <main class="mx-5 my-4">

        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Add new</button>

        <form method="POST" class="row g-3 needs-validation px-3 mt-1">

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add a new course</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="col-md-12">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="col-md-12">
                                <label for="category" class="form-label">Category</label>
                                <input type="text" class="form-control" id="category" name="category" required>
                            </div>

                            <div class="col-md-12">
                                <label for="duration" class="form-label">Duration</label>
                                <input type="number" class="form-control" id="duration" name="duration" required>
                            </div>
                            <div class="col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <input type="textarea" class="form-control" id="description" name="description" required>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="submit">Add course</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>

        <?php

        if (isset($_POST['submit'])) {

            $subject = $_POST["subject"];
            $category = $_POST["category"];
            $duration = $_POST["duration"];
            $description = $_POST["description"];

            $statement = $conn->prepare("INSERT INTO `courses` (`subject`, `category`, duration, `description`) VALUES ('$subject', '$category', '$duration', '$description')");
            $statement->execute();
        }

        ?>

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Category</th>
                    <th scope="col">Duration (minutes)</th>
                    <th scope="col">Description</th>
                    <th scope="col">More</th>
                </tr>
            </thead>

            <tbody>

                <?php

                $statement = $conn->prepare("SELECT * FROM `courses`");
                $statement->execute();
                $courses = $statement->fetchAll();

                foreach ($courses as $course) {

                ?>

                    <tr>
                        <th scope="row"><?php echo $course['ID_formation']; ?></th>
                        <td><?php echo $course['subject']; ?></td>
                        <td><?php echo $course['category']; ?></td>
                        <td><?php echo $course['duration']; ?></td>
                        <td><?php echo $course['description']; ?></td>
                        <td>
                            <form method="POST">
                                <a href="sessionsCRUD.php?courseID=<?php echo $course['ID_formation']; ?>" class="btn btn-primary">Sessions</a>
                                <button class="btn btn-danger" name="delete">Delete</button>
                            </form>
                        </td>
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