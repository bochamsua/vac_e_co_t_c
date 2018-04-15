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
    <title>Get CRS from VAECO IDs!</title>
    <meta charset="utf-8" />
</head>
<body>


<h2>Get Phone from Usernames!</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Usernames:<br><textarea name="vaecoids" rows="5" cols="40"></textarea>
    <br>

    <input style="padding: 5px 10px; font-size: 20px; font-weight: bold;" type="submit" name="submit" value="Submit">
</form>

<?php

?>

<?php

require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["vaecoids"])) {
        $nameErr = "Please put VAECO IDs";
    } else {
        $vaecoIds = $_POST["vaecoids"];

        $keyword = $_POST['keyword'];

        $list = array();

        $vaecoIds = explode("\r\n", $vaecoIds);

        $table = "<table border='1'><thead><tr>
    <td>1. STT</td>
    <td>2.Username</td>
    <td>3.Phone</td>


</tr></thead>";
        $j=1;
        $strUsername = array();
        foreach ($vaecoIds as $id) {
            $id = trim($id);
            if($id != ''){

                $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('username', $id)->getFirstItem();
                if($staff->getId()){
                    $customer = Mage::getModel('customer/customer')->load($staff->getId());

                    $table .= "<tr><td>".$j."</td><td>".$id."</td><td>".$customer->getPhone()."</td></tr>";
                }






                $j++;
            }

        }
        $table.= "</table>";

        echo $table;




    }

}
?>


</body>
</html>