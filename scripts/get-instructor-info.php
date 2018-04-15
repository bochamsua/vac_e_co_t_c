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
    <title>Check Instructor</title>
    <meta charset="utf-8" />
</head>
<body>


<h2>Check Instructor</h2>
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

                $ins = Mage::getModel('bs_instructor/instructor')->getCollection()->addAttributeToFilter('ivaecoid', $id)->getFirstItem();
                if($ins->getId()){
                    $instructor = Mage::getModel('bs_instructor/instructor')->load($ins->getId());
                    $name = $instructor->getIname();

                    echo '<strong>'.$j.'. '.$name.' - '.$id.' is an instructor.</strong><br>';

                    $approvedCourses = Mage::getModel('bs_instructor/instructor_curriculum')->getCollection()->addInstructorFilter($ins->getId());
                    if($approvedCourses->count()){
                        foreach ($approvedCourses as $c) {
                            $cu = Mage::getModel('bs_traininglist/curriculum')->load($c->getId());
                            echo '- '.$cu->getCName().'<br>';
                        }


                    }
                    echo '<br><br>';



                }else {
                    $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                    if($customer->getId()) {
                        $cus = Mage::getModel('customer/customer')->load($customer->getId());

                        echo '<strong style="color: red;">'.$j.'. '.$cus->getName().' - '.$id.' is NOT an instructor! </strong> <br>';
                    }
                }







                $j++;
            }

        }







    }

}


?>


</body>
</html>