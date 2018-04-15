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
        $id = trim($item[0]);
        $name = trim($item[1]);
        $certType = trim($item[2]);
        $desc = trim($item[3]);
        $applyFor = trim($item[4]);
        $certNo = trim($item[5]);
        $issueDate = trim($item[6]);
        if($issueDate != ''){
            $issueDate = DateTime::createFromFormat('j-M-y', $issueDate)->format('Y-m-d');
        }

        $expireDate = trim($item[7]);
        if($expireDate != ''){
            $expireDate = DateTime::createFromFormat('j-M-y', $expireDate)->format('Y-m-d');
        }




        $cert = Mage::getModel('bs_certificate/certificate');
        try {
            $cert->setStaffName($name);
            $cert->setVaecoId($id);
            $cert->setDescription($desc);
            $cert->setApplyFor($applyFor);
            $cert->setCertNo($certNo);
            $cert->setIssueDate($issueDate);
            $cert->setExpireDate($expireDate);
            $cert->setCertType($certType);

            $res = $cert->save();

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

function getList($fileName = 'caav.txt'){
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










