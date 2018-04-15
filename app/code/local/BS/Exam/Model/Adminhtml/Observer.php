<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Adminhtml observer
 *
 * @category    BS
 * @package     BS_Exam
 * @author      Bui Phong
 */
class BS_Exam_Model_Adminhtml_Observer
{

    public function saveExamresultData($observer)
    {
        $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');

        $post = Mage::app()->getRequest()->getPost('mark', -1);
        if ($post != '-1') {
            $query = array();
            foreach ($post as $times => $value) {
                if($times == 1){
                    $times = 'first_mark';
                }else {
                    $times = 'second_mark';
                }
                foreach ($value as $productId => $marks) {
                    foreach ($marks as $traineeId => $mark) {
                        foreach ($mark as $subjectId => $score) {
                            $score = intval($score);

                            $query[] = "UPDATE bs_exam_examresult SET {$times} = {$score} WHERE trainee_id = {$traineeId} AND course_id = {$productId} AND subject_id = {$subjectId}";
                        }
                    }
                }
            }

            if(count($query)){
                $queries = implode(";\n", $query);
                $writeConnection->query($queries);
            }
        }
        return $this;
    }
}
