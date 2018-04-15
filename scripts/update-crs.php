<?php



require_once 'app/Mage.php';


umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);



$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');

//Staff
//Authorisation
//Validity


//get all unique vaeco Id
$sql = "SELECT * FROM Staff WHERE CompanyId LIKE 'VAE%' AND CompanyID NOT LIKE '%.%'";
$allIds = $readConnection->fetchAll($sql);
if(count($allIds)){
    foreach ($allIds as $id) {
        $index = $id['ID'];
        $vaecoId = trim($id['CompanyID']);

        $cus = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
        if($cus->getId()){
            //get auth number

            $authNumber = $readConnection->fetchOne("SELECT AuthNumber FROM Authorisation WHERE ID = {$index}");
            if($authNumber){
                $customer = Mage::getModel('customer/customer')->load($cus->getId());
                $name = $customer->getName();

                //Get all info
                $infos = $readConnection->fetchAll("SELECT * FROM Validity WHERE AuthNumber = '{$authNumber}'");
                if(count($infos)){
                    foreach ($infos as $item) {
                        $crs = Mage::getModel('bs_certificate/crs');
                        $crs->setName($name)
                            ->setVaecoId($vaecoId)
                            ->setAuthorizationNumber($authNumber)
                            ->setCategory($item['Category'])
                            ->setAcType($item['AircraftType'])
                            ->setEngineType($item['EngineType'])
                            ->setIssueDate($item['IssueDate'])
                            ->setExpireDate($item['ValidTo'])
                            ->setFunctionTitle($item['Functions'])
                            ->setLimitation($item['Limitation'])
                            ->setReason($item['Reason'])
                            ;
                        $crs->save();


                    }

                }

            }

            echo $vaecoId.',';
        }



    }

}



$i=1;
foreach ($list as $item) {

    $vaecoId = $item['vaeco_id'];
    $vaecoId = strtoupper($vaecoId);
    $basic = $item['basic'];
    $function = $item['function'];
    $result = getBasic($basic, $function);



    if($result){
        $cus = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
        //echo $cus->getSelect()->__toString(); die;

        if($cus->getId()){
            $customer = Mage::getModel('customer/customer')->load($cus->getId());
            $customer->setStaffBasic($result['basic'])->setStaffFunction($result['function'])->save();

            echo "Done {$i}-";

            $i++;

        }
    }


}

function getBasic($basic, $position){
    $basic = "moke".$basic;
    $position = "moke".$position;

    $result = array();
    $engineer = array(
        'k/s',
        'kỹ sư',
        'ks',
        'thạc sỹ'
    );
    $technician = array(
        'thợ',
        'cao',
        'cđ',
        'cd'
    );

    $m = array(
        'cơ giới',
        'cơ khí',
        'ck'

    );

    $a = array(
        'điện',
        'đặc',
        'công',
        'bộ',
        'bm'
    );


    //<option value="3">Avionics</option>
    //<option value="4">Mechanic</option>
    //
    //<option value="6">Engineer</option>
    //<option value="5">Techincian</option>


    if(strposa($basic,$engineer) || strposa($position, $engineer)){
        $result['basic'] = 6;

        if(strposa($basic,$m) || strposa($position, $m)){
            $result['function'] = 4;

        }else {
            $result['function'] = 3;
        }


    }elseif(strposa($basic,$technician) || strposa($position, $technician)){
        $result['basic'] = 5;

        if(strposa($basic,$m) || strposa($position, $m)){
            $result['function'] = 4;

        }else {
            $result['function'] = 3;
        }

    }

    if(count($result)){
        return $result;
    }

    return false;
}




function strposa($haystack, $needle, $offset=0) {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $query) {
        if(strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
    }
    return false;
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

function getList($fileName = 'list-basic.txt'){
    $danhsach = new SplFileObject($fileName);
    $result = array();
    while (!$danhsach->eof()) {
        $line = $danhsach->fgets();
        $line = trim($line);
        $line = str_replace("  ", " ", $line);
        $line = strtolower($line);
        $array = explode("\t", $line);

        $vaecoId = preg_replace('/[^0-9a-zA-Z]+/', '', $array[0]);

        $result[] = array(
            'vaeco_id'=>$vaecoId,
            'basic'=>$array[1],
            'position'=>$array[2]
        );
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










