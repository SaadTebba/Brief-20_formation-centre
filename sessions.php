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

        <div class="card-container">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th scope="col">Starting date</th>
                        <th scope="col">Ending date</th>
                        <th scope="col">Number of places available</th>
                        <th scope="col">State</th>
                        <th scope="col">Formateur</th>
                        <th scope="col">More</th>
                    </tr>
                </thead>

                <tbody>

                    <?php

                    $statement = $conn->prepare("SELECT * FROM `session` WHERE `Id_formation` = :id");

                    $id = $_GET['courseID'];

                    $statement->bindValue(':id', $id, PDO::PARAM_STR);
                    $statement->execute();
                    $sessions = $statement->fetchAll();

                    foreach ($sessions as $session) {

                    ?>

                        <tr>
                            <td><?php echo $session['starts']; ?></td>
                            <td><?php echo $session['ends']; ?></td>
                            <td><?php echo $session['places']; ?></td>
                            <td><?php echo $session['state']; ?></td>
                            <td><?php echo $session['Id_formateur']; ?></td>
                            <td>
                                <form method="POST">
                                    <button class="btn btn-primary" type="submit" name="submit">Apply</button>
                                    <input type="hidden" name="sessionId" value="<?php echo $session['ID_session']; ?>">
                                    <input type="hidden" name="places" value="<?php echo $session['places']; ?>">
                                    <input type="hidden" name="state" value="<?php echo $session['state']; ?>">
                                </form>
                            </td>
                        </tr>

                    <?php

                    };

                    if (isset($_POST['submit'])) {

                        $member_id = $_SESSION['ID'];
                        $session_id = $_POST['sessionId'];
                        $course_id = $_GET['courseID'];
                        $date_evaluation = date('Y-m-d');
                        $session_state = $_POST['state'];
                        $session_places = $_POST['places'];
                    
                        if ($session_state === 'active') {
                    
                            $registrationCountStatement = $conn->prepare("SELECT COUNT(*) AS registration_count FROM `registrations` WHERE ID_apprenant = :member_id");
                            $registrationCountStatement->bindParam(':member_id', $member_id);
                            $registrationCountStatement->execute();
                            $registrationCount = $registrationCountStatement->fetchColumn();
                    
                            if ($registrationCount >= 2) {
                                echo "<h2>Error: You are already registered in two sessions.</h2>";
                            } else {
                                if ($session_places > 0) {
                        
                                    // Check if the member has a previous session
                                    $previousSessionStatement = $conn->prepare("SELECT * FROM `registrations` AS r JOIN `session` AS s ON r.ID_session = s.ID_session WHERE r.ID_apprenant = :member_id");
                                    $previousSessionStatement->bindParam(':member_id', $member_id);
                                    $previousSessionStatement->execute();
                                    $previousSession = $previousSessionStatement->fetch();
                        
                                    $canApply = true; // Flag to track if the member can apply for the current session
                        
                                    if ($previousSession) {
                                        // Compare the dates to check for an overlap
                                        $previousSessionStartDate = $previousSession['starts'];
                                        $previousSessionEndDate = $previousSession['ends'];
                                        $currentSessionStartDate = $session['starts'];
                                        $currentSessionEndDate = $session['ends'];
                        
                                        if ($currentSessionStartDate <= $previousSessionEndDate && $previousSessionStartDate <= $currentSessionEndDate) {
                                            echo "<h2>Error: The dates of the current session overlap with the dates of your previous session.</h2>";
                                            $canApply = false; // Set the flag to false
                                        }
                                    }
                        
                                    if ($canApply) {
                                        $existingRegistrationStatement = $conn->prepare("SELECT * FROM `registrations` WHERE ID_apprenant = :member_id AND ID_session = :session_id");
                                        $existingRegistrationStatement->bindParam(':member_id', $member_id);
                                        $existingRegistrationStatement->bindParam(':session_id', $session_id);
                                        $existingRegistrationStatement->execute();
                        
                                        if ($existingRegistrationStatement->rowCount() > 0) {
                                            echo "<h2>Error: Registration already exists for the given member in this session.</h2>";
                                        } else {
                                            $insertStatement = $conn->prepare("INSERT INTO `registrations` (ID_apprenant, ID_session, resultat, date_evaluation) VALUES (:member_id, :session_id, NULL, :date_evaluation)");
                                            $insertStatement->bindParam(':member_id', $member_id);
                                            $insertStatement->bindParam(':session_id', $session_id);
                                            $insertStatement->bindParam(':date_evaluation', $date_evaluation);
                        
                                            if ($insertStatement->execute()) {
                                                echo "<h2>Registration added successfully.</h2>";
                                            }
                                        }
                                    }
                                } else {
                                    echo "<h2>This session has no more places left.</h2>";
                                }
                            }
                        } else {
                            echo "<h2>Error: This session state is not active right now, try again later.</h2>";
                        }
                        
                    }

                    ?>

                </tbody>

            </table>

        </div>

    </main>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/165265fe22.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="script.js"></script>
</body>

</html>