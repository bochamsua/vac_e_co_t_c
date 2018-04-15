<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Traininglist default helper
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Bui Phong
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    public function getCurriculumContent($curriculumId){
        $collection = Mage::getResourceModel('bs_curriculumdoc/curriculumdoc_collection')->addFieldToFilter('status', 1);
        $constraint = 'related.curriculum_id='.$curriculumId;
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_curriculumdoc/curriculumdoc_curriculum')),
            'related.curriculumdoc_id=main_table.entity_id AND '.$constraint
        );

        $templateDir = Mage::getBaseDir('media').DS.'templates'.DS;

        $file = '';
        foreach ($collection as $cdoc) {


            $docType = Mage::getModel('bs_curriculumdoc/curriculumdoc_attribute_source_cdoctype')->getOptionFormatted($cdoc->getCdocType());

            $path = Mage::helper('bs_curriculumdoc/curriculumdoc')->getFileBaseDir().DS;
            $path .= $docType;

            if($docType == 'content'){
                $file = $path.DS.$cdoc->getCdocFile();
                break;
            }

        }
        return $file;
    }

    public function getFormattedText($value){

        $text = $value;
        $text = preg_replace('/[^a-z0-9A-Z_\\-\\.]+/i', '_', $text);

        return $text;
    }

    public function countHours($startDate, $finishDate, $startTime, $finishTime){


        $date1 = new DateTime($startDate);
        $date2 = new DateTime($finishDate);
        $interval = $date1->diff($date2);

        $days = $interval->d;
        $days += 1;

        $hours = $days * 8;

        if($startTime == '' && $finishTime == ''){

        }

    }

    public function convertToUnsign($cs, $tolower = false, $toupper = false)
    {
        $vietnamese = array("à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă",
            "ằ", "ắ", "ặ", "ẳ", "ẵ", "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề",
            "ế", "ệ", "ể", "ễ",
            "ì", "í", "ị", "ỉ", "ĩ",
            "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ",
            "ờ", "ớ", "ợ", "ở", "ỡ",
            "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
            "ỳ", "ý", "ỵ", "ỷ", "ỹ",
            "đ",
            "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă",
            "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
            "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
            "Ì", "Í", "Ị", "Ỉ", "Ĩ",
            "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
            "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
            "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
            "Đ");

        $vietnameseUnsign = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a",
            "a", "a", "a", "a", "a", "a",
            "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
            "i", "i", "i", "i", "i",
            "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o",
            "o", "o", "o", "o", "o",
            "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
            "y", "y", "y", "y", "y",
            "d",
            "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A",
            "A", "A", "A", "A", "A",
            "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",
            "I", "I", "I", "I", "I",
            "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O",
            "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",
            "Y", "Y", "Y", "Y", "Y",
            "D");

        if ($tolower) {
            return strtolower(str_replace($vietnamese, $vietnameseUnsign, $cs));
        }elseif($toupper){
            return strtoupper(str_replace($vietnamese, $vietnameseUnsign, $cs));
        }

        return str_replace($vietnamese, $vietnameseUnsign, $cs);

    }

    public function generateRandomString($length = 3) {
        return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public function getCertNo($courseId, $traineeId){
        $cert = Mage::getModel('bs_traineecert/traineecert')->getCollection()->addFieldToFilter('course_id', $courseId)->addFieldToFilter('trainee_id', $traineeId)->getFirstItem();
        if($cert->getId()){
            return $cert->getCertNo();
        }

        return false;
    }

    public function generateCert($course)
    {
        try {

            //check existing certs
            $certs = Mage::getModel('bs_traineecert/traineecert')->getCollection()->addFieldToFilter('course_id', $course->getId());
            if($certs->count()){
                return 'existed';
            }
            $curriculums = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addProductFilter($course->getId());

            $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculums->getFirstItem()->getId());

            $certComplete = $curriculum->getCCertCompletion();
            $certRecognize = $curriculum->getCCertRecognition();
            $certAttendance = $curriculum->getCCertAttendance();

            if(!$certComplete && !$certRecognize && !$certAttendance){
                return 'nocert';
            }

            $isOnline = $curriculum->getCOnline();

            $totalHours = 0;

            //get total hours by schedule info, is this an accurate way?
            $schedules = Mage::getModel('bs_register/schedule')->getCollection()->addFieldToFilter('course_id', $course->getId());
            if (count($schedules)) {
                foreach ($schedules as $sch) {
                    $totalHours += (float)$sch->getScheduleHours();
                }

            }

            $subjects = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $curriculum->getId())->addFieldToFilter('status', 1);

            $totalTrainingSubjects = $subjects->count();

            $sujectExams = Mage::getModel('bs_subject/subject')->getCollection()->addFieldToFilter('curriculum_id', $curriculum->getId())->addFieldToFilter('status', 1)->addFieldToFilter('require_exam', 1);//->addFieldToFilter('subject_exam', 0)
            $totalExamSubjects = $sujectExams->count();



            $startDate = $course->getCourseStartDate();
            $startDate = Mage::getModel('core/date')->date("d/m/Y", $startDate);

            $finishDate = $course->getCourseFinishDate();
            $checkDate = $finishDate;
            $finishDate = Mage::getModel('core/date')->date("d/m/Y", $finishDate);

            if ($finishDate != $startDate) {
                $duration = 'From ' . $startDate . ' to ' . $finishDate;
            } else {
                $duration = $startDate;
            }







            $totalHours = $totalHours . ' hrs';
            if ($isOnline) {
                $totalHours = 'Online';
                $location = 'VAECO WEB';
            }

            if ($totalTrainingSubjects < 10) {
                $totalTrainingSubjects = '0' . $totalTrainingSubjects;
            }
            if ($totalExamSubjects < 10) {
                $totalExamSubjects = '0' . $totalExamSubjects;
            }
            if ($course->getAttributeText('location') != '') {
                $location = $course->getAttributeText('location');
            }



            $sku = $course->getSku();
            $check = explode("-",$sku);
            $ge = false;
            $recurrent = false;
            if($check[0] == 'GE'){
                $ge = true;
                if($check[count($check)-1] == 'R'){
                    $recurrent = true;
                }
            }

            $reportNo = '';
            $reportDispatchNo = trim($course->getReportDispatchNo());
            $reportDispatchSuf = $course->getAttributeText('report_dispatch_suffix');
            $reportDispatchDate = $course->getReportDispatchDate();
            $day = Mage::getModel('core/date')->date("d", $reportDispatchDate);
            $month = Mage::getModel('core/date')->date("m", $reportDispatchDate);
            $year = Mage::getModel('core/date')->date("Y", $reportDispatchDate);


            $trainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($course)->setOrder('position', 'ASC');

            $traineeData = null;
            $tableData = null;
            if (count($trainees)) {
                $data['total_trainees'] = count($trainees);
                $traineeData = array();
                $i = 1;
                foreach ($trainees as $_trainee) {

                    $trainee = Mage::getModel('bs_trainee/trainee')->load($_trainee->getId());


                    $pob = trim($trainee->getTraineePob());

                    $validDriver = false;
                    $driverStatus = $trainee->getDriverLicense();
                    $driverExpire = $trainee->getDriverLicenseExpire();
                    if($driverStatus && $driverExpire > $checkDate){
                        $validDriver = true;
                    }

                    $attendanceStatus = '';
                    $examPass = 0;
                    $examFail = 0;
                    $disciplineStatus = 'No';
                    $courseResult = '';
                    $certNo = '';
                    $remark = '';


                    //get Attendance status
                    $attendances = Mage::getModel('bs_register/attendance')->getCollection()->addFieldToFilter('trainee_id', $_trainee->getId())->addFieldToFilter('course_id', $course->getId());
                    $attendExcuse = 0;
                    $attendNotExcuse = 0;
                    if ($attendances->count()) {
                        foreach ($attendances as $att) {
                            $attStartDate = $att->getAttStartDate();
                            $attFinishDate = $att->getAttFinishDate();

                            $datetime1 = new DateTime($attStartDate);
                            $datetime2 = new DateTime($attFinishDate);
                            $interval = $datetime1->diff($datetime2);

                            $days = $interval->days;
                            $days += 1;

                            $hours = $days * 8;

                            $attStartTime = $att->getAttStartTime();
                            if ($attStartTime == 2) {
                                $hours -= 4;
                            }
                            $attFinishTime = $att->getAttFinishTime();
                            if ($attFinishTime == 1) {
                                $hours -= 4;
                            }
                            if ($att->getAttExcuse()) {
                                $attendExcuse += $hours;
                            } else {
                                $attendNotExcuse += $hours;
                            }
                        }

                    }

                    $totalOffHours = $attendExcuse + $attendNotExcuse;
                    $totalOffHoursDiff = $totalHours - $totalOffHours;
                    if($totalOffHoursDiff < 0){
                        $totalOffHoursDiff = 0;
                    }
                    $attendanceStatus = $totalOffHoursDiff . '/' . $totalHours;

                    //get exam result
                    $examresults = Mage::getModel('bs_exam/examresult')->getCollection()->addFieldToFilter('course_id', $course->getId())->addFieldToFilter('trainee_id', $trainee->getId());

                    $totalMark = 0;
                    $grade = '';
                    if ($examresults->count()) {
                        foreach ($examresults as $exre) {
                            $firstMark = (float)$exre->getFirstMark();
                            $secondMark = (float)$exre->getSecondMark();
                            $thirdMark = (float)$exre->getThirdMark();

                            $finalMark = max($firstMark, $secondMark, $thirdMark);
                            $totalMark += $finalMark;
                            if ($finalMark >= 75) {
                                $examPass += 1;
                            } else {
                                $examFail += 1;
                            }


                        }

                        $everage = $totalMark / $examresults->count();
                        if($everage >= 90){
                            $grade = 'GIỎI';
                        }elseif($everage >= 80){
                            $grade = 'KHÁ';
                        }elseif($everage >= 75 && $everage < 80){
                            $grade = 'TRUNG BÌNH';
                        }

                    }

                    //Certificate stuffs

                    $j = $i;
                    if ($j < 10) {
                        $j = '0' . $j;
                    }
                    if ($certAttendance) {
                        $certNo = $course->getSku() . '/A-' . $j;
                    }
                    if ($certRecognize) {
                        $certNo = $course->getSku() . '/R-' . $j;
                    }
                    if ($certComplete) {
                        $certNo = $course->getSku() . '/C-' . $j;
                    }

                    if ($totalOffHours < 0 || ($totalOffHours / $totalHours) > 0.9 || $examFail > 0) {

                        $certNo = '';
                    }

                    $i++;


                    if(!$certNo){
                        $certNo = 'N/A';
                    }

                    $cert = Mage::getModel('bs_traineecert/traineecert')->setCourseId($course->getId())->setTraineeId($_trainee->getId())->setCertNo($certNo)->setIssueDate(now())->save();


                }

                return 'ok';



            }
            return 'trainee';

        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        return 'other';
    }

    public function getCurriculumByCategory($catId){
        $resource = Mage::getSingleton('core/resource');
        //$writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');

        $tableRelation = $resource->getTableName('bs_traininglist/curriculum_category');

        $res = $readConnection->fetchCol("SELECT curriculum_id FROM {$tableRelation} WHERE category_id = {$catId} ORDER BY position ASC");

        if($res){
            return $res;
        }
        return false;

    }

    public function getInstructorByCategory($catId){
        $resource = Mage::getSingleton('core/resource');
        //$writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');

        $tableCurr = $resource->getTableName('bs_traininglist/curriculum');
        $tableCurrCat = $resource->getTableName('bs_traininglist/curriculum_category');
        $tableInsCurr = $resource->getTableName('bs_instructor/instructor_curriculum');

        $sql = "SELECT DISTINCT curriculum_id FROM {$tableCurrCat} WHERE category_id = {$catId}";
        $sql = "SELECT DISTINCT instructor_id FROM {$tableInsCurr} WHERE curriculum_id IN ({$sql})";


        $tableIns = $resource->getTableName('bs_instructor_instructor_varchar');
        $tableFunc = $resource->getTableName('bs_instructor/instructorfunction');

        $res = $readConnection->fetchCol($sql);

        if($res){
            return $res;
        }
        return false;

    }

    public function getInstructorByCategoryV2($catId){
        $resource = Mage::getSingleton('core/resource');
        //$writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');

        $tableIns = $resource->getTableName('bs_instructor_instructor_varchar');
        $tableFunc = $resource->getTableName('bs_instructor/instructorfunction');

        $res = $readConnection->fetchCol("SELECT entity_id FROM {$tableIns} WHERE entity_id IN (SELECT instructor_id FROM {$tableFunc} WHERE category_id = {$catId}) AND attribute_id = 272 ORDER BY `value` ASC");

        if($res){
            return $res;
        }
        return false;

    }

    public function getTaskInstructorByCategory($catId){
        $resource = Mage::getSingleton('core/resource');
        //$writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');

        $tableIns = $resource->getTableName('bs_tasktraining/taskinstructor');
        $tableFunc = $resource->getTableName('bs_tasktraining/taskfunction');

        $res = $readConnection->fetchCol("SELECT entity_id FROM {$tableIns} WHERE entity_id IN (SELECT instructor_id FROM {$tableFunc} WHERE category_id = {$catId}) ORDER BY vaeco_id ASC");

        if($res){
            return $res;
        }
        return false;

    }

    public function checkCurriculum($code, $curId = null){
        $curriculum = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addAttributeToFilter('c_code', $code);
        if($curId){
            $curriculum->addAttributeToFilter('entity_id', array('neq'=>$curId));
        }
        $curr = $curriculum->getFirstItem();
        if($curr->getId()){
            return true;
        }
        return false;
    }

    public function getShortcode($title){
        $title = "moke".$title;
        $title = strtolower($title);

        $code = '';

        if(strpos($title, "english")){
            $code = 'TAE';
        }

        for($i=20; $i>1; $i--){
            if(strpos($title, "module ".$i)){
                if(strpos($title, "airframe")){
                    $code = 'M'.$i.'-A'; break;
                }elseif(strpos($title, "electrical")){
                    $code = 'M'.$i.'-E'; break;
                }elseif(strpos($title, "avionic")){
                    $code = 'M'.$i.'-AV'; break;
                }elseif(strpos($title, "- systems part")){
                    $code = 'M'.$i.'-S'; break;
                }else {
                    $code = 'M'.$i; break;
                }

            }
        }

        for($i=80; $i>=0; $i--){
            if($i< 10){
                $i = '0'.$i;
            }
            if(strpos($title, "ata ".$i)){
                $code = 'ATA'.$i; break;

            }elseif(strpos($title, "document ".$i)){
                $code = 'DOC'; break;
            }
        }

        if($code != ''){
            return $code;
        }

        return '';
    }

    public function lowercase($str, $ucFirst = false) {
        $lower = 'a|b|c|d|e|f|g|h|i|j|k|l|m|n|o|p|q|r|s|t|u|v|w|x|y|z|á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|đ|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|í|ì|ỉ|ĩ|ị|ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|ý|ỳ|ỷ|ỹ|ỵ';
        $upper = 'A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ|Đ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ|Í|Ì|Ỉ|Ĩ|Ị|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự|Ý|Ỳ|Ỷ|Ỹ|Ỵ';
        $arrayUpper = explode('|',$upper);
        $arrayLower = explode('|',$lower);
        $str = str_replace($arrayUpper,$arrayLower,$str);
        if($ucFirst){
            $str = ucfirst($str);
        }
        return $str;
    }

    public function uppercase($str) {
        $lower = 'a|b|c|d|e|f|g|h|i|j|k|l|m|n|o|p|q|r|s|t|u|v|w|x|y|z|á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|đ|é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|í|ì|ỉ|ĩ|ị|ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|ý|ỳ|ỷ|ỹ|ỵ';
        $upper = 'A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ|Đ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ|Í|Ì|Ỉ|Ĩ|Ị|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự|Ý|Ỳ|Ỷ|Ỹ|Ỵ';
        $arrayUpper = explode('|',$upper);
        $arrayLower = explode('|',$lower);
        return str_replace($arrayLower,$arrayUpper,$str);

    }

    public function toUpperCase($str){
        return $this->uppercase($str);
    }
    public function toLowerCase($str){
        return $this->lowercase($str);
    }
}
