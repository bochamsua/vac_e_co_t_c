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
    <title>Get all info from VAECO IDs!</title>
    <meta charset="utf-8" />
</head>
<body>


<h2>Get all info from VAECO IDs!</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    VAECO IDs:<br><textarea name="vaecoids" rows="5" cols="40"></textarea>
    <br>

    <p style="font-style: italic; color: blue;">Multiple values are allowed. Put each value in one row. VAECO IDs can be in any of following formats: VAE02907, 02907 or just 2907.
    <br>
        You can just copy the VAECO IDs from a list if you have one? Empty lines are fine, they will be ignored. So just copy and paste.
    </p>
    <br>
    <input style="padding: 5px 10px; font-size: 20px; font-weight: bold;" type="submit" name="submit" value="Submit">
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

        $onlyUsername = $_POST['username'];
        $onlyList = $_POST['list'];

        $list = array();

        $vaecoIds = explode("\r\n", $vaecoIds);


        $j=1;
        $strUsername = array();
        foreach ($vaecoIds as $id) {
            $id = trim($id);
            if($id != ''){
                if(strlen($id) == 5){
                    $id = "VAE".$id;
                }elseif (strlen($id) == 4){
                    $id = "VAE0".$id;
                }
                $id = strtoupper($id);

                $vaecoId = $id;
                $name = '';
                $dept = '';
                $phone = '';
                $username = '';
                $dob = '';
                $nameUpper = '';
                $shortName = '';
                $dobNew = '';


                $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                if($customer->getId()){
                    $cus = Mage::getModel('customer/customer')->load($customer->getId());
                    $dob = $cus->getDob();
                    $dobNew = Mage::getModel('core/date')->date("d-M-Y", $dob);
                    $dob = Mage::getModel('core/date')->date("d/m/Y", $dob);

                    $name = $cus->getName();
                    $nameUpper = mb_strtoupper($name, 'UTF-8');
                    $phone = $cus->getPhone();
                    $username = $cus->getUsername();
                    $position = $cus->getPosition();
                    $division = $cus->getDivision();

                    $strUsername[] = $username;

                    $nameArray = explode(" ", $name);
                    $last = $nameArray[count($nameArray)-1];

                    for($i=0; $i< count($nameArray)-1; $i++){
                        $shortName .= $nameArray[$i][0].'.';
                    }
                    $shortName .= $last;

                    $fileLocation = '';
                    $ins = Mage::getModel('bs_instructor/instructor')->getCollection()->addAttributeToFilter('ivaecoid', $id)->getFirstItem();
                    if($ins->getId()){
                        $instructor = Mage::getModel('bs_instructor/instructor')->load($ins->getId());
                        $fileLocation = $instructor->getIfilefolder();
                    }

                    $function = $customer->getStaffFunction();
                    if($function == 3){
                        $function = 'Avionics';
                    }elseif($function == 4){
                        $function = 'Mechanic';
                    }

                    $groupId = $cus->getGroupId();
                    $group = Mage::getModel('customer/group')->load($groupId);
                    $dept = $group->getCustomerGroupNameVi();
                    $dept = str_replace("Trung tâm Bảo dưỡng","TTBD", $dept);

                    $list[] = $j.'. '.$name.' - '.$vaecoId;

                }

                $table = "<table border='1'>";
                $table .= "<tr><td>1. Name:</td><td>".$name."</td><td>3. Date of Birth:</td><td>".$dob."</td><td>5. Mobile phone:</td><td>".$phone."</td><td>7. Instructor No.</td><td>&nbsp;</td></tr>";
                $table .= "<tr><td>2. Company ID:</td><td>".$vaecoId."</td><td>4. Dept./Center:</td><td>".$dept."</td><td>6. Location:</td><td>".$fileLocation."</td><td>8. Update.</td><td>&nbsp;</td></tr>";
                $table .= "<tr><td>9. Function (M/A):</td><td>".$function."</td></tr>";
                $table.= "</table><br>";

                echo $table;
                $j++;
            }

        }







    }

}
?>


</body>
</html>