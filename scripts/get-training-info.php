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
    <title>Get training info from VAECO IDs!</title>
    <meta charset="utf-8" />
</head>
<body>


<h2>Get all info from VAECO IDs!</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    VAECO IDs:<br><textarea name="vaecoids" rows="5" cols="40"></textarea>
    <br>
    <input type="text" name="search" value=""> List only?

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

        $search = $_POST['search'];

        $search = explode(",", $search);

        $list = getList('hf-trainer.txt');



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
                $start = 'not found';
                $end = 'not found';
                $cert = 'not found';
                $place = 'not found';

                foreach ($list as $key=>$values) {

                    if($key == $vaecoId){
                        foreach ($values as $v) {
                            foreach ($search as $s) {
                                if(strpos("moke".strtolower($v[1]), $s) && !strpos("moke".strtolower($v[1]), "advance")){
                                    $name = $v[1];
                                    $place = $v[2];
                                    $start = $v[3];
                                    $end = $v[4];
                                    $cert = $v[5];

                                    break;

                                }

                            }

                        }
                    }
                }

                if($name == 'not found'){
                    $filter = array();
                    foreach ($search as $ss) {
                        $filter[] = array('like' => '%'.$ss.'%');

                    }
                    $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();


                    if($customer->getId()){

                        $related = Mage::getModel('bs_staffinfo/training')->getCollection()
                            ->addFieldToFilter('staff_id', $customer->getId())
                            ->addFieldToFilter('course', $filter)
                        ;

                        if($related->count()){
                            $rel = Mage::getModel('bs_staffinfo/training')->load($related->getFirstItem()->getId());
                            $name = $rel->getCourse();
                            $cert = $rel->getCertificate();
                            $place = $rel->getOrganization();
                            $start = new DateTime($rel->getStartDate());
                            $start = $start->format('j-M-y');
                            $end = new DateTime($rel->getEndDate());
                            $end = $end->format('j-M-y');
                        }
                    }
                }



                /**/



                $table .= "<tr><td>".$name."</td><td>".$cert."</td><td>".$place."</td><td>".$start."</td><td>".$end."</td></tr>";



                 $j++;
            }

        }
        $table.= "</table><br>";

        echo $table;






    }

}

function getList($fileName = 'hf-trainer.txt'){
    if(!file_exists($fileName)){
        return false;
    }
    $danhsach = new SplFileObject($fileName, 'ru');
    $result = array();
    $i=0;
    while (!$danhsach->eof()) {
        $line = $danhsach->fgets();
        $line = trim($line);
        //$line = str_replace(" ", "", $line);
        $array = explode("\t", $line);

        if($i > 0){
            $key = $array[0];
            $result[$key][] = $array;
        }
        $i++;
    }

    $danhsach = null;

    return $result;
}
?>


</body>
</html>