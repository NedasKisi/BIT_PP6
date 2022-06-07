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

    $dir_contents = scandir($path);

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
        if ($item == "." || $item == "..") {
            continue;
        }
        echo ("<tr><td>" . (is_dir($path . "/" . $item) ? "<span class='fold'>Folder</span>" : "<span class='file'>File</span>") . "</td>");
        if (is_dir($path . "/" . $item)) {
            echo ("<td>" . "<a class='folder-link' href='./?path=" . ltrim($path, "./") . "/" . ($item) . "'>" . $item .  "</a></td>");
        } else {
            echo ("<td>" . $item . "</td>");
        }
        if (is_file($path . "/" . $item)) {
            if ($item != "index.php") {
                echo ("<td></td>");
            }
        } else {
            echo ("<td></td>");
        }
    }
    echo ("</tbody></table></div>");
    // Table end
    ?>

</body>

</html>