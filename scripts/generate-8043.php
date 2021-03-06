<?php

require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$list = getList('all.txt');
$template = Mage::helper('bs_formtemplate')->getFormtemplate('8043');
$i=1;
foreach ($list as $item) {

    $add = 'OK';

    $id = $item[0];
    $name = $item[1];
    $type = $item[2];
    $ti = trim($item[3]);
    $pi = trim($item[4]);
    $te = trim($item[5]);
    $pe = trim($item[6]);
    $function = trim($item[7]);
    $course = trim($item[8]);
    $cert = trim($item[9]);
    $country = trim($item[10]);
    $start = trim($item[11]);//d/m/Y
    $end = trim($item[12]);//d/m/Y

    $courseHf = trim($item[13]);
    $courseHfCert = trim($item[14]);
    $courseHfCountry = trim($item[15]);
    $courseHfStart = trim($item[16]);
    if($courseHfStart != 'N/A' && $courseHfStart != ''){

        if(count(explode("-",$courseHfStart)) == 3){
            $courseHfStart = DateTime::createFromFormat("j-M-y",$courseHfStart)->format("d/m/Y");//j-M-y
        }else {
            $add = 'CHECK';
        }
    }else {
        $add = 'CHECK';
    }
    $courseHfEnd = trim($item[17]);
    if($courseHfEnd != 'N/A' && $courseHfEnd != ''){
        if(count(explode("-",$courseHfEnd)) == 3){
            $courseHfEnd = DateTime::createFromFormat("j-M-y",$courseHfEnd)->format("d/m/Y");//j-M-y
        }else {
            $add = 'CHECK';
        }


    }else {
        $add = 'CHECK';
    }


    $courseTT = trim($item[18]);
    $courseTTCert = trim($item[19]);
    $courseTTCountry = trim($item[20]);
    $courseTTStart = trim($item[21]);
    if($courseTTStart != 'N/A' && $courseTTStart != ''){
        if(count(explode("-",$courseTTStart)) == 3){
            $courseTTStart = DateTime::createFromFormat("j-M-y",$courseTTStart)->format("d/m/Y");//j-M-y
        }else {
            $add = 'CHECK';
        }

    }else {
        $add = 'CHECK';
    }
    $courseTTEnd = trim($item[22]);
    if($courseTTEnd != 'N/A' && $courseTTEnd != ''){
        if(count(explode("-",$courseTTEnd)) == 3){
            $courseTTEnd = DateTime::createFromFormat("j-M-y",$courseTTEnd)->format("d/m/Y");//j-M-y
        }else {
            $add = 'CHECK';
        }

    }else {
        $add = 'CHECK';
    }
    $crs = trim($item[23]);
    $crsIssue = trim($item[24]);////d/m/Y
    if($crsIssue != ''){
        $crsIssue = DateTime::createFromFormat("m/d/y",$crsIssue)->format("d/m/Y");
    }

    $crsExpire = $crsIssue;//m/d/y
    $crsFunction = trim($item[26]);

    $docwise = trim($item[27]);
    $docwiseStart = trim($item[28]);//+5 years
    $docwiseEnd = '';
    $test = explode("/", $docwiseStart);
    if(count($test) == 3){
        if(strlen($test[0]) == 4){
            $docwiseStart = DateTime::createFromFormat("Y/m/d",$docwiseStart)->format("d/m/Y");
            $docwiseEnd = DateTime::createFromFormat("Y/m/d",trim($item[28]))->add(new DateInterval("P5Y"))->format("d/m/Y");
        }else {
            $docwiseEnd = DateTime::createFromFormat("d/m/Y",$docwiseStart)->add(new DateInterval("P5Y"))->format("d/m/Y");
        }

        $year = (int)$test[2];
        $month = (int)$test[1];

        if($year >= 2014 && $month >= 10){
            $add = 'CHECK - DocWise';
        }

    }else {
        $add = 'CHECK';
    }


    $dob = trim($item[30]);//d/m/Y
    $joint = trim($item[31]);//d/m/Y
    $location = trim($item[32]);
    $dept = trim($item[33]);
    $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
    if($customer->getId()) {
        $cus = Mage::getModel('customer/customer')->load($customer->getId());
        $departmentId = $cus->getGroupId();
        $dob = $cus->getDob();
        $dob = Mage::getModel('core/date')->date("d/m/Y", $dob);
        $joint = Mage::getModel('core/date')->date("d/m/Y", $cus->getJointdate());

        $group = Mage::getModel('customer/group')->load($departmentId);
        $dept = $group->getCustomerGroupName();
    }






    $checkbox = array();
    if($ti != ''){
        $checkbox['ti'] = 1;
    }
    if($pi != ''){
        $checkbox['pi'] = 1;
    }
    if($te != ''){
        $checkbox['te'] = 1;
    }
    if($pe != ''){
        $checkbox['pe'] = 1;
    }


    if($cert == 'N/A'){
        $cert = 'No number';
    }
    $related = array();
    $related[] = array(
        'course' => '1.a '.$course,
        'cert'  => $cert,
        'country'   => Mage::helper('bs_traininglist')->convertToUnsign($country),
        'start'     => $start,
        'end'       => $end
    );
    $related[] = array(
        'course' => '1.b Being trained in MTOE',
        'cert'  => 'N/A',
        'country'   => 'N/A',
        'start'     => 'N/A',
        'end'       => 'N/A'
    );

    $related[] = array(
        'course' => '1.c '.$courseHf,
        'cert'  => $courseHfCert,
        'country'   => $courseHfCountry,
        'start'     => $courseHfStart,
        'end'       => $courseHfEnd
    );
    $related[] = array(
        'course' => '1.d '.$courseTT,
        'cert'  => $courseTTCert,
        'country'   => $courseTTCountry,
        'start'     => $courseTTStart,
        'end'       => $courseTTEnd
    );

    //$desc$	$certdoc$	$issue$	$expire$	$remark$
    $docs = array();
    $docs[] = array(
        'desc' =>'Holding a valid Docwise certificate',
        'certdoc'   => $docwise,
        'issue' => $docwiseStart,
        'expire'    => $docwiseStart,
        'remark'    => $docwiseEnd
    );

    if(strpos("moke".strtolower($type), "type training") && $crs == ''){
        $add = 'CHECK-CRS';
    }

    if($crs != ''){
        $docs[] = array(
            'desc' =>'QA-Insp & RTS Authorization',
            'certdoc'   => $crs,
            'issue' => $crsIssue,
            'expire'    => $crsIssue,
            'remark'    => $crsFunction
        );
    }

    $tableData = array($related, $docs);


    $templateData = array(
        'name'  => $name,
        'id'    => $id,
        'dob'   => $dob,
        'dept'  => $dept,
        'joint_date'  => $joint,
        'location'       => $location,
        'title'  => $type,
        'function'=>$function,

    );


    $res = Mage::helper('bs_traininglist/docx')->generateDocx($i.'.'.Mage::helper('bs_traininglist')->convertToUnsign($name).'-'.$type.'-'.$add, $template, $templateData, $tableData, $checkbox);

    if($res){
        echo "Done". $name;
    }


    $i++;


}



function getList($fileName = 'all.txt'){
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
            $result[] = $array;
        }
        $i++;
    }

    $danhsach = null;

    return $result;
}
?>
