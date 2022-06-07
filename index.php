<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Browser</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="./assets/buttons.css">
    <link rel="stylesheet" href="./assets/forms.css">
</head>

<body>

    <?php

    if (isset($_GET['path'])) {
        $path = './' . $_GET['path'];
    } else {
        $path = './';
    }

    if (isset($_POST['name']) && $_POST['name'] != '') { // creates a folder if folder already exists gives out error 
        if (file_exists($path . "/" . ($_POST['name']))) {
            echo "<div class='errorMsg'>Directory already exists. Please try a different name!</div>"; // dir creation error
            header('Refresh:3');
        } else {
            mkdir($path . "/" . ($_POST['name']));
        }
    }

    if (isset($_POST['upload'])) { // File upload
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $file_store = ($path . "/") . $file_name;
        move_uploaded_file($file_tmp, $file_store);
    }

    if (array_key_exists('action', $_GET)) { // File delete; Download;
        if (array_key_exists('file', $_GET)) {
            $file = "./" . $_GET['path'] . "./" . $_GET['file'];
            if ($_GET['action'] == 'delete') {
                unlink($path . "/" . $_GET['file']);
                ob_start();
                header('Location:' . '?path=' . ltrim($path, './'));
            } elseif ($_GET['action'] == 'download') {
                $downloadFile = str_replace("&nbsp;", " ", htmlentities($file, 0, 'utf-8'));
                ob_clean();
                ob_flush();
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename=' . basename($downloadFile));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($downloadFile));
                ob_end_flush();
                readfile($downloadFile);
                exit;
            }
        }
    }

    $dir_contents = scandir($path);

    $split = explode("/", $path); // Back function
    $emptyString = "";
    for ($i = 0; $i < count($split) - 1; $i++) {
        if ($split[$i] == "")
            continue;
        $emptyString .= "/" . $split[$i];
    }

    // Icons for buttons
    $deleteIcon = file_get_contents("./svg/delete.svg");
    $downloadIcon = file_get_contents("./svg/download.svg");

    echo ("<div class='header'><button class='buttonBack'>" . "<a href='./?path=" . ltrim($emptyString, "./") . "'>" . "Back" . "</a>" . "</button>"); //Back button


    // Table start
    echo ("<table>
            <thead class='tableTop'>
                <tr>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>");
    echo ("<tbody>");

    foreach ($dir_contents as $item) {
        if ($item == "." || $item == "..") { //  (|| $item == "index.php" ) condition to hide file.
            continue;
        }
        echo ("<tr><td>" . (is_dir($path . "/" . $item) ? "<span class='fold'>Folder</span>" : "<span class='file'>File</span>") . "</td>");
        if (is_dir($path . "/" . $item)) {
            echo ("<td>" . "<a class='folder-link' href='./?path=" . ltrim($path, "./") . "/" . ($item) . "'>" . $item .  "</a></td>");
        } else {
            echo ("<td>" . $item . "</td>");
        }
        if (is_file($path . "/" . $item)) { // prevention to delete required files(styling and php)
            if ($item != "index.php") {
                echo ("<td><a class='deleteButton'href='./?path=" . ltrim($path, "./") . "&file=" . $item . "&action=delete" . "'>" . "$deleteIcon</a><a class='downloadButton' href='./?path=" . ltrim($path, "./") . "&file=" . $item . "&action=download" . "'>" . "$downloadIcon</a></td>");
            } else {
                echo ("<td></td>");
            }
        } else {
            echo ("<td></td>");
        }
    }
    echo ("</tbody></table></div>"); // Table end
    ?>

    <footer class="footer-forms">
        <form class="createFolder" action="<?php $path ?>" method="POST">
            <label for="name">Create new directory:<br></label>
            <input type="text" id="name" name="name" placeholder="Folder name" maxlength="32">
            <input id="createButton" type="submit" name="create" value="Create">
            <br>
        </form>
        <div class="uploadContainer">
            <form class="upload-file" action="" method="POST" enctype="multipart/form-data">
                <label>Upload a file:<br></label>
                <input type="file" name="file" id="file">
                <input id="UploadButton" type="submit" name="upload" value="Upload">
            </form>
        </div>
    </footer>

</body>

</html>