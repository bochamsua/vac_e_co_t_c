<!DOCTYPE HTML>
<html>
<head>
    <style>
        .error {color: #FF0000;}
    </style>
</head>
<body>


<h2>For Ms Trang!</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    VAECO IDs:<textarea name="vaecoids" rows="5" cols="40"></textarea>
    <br><br>

    <br><br>
    <input type="submit" name="submit" value="Submit">
</form>

<?php

?>

<?php

require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["vaecoids"])) {
        $nameErr = "Please put VAECO IDs";
    } else {
        $vaecoIds = $_POST["vaecoids"];

        $vaecoIds = explode("\r\n", $vaecoIds);

        echo "<table>";
        foreach ($vaecoIds as $id) {
            $id = trim($id);
            if(strlen($id) == 5){
                $id = "VAE".$id;
            }elseif (strlen($id) == 4){
                $id = "VAE0".$id;
            }
            $id = strtoupper($id);

            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
            if($customer->getId()){
                $cus = Mage::getModel('customer/customer')->load($customer->getId());
                $dob = $cus->getDob();

                $date = Mage::getModel('core/date')->date("d-M-Y", $dob);

                echo "<tr><td>".$date."</td></tr>";
            }else {
                echo "<tr><td>".$id."</td></tr>";
            }


        }
        echo "</table>";


    }

}
?>


</body>
</html>