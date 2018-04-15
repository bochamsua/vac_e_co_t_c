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


<h2>Get CRS from VAECO IDs!</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    VAECO IDs:<br><textarea name="vaecoids" rows="5" cols="40"></textarea>
    <br>
    <input type="text" name="keyword" value=""> Keyword?
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

        $keyword = $_POST['keyword'];

        $list = array();

        $vaecoIds = explode("\r\n", $vaecoIds);

        $table = "<table border='1'><thead><tr>
    <td>STT</td>
    <td>1.Name</td>
    <td>2.VAECO ID</td>
    <td>3.CRS Number</td>
    <td>4.Issue Date</td>

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

                $crs = Mage::getModel('bs_certificate/crs')->getCollection()
                    ->addFieldToFilter('vaeco_id', $id)
                    ->addFieldToFilter('issue_date', array('lt'=>'2014-10-01 00:00:00'))
                ;
                if($keyword != ''){
                    $crs->addFieldToFilter('ac_type', array('like'=>'%'.$keyword.'%'));
                }
                $crs->addFieldToFilter('category', array('nlike'=>'C'));
                $crs->setOrder('issue_date');

                if($crs->count()){

                    $name = $crs->getFirstItem()->getName();
                    $number = $crs->getFirstItem()->getAuthorizationNumber();
                    $cat = $crs->getFirstItem()->getCategory();

                    $date = Mage::getModel('core/date')->date("m/d/y", $crs->getFirstItem()->getIssueDate());

                    //$table .= "<tr><td>".$j."</td><td>".$name."</td><td>".$id."</td><td>".$number."</td><td>".$date."</td></tr>";
                    $table .= "<tr><td>".$number."</td><td>".$date."</td><td>".$cat."</td></tr>";
                }else {
                    $cus = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                    $name = 'Not Exist';
                    if($cus->getId()){
                        $customer = Mage::getModel('customer/customer')->load($cus->getId());
                        $name  = $customer->getName();
                    }
                    //$table .= "<tr><td>".$j."</td><td>".$name."</td><td>".$id."</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                    $table .= "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
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