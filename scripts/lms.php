<!DOCTYPE HTML>
<html>
<head>
    <style>
        .error {color: #FF0000;}
        table thead {
            background-color: #ccc;
        }
        table tr td {
            padding: 3px;
        }
    </style>
    <title>Get LMS activation key!</title>
    <meta charset="utf-8" />
</head>
<body>


<h2>Get LMS activation key!</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    VAECO IDs:<br><textarea name="requestkey" rows="5" cols="40"></textarea>
    <br>

    <input style="padding: 5px 10px; font-size: 20px; font-weight: bold;" type="submit" name="submit" value="Submit">
</form>

<?php

?>

<?php

require_once "bosua.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["requestkey"])) {
        echo "Please put Key";
    } else {
        $requestKey = $_POST["requestkey"];

        $data = LMSCrypt::decrypt($requestKey);
        $data['activated'] = '1';
        $data['expiration'] = '2085-03-31';
        $data['revocationReason'] = null;

        $resultKey = LMSCrypt::crypt($data);

        echo  $resultKey;


    }

}


?>


</body>
</html>