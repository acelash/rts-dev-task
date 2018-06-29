<?php
$message = 'If u want to join our team please send your CV.';
$isSubmited = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $file = fopen("submits.txt", "w") or die("Unable to open file!");

    $txt = "";
    if(array_key_exists('name',$_POST) && strlen(trim($_POST['name']))) $txt .= "Name: ".$_POST['name']."; ";
    if(array_key_exists('email',$_POST) && strlen(trim($_POST['email']))) $txt .= "Email: ".$_POST['email']."; ";
    if(array_key_exists('message',$_POST) && strlen(trim($_POST['message']))) $txt .= "Message: ".$_POST['message']."; ";

    // CV
    if(array_key_exists('file',$_FILES) && $_FILES["file"]){
        $target_dir = "uploads/";
        $allowedFileTypes = ['pdf','txt','doc','docx'];
        $newFileName = time()."-".basename($_FILES["file"]["name"]);
        $target_file = $target_dir.$newFileName;
        $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        if ($_FILES["file"]["size"] > 5000000) {
            die("Sorry, your file is too large.");
        }
        if (!in_array($fileType,$allowedFileTypes)) {
            die("Sorry, this file format is not allowed. We accept only: ".implode(', ',$allowedFileTypes));
        }

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $txt .= "CV: ".$newFileName."; ";
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    }

    if($txt){
        $txt .= "\n";
        fwrite($file, $txt);
        $message = "Successfully sumbited!";
        $isSubmited = true;
    } else {
        $message = "Please provide some info about you.";
    }

    fclose($file);
}

?>

<html>
<head>
    <title>Dev task</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,500,500i,700" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<form class="panel" method="post" enctype="multipart/form-data">
    <div class="title">
        Job offer
    </div>
    <div class="subtitle <?php if ($isSubmited) echo 'success'; ?> "><?php echo $message; ?></div>
    <hr class="line">
    <div class="form-body">

        <div class="form-group">
            <label for="name">Your name</label>
            <div class="field-container">
                <div class="prepend">
                    <img src="images/user.png">
                </div>
                <input type="text" name="name" class="form-control" id="name" placeholder="example: John Doe">
            </div>
        </div>

        <div class="form-group">
            <label for="email">Your CV</label>
            <div class="field-container">
                <div class="prepend">
                    <img src="images/file.png">
                </div>
                <input type="file" name="file" class="form-control" id="file">
            </div>
        </div>

        <div class="form-group">
            <label for="email">Your Email Address</label>
            <div class="field-container">
                <div class="prepend">
                    <img src="images/email.png">
                </div>
                <input type="email" name="email" class="form-control" id="email"
                       placeholder="example: office@example.com">
            </div>
        </div>

        <div class="form-group">
            <label for="message">Your Message</label>
            <div class="field-container">
                <div class="prepend">
                    <img src="images/message.png">
                </div>
                <textarea name="message" class="form-control" id="message"
                          placeholder="example: I'd like to say &quot;Hello World!&quot;"></textarea>
            </div>
        </div>

        <button class="submit">Submit your CV</button>
    </div>
</form>
</body>
</html>

