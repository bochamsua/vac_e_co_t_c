<?php
die;
require_once 'app/Mage.php';

umask(0);
Mage::app();

$storeId = '0';

Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$resource = Mage::getSingleton('core/resource');
$writeConnection = $resource->getConnection('core_write');
$readConnection = $resource->getConnection('core_read');


$curriculumCat    = $resource->getTableName('bs_traininglist/curriculum_category');


/*
 * <select class=" select" name="curriculum[c_aircraft]" id="c_aircraft">
<option selected="selected" value=""></option>
<option value="126">A320/321</option>
<option value="127">A330</option>
<option value="128">A350</option>
<option value="129">B777</option>
<option value="130">B787</option>
<option value="131">ATR72</option>
<option value="132">F70</option>
</select>
 */

/*
 * <select class=" select" name="curriculum[c_rev]" id="c_rev">
<option selected="selected" value=""></option>
<option value="149">00</option>
<option value="150">01</option>
<option value="151">02</option>
<option value="152">03</option>
<option value="153">04</option>
<option value="154">05</option>
<option value="155">06</option>
<option value="156">07</option>
<option value="157">08</option>
<option value="158">09</option>
<option value="159">10</option>
</select>
 */

/*
 * <select multiple="multiple" size="10" class=" select multiselect" name="curriculum[c_compliance_with][]" id="c_compliance_with">
<option value="197">MTOE</option>
<option value="198">AMOTP</option>
<option value="199">RSTP</option>
<option value="200">Others</option>
</select>
 */



function getOptionIdByText($attributeCode,$value)
{
    $allOptions = Mage::getModel('eav/config')->getAttribute('bs_traininglist_curriculum', $attributeCode)->getSource()->getAllOptions(false);

    foreach ($allOptions as $option) {
        if ($option['label'] == $value) {
            return $option['value'];
        }
    }
}

function getList($fileName = 'a320.txt'){
    $list = new SplFileObject($fileName);
    $result = array();
    $i=0;
    while (!$list->eof()) {
        $line = $list->fgets();
        $line = trim($line);

        $line = str_replace("  ", " ", $line);

        $array = explode("\t", $line);
        $result[] = array(
            'name'=>$array[0],
            'code'=>$array[2],
            'rev'=>$array[1]
        );
    $i++;
    }

    $list = null;

    return $result;
}

$fileName = 'danhsach.txt';


$lists = getList($fileName);

$count = count($lists);

if(count($count)){
    $i=1;
    foreach($lists as $c){
        $curriculum    = Mage::getModel('bs_traininglist/curriculum');

        $data = array('store_id'=>'0',
            'c_name'=>$c['name'],
            'c_code'=>$c['code'],
            'c_aircraft'=>'',
            'c_approved_date'=>'',
            'c_compliance_with'=>'',
            'c_description'=>'',
            'c_purpose'=>'146',
            'c_new_staff'=>'0',
            'c_mandatory'=>'0',
            'c_recurrent'=>'0',
            'job_specific'=>'',
            'c_frequency'=>'',
            'c_remedial'=>'0',
            'c_objectives'=>'',
            'c_classroom'=>'1',
            'c_seminar'=>'0',
            'c_self_study'=>'0',
            'c_case_study'=>'0',
            'c_embedded'=>'0',
            'c_tasktraining'=>'1',
            'c_duration'=>'',
            'c_capacity'=>'',
            'c_knowledge'=>'',
            'c_skill'=>'',
            'c_experience'=>'',
            'c_docwise'=>'0',
            'c_tool'=>'',
            'c_exam_type'=>'',
            'c_cert_completion'=>'0',
            'c_cert_recognition'=>'0',
            'c_rev'=>getOptionIdByText('c_rev',$c['rev']),
            'c_cert_attendance'=>'0',
            'status'=>'1',
            'attribute_set_id'=>'14',
        );

        $curriculum->addData($data);

        try {
            $curriculum->save();
            $curriculumId = $curriculum->getId();

            //$sql = "INSERT INTO {$curriculumCat} (curriculum_id, category_id, position) VALUES ({$curriculumId},{$catId}, {$i})";

            //$writeConnection->query($sql);

            echo "done $c[0] <br>";

            $i++;
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }
}


//echo "<pre>";
//print_r($curs);










