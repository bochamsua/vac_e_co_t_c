<?php
require_once '../app/Mage.php';


umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$staffs = Mage::getModel('bs_staff/staff')->getCollection();//->addFieldToFilter('vaeco_id', 'VAE02119');
if($staffs->count()){
    $i=1;
    foreach ($staffs as $st) {
        $vaecoId = $st->getVaecoId();

        //check if staff existed in system
        $customerModel = Mage::getModel('customer/customer');

        $fullName = $st->getFullname();

        $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
        $groupId = Mage::getModel('customer/group')->getCollection()->addFieldToFilter('customer_group_name_vi', array('like'=>$st->getDepartment()))->getFirstItem();
        $customerGroupId = null;
        if($groupId->getId()){
            $customerGroupId = $groupId->getId();
        }
        if($customerGroupId == null){
            echo "The department ".$st->getDepartment()." doesn't exist, we need to add it <br>"; break;

        }



        if($cusId = $customer->getId()){
            $customerModel->load($cusId);

            $phone = $customerModel->getPhone();
            if(!$phone){
                $phone = '?';
            }


            if($st->getPhone() != '' && strlen($st->getPhone()) > 3){
                $phone = $st->getPhone();
            }
            $customerModel->setPhone($phone);

            $customerModel->setUsername($st->getUsername());



            $customerModel->setPosition($st->getPosition());
            $customerModel->setDivision($st->getDivision());

        }else {

            $phone = '';
            if($st->getPhone() != ''){
                $phone = $st->getPhone();
            }

            $fullNameArray = explode(" ", $fullName);
            $firstName = $fullNameArray[0];
            unset($fullNameArray[0]);
            $lastName = implode(" ", $fullNameArray);

            $customerModel->setFirstname($firstName);
            $customerModel->setLastname($lastName);
            $customerModel->setEmail($vaecoId.mt_rand(1,1000).'@gmail.com');
            $customerModel->setVaecoId($vaecoId);
            $customerModel->setUsername($st->getUsername());
            $customerModel->setPhone($phone);
            $customerModel->setPosition($st->getPosition());
            $customerModel->setDivision($st->getDivision());
        }

        $customerModel->setGroupId($customerGroupId);

        $res = $customerModel->save();

        if($res){
            echo "Done {$i} br> \n";
        }else {
            echo "Something weng wrong";
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

function getList($fileName = 'danhsach.txt'){
    $danhsach = new SplFileObject($fileName);
    $result = array();
    while (!$danhsach->eof()) {
        $line = $danhsach->fgets();
        $line = trim($line);
        $line = str_replace("  ", " ", $line);
        $array = explode("\t", $line);

        $username = null;
        $id = null;
        $name = null;
        if(count($array) && count($array) > 3){
            $username = trim($array[0]);
            $name = trim($array[1]);
            $id = trim($array[2]);
            if($id != "" && $id != "VAE00000" && strlen($id) < 10 && $username != ""){

                $result[] = array(
                    'id'=>$id,
                    'name'=>$name,
                    'username'=>$username
                );
            }


        }
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










