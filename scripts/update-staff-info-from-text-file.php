<?php


require_once 'app/Mage.php';


umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

$list = getList();
if($list){
    foreach ($list as $item) {
        $id = $item[1];
        $name = $item[2];
        $dob = $item[3];
        if($dob != ''){
            $year = explode("/", $dob);
            $finalYear = '19'.$year[2];
            $dob = implode("/", array($year[0],$year[1], $finalYear));
            $dob = DateTime::createFromFormat('m/d/Y', $dob)->format('Y-m-d');
        }

        $pob = $item[4];
        $pob = str_replace("\"", "", $pob);
        $cmnd = $item[5];
        $datecmnd = $item[6];
        if($datecmnd != ''){
            //$year = explode("/", $dob);
            //$finalYear = '19'.$year[2];
            //$dob = implode("/", array($year[0],$year[1], $finalYear));
            $datecmnd = DateTime::createFromFormat('j-M-y', $datecmnd)->format('Y-m-d');
        }
        $placecmnd = $item[7];
        $address = $item[8];
        $address = str_replace("\"", "", $address);
        $datehk = $item[12];
        if($datehk != ''){
            //$year = explode("/", $dob);
            //$finalYear = '19'.$year[2];
            //$dob = implode("/", array($year[0],$year[1], $finalYear));
            $datehk = DateTime::createFromFormat('j-M-y', $datehk)->format('Y-m-d');
        }
        $phone = $item[14];

        $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
        if($cusId = $customer->getId()){
            try {
                $customer = Mage::getModel('customer/customer')->load($cusId);


                if($pob != ''){
                    $customer->setPob($pob);
                }
                if($customer->getPhone() == null){
                    $customer->setPhone($phone);

                }
                $customer->setDob($dob);


                $customer->setIdNumber($cmnd);
                $customer->setIdDate($datecmnd);
                $customer->setIdPlace($placecmnd);
                $customer->setCurrentAddress($address);
                if($datehk != ''){
                    $customer->setJointdate($datehk);
                }


                $res = $customer->save();

                if($res){
                    echo "Done ".$name.'<br>';
                }else {
                    echo "Something weng wrong";
                }
            }catch (Exception $e){
                echo $e->getMessage();
            }





        }


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

function getList($fileName = 'listvaeco.txt'){
    if(!file_exists($fileName)){
        return false;
    }
    $danhsach = new SplFileObject($fileName, 'ru');
    $result = array();
    $i=0;
    while (!$danhsach->eof()) {
        //$line = $danhsach->fgets();
        $line=$danhsach->fgets();
        //$line = trim($line);
        //$line = str_replace("  ", " ", $line);
        $array = explode("\t", $line);

        if($i > 0){
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










