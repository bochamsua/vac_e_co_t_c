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
class BS_Traininglist_Helper_Course extends Mage_Core_Helper_Abstract
{

    public function getCourseGeneralInfo($course){

        $name = $course->getName();
        $code = $course->getSku();
        $deptInfo = 'Department';

        $dI = $course->getDeptInfo();
        if ($dI == 334) {
            $deptInfo = 'Company';
        } elseif ($dI == 335) {
            $deptInfo = 'Place of Birth';
        }


        $location = 'TC-VAECO';
        $conductingPlace = $course->getAttributeText('conducting_place');
        if (trim($conductingPlace) == 'HCM') {
            $location .= ' (STD)';
        } elseif (trim($conductingPlace) == 'DAD') {
            $location = 'DAD Branch';
        }


        if ($course->getAttributeText('location') != '') {
            $location = $course->getAttributeText('location');
        }

        $startDate = $course->getCourseStartDate();
        $startDate = Mage::getModel('core/date')->date("d/m/Y", $startDate);

        $finishDate = $course->getCourseFinishDate();
        $finishDate = Mage::getModel('core/date')->date("d/m/Y", $finishDate);

        if ($finishDate != $startDate) {
            $duration = 'From ' . $startDate . ' to ' . $finishDate;
        } else {
            $duration = $startDate;
        }

        $data = array(
            'title' => $name,
            'code' => $code,
            'duration' => $duration,
            'location' => $location,
            'dept_info' => $deptInfo

        );

        return $data;
    }

    public function getCourseTraineeInfo($course, $traineeIds = array()){

        $trainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($course);
        $trainees->getSelect()->order('position');

        if (count($traineeIds)) {
            $trainees->addAttributeToFilter('entity_id', array('in' => $traineeIds));
        }

        $dI = $course->getDeptInfo();

        $tableData = null;
        $traineeData = null;
        if (count($trainees)) {
            $traineeData = array();
            foreach ($trainees as $_trainee) {

                $trainee = Mage::getModel('bs_trainee/trainee')->load($_trainee->getId());
                $name = $trainee->getTraineeName();
                $code = $trainee->getTraineeCode();
                $dept = $trainee->getTraineeDept();

                if ($dI == 334) {
                    $dept = $trainee->getTraineeCompany();
                } elseif ($dI == 335) {
                    $dept = $trainee->getTraineePob();
                }

                if ($dept == '') {
                    $dept = 'TC';
                }

                $dob = '';
                $function = '';
                $basic = '';
                $vaecoId = $trainee->getVaecoId();
                if ($trainee->getTraineeDob() != '') {
                    $dob = Mage::getModel('core/date')->date("d/m/Y", $trainee->getTraineeDob());
                }

                if ($vaecoId != '') {
                    $code = $vaecoId;
                    $staff = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
                    if ($staff->getId()) {

                        $customer = Mage::getModel('customer/customer')->load($staff->getId());
                        if ($customer->getDob() != '') {
                            $dob = Mage::getModel('core/date')->date("d/m/Y", $customer->getDob());
                        }

                        $departmentId = $customer->getGroupId();
                        $department = Mage::getModel('customer/group')->load($departmentId);


                        if ($dI == 335) {
                            if($customer->getPob() != ''){
                                $dept = $customer->getPob();
                            }

                        }else {
                            if ($department) {
                                $dept = $department->getCode();
                            }
                        }

                        $customerFunc = $customer->getStaffFunction(); // 3 - A, 4 - M
                        if($customerFunc == 3){
                            $function = 'A';
                        }elseif ($customerFunc == 4){
                            $function = 'M';
                        }

                        $customerBasic = $customer->getStaffBasic(); // 5 - T, 6 - E
                        if($customerBasic == 5){
                            $basic = 'T';
                        }elseif ($customerBasic == 6){
                            $basic = 'E';
                        }
                    }
                }

                $docwise = 'N';
                $valid = Mage::helper('bs_trainee')->checkDocwise($vaecoId, $course->getCourseStartDate());
                if($valid) {
                    $docwise = 'Y';
                }

                $dept = str_replace("\"", "", $dept);
                $traineeData[] = array(
                    'name' => $name,
                    'id' => $code,
                    'department' => $dept,
                    'dob' => $dob,
                    'function' => $function,
                    'basic' => $basic,
                    'docwise'   => $docwise
                );


            }



        }else {
            $traineeData = array();

            $traineeData[] = array(
                'name' => 'Please import',
                'id' => 'trainee!',
                'department' => 'into',
                'dob' => 'this',
                'function' => 'course',
                'basic' => '-',
                'docwise'   => 'thanks!'
            );
        }

        $tableData = array($traineeData);

        return $tableData;

    }
}
