<h3>File Upload using PHP</h3>

<?php
if (isset($_POST['upload'])) {

    $name = $_FILES['myfile']['name'];
    $size = $_FILES['myfile']['size'];
    $tmp = $_FILES['myfile']['tmp_name'];

    $allowed = ["jpg", "pdf"];
    $ext = pathinfo($name, PATHINFO_EXTENSION);

    if (!in_array($ext, $allowed)) {
        echo "File type not allowed";
    }
    elseif ($size > 400 * 1024) {
        echo "File size must be within 400 KB";
    }
    else {
        if (move_uploaded_file($tmp, "uploads/" . $name)) {
            echo "File uploaded successfully";
        }
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="myfile" required>
    <input type="submit" name="upload" value="UPLOAD">
</form>