<?php

require_once '../app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$rows = getList();

$type = 210;//course_type
$hrdistype = 400;//hr_dis_type
$hrdisDate = '2015-12-28';//hr_dispatch_date
$conductingPlace = 0;//conducting_place
$year = 243;//course_plan_year

/*
<option value="209">HAN</option>
<option value="208">HCM</option>
<option value="207">DAD</option>

 */

$data = array (
    'store_id' => 0,
    'attribute_set_id' => 4,
    'type_id' => 'simple',
    'course_type' => '210',
    'hr_dis_type'   => '400',
    'hr_dispatch_date' => '28/12/2015',
    'course_plan_type' => '201',
    'course_plan_year' => '243',
    'other_dispatch_no' => '',
    'other_dispatch_date' => '',
    'other_dispatch_dept' => '',
    'hr_note' => '',
    'onhold' => '0',
    'hidden_from' => '',
    'created_by' => '',
    'course_name_vi' => '',
    'course_status' => '',
    'plan_dispatch_no' => '',
    'plan_dispatch_suffix' => '',
    'plan_dispatch_date' => '',
    'course_start_date' => '',
    'course_finish_date' => '',
    'conducting_type' => '',
    'training_day' => '',
    'training_time' => '',
    'hr_decision_status' => '',
    'responsibility_division' => '',
    'course_note' => '',
    'dept_info' => '',
    'bypass_docwise' => '0',
    'status' => '0',
    'price' => '',
    'other_price' => '',
    'hr_decision_no' => '',
    'hr_decision_suffix' => '',
    'hr_decision_date' => '',
    'number_trainees' => '',
    'hr_decision_no2' => '',
    'hr_decision_suffix2' => '',
    'hr_decision_date2' => '',
    'number_trainees2' => '',
    'hr_decision_no3' => '',
    'hr_decision_suffix3' => '',
    'hr_decision_date3' => '',
    'number_trainees3' => '',
    'documents' => '0',
    'course_report' => '0',
    'report_dispatch_no' => '',
    'report_dispatch_suffix' => '',
    'report_dispatch_date' => '',
    'course_complete_trainees' => '',
    'course_incomplete_trainees' => '',
    'course_final_note' => '',
    'report_content' => '',
    'course_file_location' => '',
    'weight' => '10',
    'visibility' => '4',
    'url_key' => '',

);


foreach ($rows as $row) {

    //$row = explode("\t",$line);
    //$row = array_map('trim', $line);

    $name = str_replace(array(",","\"", "\r\n"),"",$row[0]);
    $trainee = trim($row[2]);
    $class = (int)$row[3];
    $han = (int)$row[4];
    $dad = (int)$row[5];
    $hcm = (int)$row[6];


    $money = str_replace(array(",","\"", "\r\n"),"",$row[7]);

    if($han > 0){
        for ($i=0; $i < $han; $i++){
            $course = Mage::getModel('catalog/product');

            $data['course_requested_name'] = $name;
            $data['conducting_place'] = 209;
            $data['expected_trainees_class'] = $trainee;
            $data['expected_classes'] = $class;
            $data['arrival_price'] = $money;
            $data['name'] = 'VIRTUAL NAME '.microtime();
            $data['sku']    = 'VIRTUAL CODE '.microtime();

            $course->addData($data);

            $course->save();
        }
    }
    if($dad > 0){
        for ($i=0; $i < $dad; $i++){
            $course = Mage::getModel('catalog/product');

            $data['course_requested_name'] = $name;
            $data['conducting_place'] = 207;
            $data['expected_trainees_class'] = $trainee;
            $data['expected_classes'] = $class;
            $data['arrival_price'] = $money;
            $data['name'] = 'VIRTUAL NAME '.microtime();
            $data['sku']    = 'VIRTUAL CODE '.microtime();

            $course->addData($data);

            $course->save();
        }
    }
    if($hcm > 0){
        for ($i=0; $i < $hcm; $i++){
            $course = Mage::getModel('catalog/product');

            $data['course_requested_name'] = $name;
            $data['conducting_place'] = 208;
            $data['expected_trainees_class'] = $trainee;
            $data['expected_classes'] = $class;
            $data['arrival_price'] = $money;
            $data['name'] = 'VIRTUAL NAME '.microtime();
            $data['sku']    = 'VIRTUAL CODE '.microtime();

            $course->addData($data);

            $course->save();
        }
    }


    echo "Done {$row[0]} <br> \n";


}


function getList($fileName = 'courses.txt'){
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

        $result[] = $array;
        $i++;
    }

    $danhsach = null;

    return $result;
}

?>
