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
    <title>Get basic info from VAECO IDs!</title>
    <meta charset="utf-8" />
</head>
<body>


<h2>Get basic info from VAECO IDs!</h2>
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

        $lines = explode("\r\n", $vaecoIds);


        $j=1;
        $strUsername = array();
        $table = "<table border='1'>";
        foreach ($lines as $item) {

            $id = trim($item);
            if($id != ''){
                $id = trim($id);
                if(strlen($id) == 5){
                    $id = "VAE".$id;
                }elseif (strlen($id) == 4){
                    $id = "VAE0".$id;
                }
                $id = strtoupper($id);

                $vaecoId = $id;

                $name = 'not found';
                $dob = 'not found';
                $joint = 'not found';
                $fileLocation = 'not found';
                $dept = 'not found';





                $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                if($customer->getId()){
                    $cus = Mage::getModel('customer/customer')->load($customer->getId());
                    $dob = $cus->getDob();

                    $dob = Mage::getModel('core/date')->date("d/m/Y", $dob);
                    $joint = Mage::getModel('core/date')->date("d/m/Y", $cus->getJointdate());

                    $name = $cus->getName();






                    $fileLocation = '';
                    $ins = Mage::getModel('bs_instructor/instructor')->getCollection()->addAttributeToFilter('ivaecoid', $id)->getFirstItem();
                    if($ins->getId()){
                        $instructor = Mage::getModel('bs_instructor/instructor')->load($ins->getId());
                        $fileLocation = $instructor->getIfilefolder();
                    }



                    $groupId = $cus->getGroupId();
                    $group = Mage::getModel('customer/group')->load($groupId);
                    $dept = $group->getCustomerGroupName();
                    $dept = str_replace("Trung tâm Bảo dưỡng","TTBD", $dept);



                }



                /**/



                $table .= "<tr><td>".$fileLocation."</td></tr>";//<td>".$name."</td><td>".$dob."</td><td>".$joint."</td><td>".$dept."</td>



                 $j++;
            }

        }
        $table.= "</table><br>";

        echo $table;






    }

}


?>


</body>
</html>