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
    <input type="hidden" name="username" value="0" />
    <input type="checkbox" name="username" value="1"> Get only Usernames?
    <br>
    <input type="hidden" name="list" value="0" />
    <input type="checkbox" name="list" value="1"> List only?
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

        $table = "<table border='1'><thead><tr>
    <td>STT</td>
    <td>1.VAECO ID</td>
    <td>2.Name</td>
    <td>3.Phone</td>
    <td>4.Position</td>
    <td>5.Division</td>
    <td>6.Department</td>
    <td>7.Username</td>
    <td>8.Birthday</td>
</tr></thead>";
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


                    $groupId = $cus->getGroupId();
                    $group = Mage::getModel('customer/group')->load($groupId);
                    $dept = $group->getCustomerGroupCode();

                    $list[] = $j.'. '.$name.' - '.$vaecoId;

                }

                $table .= "<tr><td>".$j."</td><td>".$vaecoId."</td><td>".$name."</td><td>".$phone."</td><td>".$position."</td><td>".$division."</td><td>".$dept."</td><td>".$username."</td><td>".$dob."</td></tr>";


                $j++;
            }

        }
        $table.= "</table>";

        if($onlyList == 1){
            echo implode("<br>", $list);
        }elseif($onlyUsername == 1){
            echo 'Username: '.implode(", ", $strUsername);
        }else {
            echo $table;
            echo 'Username: '.implode(", ", $strUsername);
        }




    }

}
?>


</body>
</html>