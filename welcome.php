<?php

$insert = false;
$update = false;
$deleted = false;
$deleted_all = false;

$servername = "localhost";
$localhost = "root";
$password = "";
$database = "XP6";

$conn = mysqli_connect($servername, $localhost, $password, $database);
if (!$conn) {
    die("Connection Failed " . mysqli_connect_error());
}
if (isset($_POST['deleted'])) {
    $sql = "DELETE FROM `notes`";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        header("Location:/XP6/welcome.php");
        $deleted_all = true;
        exit();
    }

}

if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $sql = "DELETE FROM `notes` WHERE `id` = $sno";
    $result = mysqli_query($conn, $sql);
    $deleted = true;

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['snoEdit'])) {
        // Update the record
        $sno = $_POST["snoEdit"];
        $title = $_POST["titleEdit"];
        $description = $_POST["descriptionEdit"];
        $status = $_POST["statusEdit"];
        $date = $_POST["date_edit"];


        $sql = "UPDATE `notes` SET `title` = '$title', `description` = '$description', `status` = '$status' , `priority` = CASE WHEN `status` = 'Started' THEN '3' WHEN `status` = 'OnHold' THEN '2' WHEN `status` = 'Completed' THEN '1' ELSE NULL END, `deadline` = '$date' WHERE `notes`.`id` = $sno";

        // UPDATE `notes` SET `title` = 'jghbjk', `description` = 'jhkjbkj', `status` = 'Completed' , `priority` = CASE WHEN `status` = 'Started' THEN '3' WHEN `status` = 'OnHold' THEN '2' WHEN `status` = 'Completed' THEN '1' ELSE NULL END, `deadline` = '2023-09-09 12:09:09' WHERE `notes`.`id` = 12;

        // UPDATE `notes` SET `description` = 'Bring crud for home' WHERE `notes`.`id` = 3;
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $update = true;
        } else {
            echo "We could not update the record successfully " . mysqli_error($conn);
        }


    } else {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $progress = $_POST['progress'];
        $deadline = $_POST['date_and_time'];

        $sql = "INSERT INTO `notes` (`title`, `description`, `status`, `deadline`,`Priority`) VALUES ('$title', '$description', '$progress', '$deadline', CASE
            WHEN `status` = 'Started' THEN '3'
            WHEN `status` = 'OnHold' THEN '2'
            WHEN `status` = 'Completed' THEN '1'
        END)";

        // INSERT INTO `notes` (`id`, `title`, `description`, `status`, `deadline`, `tstamp`, `Priority`) VALUES (NULL, 'Buy Fruits ', 'Buy fruits For Home', 'Started', '2023-09-15 06:00:00', current_timestamp(), '3');

        // The record was not inserted successfully because of this error ---> You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ':00, CASE WHEN `status` = 'Started' THEN '3' WHEN `status` = ...' at line 1

        $result = mysqli_query($conn, $sql);
        if ($result) {
            $insert = true;
        } else {
            echo "The record was not inserted successfully because of this error ---> " . mysqli_error($conn);
        }

    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">

    <title>iNotes - Notes taking made easy</title>

</head>
<style>
.progress {
    height: 20px;
    margin-bottom: 20px;
}

.progress-bar {
    background-color: #007bff; /* Blue color */
    color: #fff; /* Text color */
}

</style>
<body>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="/XP6/welcome.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="form-group">
                            <label for="title">Note Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit"
                                aria-describedby="emailHelp">
                        </div>

                        <div class="form-group">
                            <label for="desc">Note Description</label>
                            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit"
                                rows="3"></textarea>
                        </div>
                        <!-- <button >Submit</button> -->
                        <div class="form-group">
                            <label for="">Status</label>
                            <div></div>
                            <select name="statusEdit" id="statusEdit" class="form-control">
                                <option value="Started">Started</option>
                                <option value="OnHold">OnHold</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="deadline">
                            <input type="datetime-local" name="date_edit" id="date_edit" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer d-block mr-auto">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="Login_System/Partials/mobile-organizers.png"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/Login_System/Welcome.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/XP6/Login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/XP6/Logout.php">Logout</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/XP6/Signup.php">Signup</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2 " style="margin-left:750px" type="search" placeholder="Search"
                        aria-label="Search">
                    <button class="btn btn-outline-success" style="margin-left:10px" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <?php
    if ($insert) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been inserted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
    }
    ?>
    <?php
    if ($update) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> Your note has been updated successfully
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
        </button>
        </div>";
    }
    ?>
    <?php
    if ($deleted) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been deleted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
    }
    ?>
    <?php
    if ($deleted_all) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> All note has been deleted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
    }
    ?>
    <div class="container my-4">
        <h2>Add a Note to iNotes</h2>
        <form action="/XP6/welcome.php" method="POST">
            <div class="form-group">
                <label for="title">Note Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
            </div>

            <div class="form-group">
                <label for="desc">Note Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="form-control">Status</label>
                <!-- <input type="number" min="0" max="100" id="progressInput" class="form-control" name="input-field" aria-describedby="emailHelp" oninput="updateProgressBar()"> -->
                <select name="progress" id="progress" class="form-control">
                    <option value="Started">Started</option>
                    <option value="OnHold">OnHold</option>
                    <option value="Completed">Completed</option>
                </select><br>
                <label for="form-control">Deadline</label>
                <input type="datetime-local" class="form-control" name="date_and_time" id=""><br>
                <!-- <input type="number" id="input-field" placeholder="Enter a value"> -->
            </div>
            <button type="submit" class="btn btn-primary" id="submit-button" onclick="return myFunction()">Add
                Note</button>
            <input type="submit" value="Delete All" class="btn btn-primary" name="deleted">
        </form>
    </div>

    <div class="container my-4">
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                    <th scope="col">Progress</th>
                    <th scope="col">Deadline</th>
                    <th scope="col">Time Of Entry</th>
                    <th scope="col">Priority</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn, $sql);
                $sno = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $sno = $sno + 1;
                    echo "<tr>
                    
            <th scope='row'>" . $sno . "</th>
            <td>" . $row['title'] . "</td>
            <td>" . $row['description'] . "</td>
            <td> <button class='edit btn btn-sm btn-primary' id=" . $row['id'] . ">Edit</button><button class='delete btn btn-sm btn-primary' id=d" . $row['id'] . ">Delete</button></td>
            <td>" . $row['status'] . "</td>
            <td>" . $row['deadline'] . "</td>
            <td>" . $row['tstamp'] . "</td>
            <td>" . $row['priority'] . "</td>
            </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <hr>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();

        });

    </script>
    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit");
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                description = tr.getElementsByTagName("td")[1].innerText;
                progress = tr.getElementsByTagName("td")[3].innerText;
                deadline = tr.getElementsByTagName("td")[4].innerText;  
                console.log(title, description, progress, deadline);
                titleEdit.value = title;
                descriptionEdit.value = description;
                statusEdit.value = progress;
                date_edit.value = deadline;
           //    priorityEdit.value = priority;
                snoEdit.value = e.target.id;
                console.log(e.target.id)
                $('#editModal').modal('toggle');
            })
        })


        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log("edit ");
                sno = e.target.id.substr(1);

                if (confirm("Are you sure you want to delete this note!")) {
                    console.log("yes");
                    window.location = `/XP6/welcome.php?delete=${sno}`;
                    // TODO: Create a form and use post request to submit a form
                }
                else {
                    console.log("no");
                }
            })
        })
            // script.js

    </script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
<script>
    function myFunction(){
        
    }
</script>
</body>

</html>