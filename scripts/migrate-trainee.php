<?php



require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$tableStaff = $resource->getTableName('bs_staff/staff');

//$writeConnection->query("TRUNCATE TABLE {$tableStaff}");


$groupId = 23;// TTDT

$list = getList();
$result = array();

if(count($list)){

    $i=1;

    foreach ($list as $item) {
        $name = trim($item[1]);
        $dob = trim($item[2]);
        $vaecoId = trim($item[3]);

        //check trainee table
        $trainee = Mage::getModel('bs_trainee/trainee')->getCollection()
            ->addAttributeToFilter('trainee_name', array('like'=>$name))
            ->addAttributeToFilter('trainee_code', array('like'=>'%HV%'))
            ->addAttributeToFilter('vaeco_id', array('null'=>true))
        ;


        if($trainee->count() == 1){//found only one
            $trainee = Mage::getModel('bs_trainee/trainee')->load($trainee->getFirstItem()->getId());

            $traineeCode = $trainee->getTraineeCode();

            //Update Trainee first
            $trainee->setVaecoId($vaecoId)->save();



            //Update Docwise trainee
            $dwtn = Mage::getModel('bs_docwise/trainee')->getCollection()->addFieldToFilter('vaeco_id', $traineeCode)->getFirstItem();
            if($dwtn->getId()){
                $nothing = false;
                $dwtn->setVaecoId($vaecoId)->save();
            }

            //Update Docwise score
            $dwScore = Mage::getModel('bs_docwise/score')->getCollection()->addFieldToFilter('vaeco_id', $traineeCode);
            if($dwScore->count()){
                $nothing = false;
                foreach ($dwScore as $item) {
                    $item->setVaecoId($vaecoId)->save();
                }
            }

            //Update staff
            //check if staff existed in system
            $customerModel = Mage::getModel('customer/customer');

            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();

            if(!$customer->getId()) {

                $phone = '';

                $fullNameArray = explode(" ", $name);
                $firstName = $fullNameArray[0];
                unset($fullNameArray[0]);
                $lastName = implode(" ", $fullNameArray);

                $customerModel->setFirstname($firstName);
                $customerModel->setLastname($lastName);
                $customerModel->setEmail($vaecoId.'@gmail.com');
                $customerModel->setVaecoId($vaecoId);
                $customerModel->setUsername('chưa có');
                $customerModel->setPhone($phone);
                //$customerModel->setPosition('');
                //$customerModel->setDivision('');
            }

            $customerModel->setGroupId($groupId);

            $res = $customerModel->save();


            echo "{$i}. {$name} is saved  <br>\n";
        }elseif($trainee->count() > 1){
            echo "{$i}. two or more trainees have the same name {$name} <br>\n";
        }else {//We will add directly to staff table
            //Update staff
            //check if staff existed in system
            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
            if(!$customer->getId()){
                $customerModel = Mage::getModel('customer/customer');



                $fullNameArray = explode(" ", $name);
                $firstName = $fullNameArray[0];
                unset($fullNameArray[0]);
                $lastName = implode(" ", $fullNameArray);

                $customerModel->setFirstname($firstName);
                $customerModel->setLastname($lastName);
                $customerModel->setEmail($vaecoId.mt_rand(10,1000).'@gmail.com');
                $customerModel->setVaecoId($vaecoId);
                $customerModel->setUsername('chưa có');
                $customerModel->setPhone($phone);
                //$customerModel->setPosition('');
                //$customerModel->setDivision('');


                $customerModel->setGroupId($groupId);

                $res = $customerModel->save();
                if($res){
                    echo "{$i}. {$name} is updated directly to staff table <br>\n";
                }else {
                    echo "{$i}. {$name} unknown error occurred! <br>\n";
                }
            }else {
                echo "{$i}. {$name} this staff already existed! <br>\n";
            }



        }


        $i++;
    }

}




function convertToUnsign($cs, $tolower = false)
{
    /*Mảng chứa tất cả ký tự có dấu trong Tiếng Việt*/
    $marTViet=array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
        "ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề",
        "ế","ệ","ể","ễ",
        "ì","í","ị","ỉ","ĩ",
        "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ",
        "ờ","ớ","ợ","ở","ỡ",
        "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
        "ỳ","ý","ỵ","ỷ","ỹ",
        "đ",
        "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă",
        "Ằ","Ắ","Ặ","Ẳ","Ẵ",
        "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
        "Ì","Í","Ị","Ỉ","Ĩ",
        "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ","Ờ","Ớ","Ợ","Ở","Ỡ",
        "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
        "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
        "Đ");

    /*Mảng chứa tất cả ký tự không dấu tương ứng với mảng $marTViet bên trên*/
    $marKoDau=array("a","a","a","a","a","a","a","a","a","a","a",
        "a","a","a","a","a","a",
        "e","e","e","e","e","e","e","e","e","e","e",
        "i","i","i","i","i",
        "o","o","o","o","o","o","o","o","o","o","o","o",
        "o","o","o","o","o",
        "u","u","u","u","u","u","u","u","u","u","u",
        "y","y","y","y","y",
        "d",
        "A","A","A","A","A","A","A","A","A","A","A","A",
        "A","A","A","A","A",
        "E","E","E","E","E","E","E","E","E","E","E",
        "I","I","I","I","I",
        "O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O",
        "U","U","U","U","U","U","U","U","U","U","U",
        "Y","Y","Y","Y","Y",
        "D");

    if ($tolower) {
        return strtolower(str_replace($marTViet,$marKoDau,$cs));
    }

    return str_replace($marTViet,$marKoDau,$cs);

}

function getList($fileName = 'trainee.txt'){
    $danhsach = new SplFileObject($fileName);
    $result = array();
    $i=0;
    while (!$danhsach->eof()) {
        if($i>0){//ignore the first line, treat as an example
            $line = $danhsach->fgets();
            $line = trim($line);
            $line = str_replace("  ", " ", $line);
            $array = explode("\t", $line);
            $result[] = $array;
        }


        $i++;

    }

    $danhsach = null;

    return $result;
}

function getTextBetweenTags($string, $tagname) {
    // Create DOM from string
    $html = str_get_html($string);

    $titles = array();
    // Find all tags
    foreach($html->find($tagname) as $element) {
        $titles[] = $element->plaintext;
    }

    return $titles;
}

function formatPhone($phone)
{
    $phone = preg_replace("/[^0-9]/", "", $phone);

    if(strlen($phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
    elseif(strlen($phone) == 10)
        return preg_replace("/([0-9]{4})([0-9]{3})([0-9]{3})/", "$1-$2-$3", $phone);
    elseif(strlen($phone) == 11)
        return preg_replace("/([0-9]{5})([0-9]{3})([0-9]{3})/", "$1-$2-$3", $phone);
    else
        return $phone;
}

/*
 *
TRUNCATE  bstaffs;
ALTER TABLE  bstaffs AUTO_INCREMENT = 1;
 */










