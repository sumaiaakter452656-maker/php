<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP File upload</title>
</head>

<body>
    <h3>File Upload using PHP</h3>
    <?php
    if (isset($_POST['upload'])) {
        $name = $_FILES['myfile']['name'];
        $size = $_FILES['myfile']['size'];
        $fileTmpName = $_FILES['myfile']['tmp_name'];
        $targetPath = "uploads/$name";
        $status = true;
        // Allowed file type
        $allowedTypes = ["jpg", "pdf"];
        $size_kb = $size / 1024;
        $fileExt = pathinfo($name, PATHINFO_EXTENSION);
        // Filetlype check
        if (!in_array($fileExt, $allowedTypes)) {
            echo "Filetype not allowed<br><br>";
            $status = false;
        }
        // Filesize check
        if ($size_kb > "400") {
            echo "Size not allowed<br><br>";
            $status = false;
        }
        if ($status) {
            move_uploaded_file($fileTmpName, $targetPath);
            echo "Success";
        }
    }
    ?>
    <br><br>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="myfile">
        <input type="submit" name="upload" value="UPLOAD">
    </form>
</body>

</html>