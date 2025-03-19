<?php
$conn = mysqli_connect('localhost', 'root', '', 'tiffin_service');

if (isset($_POST['register'])) {
    $ReceipeName = $_POST['ReceipeName'];
    $Price = $_POST['Price'];
    $threeday = $_POST['threeday'];
    $oneweek = $_POST['oneweek'];
    $twoweek = $_POST['twoweek'];
    $onemonth = $_POST['onemonth'];

    $image_name = $_FILES['my_image']['name'];
    $tmp = explode('.', $image_name);
    $newfilename = round(microtime(true)) . '.' . end($tmp);
    $uploadpath = "uploads/" . $newfilename;
    
    if (move_uploaded_file($_FILES['my_image']['tmp_name'], $uploadpath)) {
        $sql = "INSERT INTO image_upload 
                (ReceipeName, Price, threeday, oneweek, twoweek, onemonth, imagesss) 
                VALUES 
                ('$ReceipeName', '$Price', '$threeday', '$oneweek', '$twoweek', '$onemonth', '$newfilename')";

        $data = mysqli_query($conn, $sql);

        if ($data) {
            echo "Data inserted into database";
        } else {
            echo "Failed to insert data into database: " . mysqli_error($conn);
        }
    } else {
        echo "Image upload failed.";
    }
}
?>
