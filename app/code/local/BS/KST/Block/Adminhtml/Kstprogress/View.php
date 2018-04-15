<?php

class BS_KST_Block_Adminhtml_Kstprogress_View extends Mage_Adminhtml_Block_Widget_View_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->removeButton('edit');
        $this->updateButton('back', 'onclick', 'window.location.href=\'' . $this->getUrl('*/') . '\'');


    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getReport(){
        $currentUser = Mage::getSingleton('admin/session')->getUser();
        $userId = $currentUser->getId();
        $userName = $currentUser->getUsername();
        $currentDate = date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));

        $groupId = 0;
        $member = Mage::getModel('bs_kst/kstmember')->getCollection()->addFieldToFilter('username', $userName)->getFirstItem();
        if($member->getId()){
            $groupId = $member->getKstgroupId();
        }


        $resource = Mage::getSingleton('core/resource');
        //$writeConnection = $resource->getConnection('core_write');
        $readConnection = $resource->getConnection('core_read');

        $tableProgress = $resource->getTableName('bs_kst/kstprogress');

        $sql = "SELECT DISTINCT course_id FROM {$tableProgress}";

        //$pUsername = $readConnection->fetchCol("SELECT DISTINCT username FROM {$tableProgress} WHERE username = '{$userName}'");

        if($groupId > 0){//we found that this is trainee username, process permission



            $sql .= " WHERE group_id = {$groupId}";

        }

        $pCourseIds = $readConnection->fetchCol($sql);


        $courses = Mage::getResourceModel('catalog/product_collection');//->addAttributeToFilter('sku',array(array('like'=>'%KST%'), array('like'=>'%CRS%')))
        $courses
            //->addAttributeToFilter('course_start_date',array('to' => $currentDate))
           //->addAttributeToFilter('course_finish_date',array('from' => $currentDate))
                ->addAttributeToFilter('entity_id', array('in'=>$pCourseIds))
            ->addAttributeToFilter('status',1)
        ;




        if($courses->count()){


            $result = array();
            $i=0;
            foreach ($courses as $course) {
                $course = Mage::getModel('catalog/product')->load($course->getId());

                $myTimezone = new DateTimeZone('Asia/Ho_Chi_Minh');
                $now = new DateTime('now', $myTimezone);

                $nowValue = strtotime('now');
                $startValue = strtotime($course->getCourseStartDate());
                $finishValue = strtotime($course->getCourseFinishDate());

                $startDate = Mage::getModel('core/date')->date("d/m/Y", $course->getCourseStartDate());
                $finishDate = Mage::getModel('core/date')->date("d/m/Y", $course->getCourseFinishDate());

                if ($finishDate != $startDate) {
                    $duration = 'From ' . $startDate . ' to ' . $finishDate;
                } else {
                    $duration = $startDate;
                }

                $totalValue = $finishValue - $startValue;

                $days = floor($totalValue / 86400);


                $dayPassed = floor(($nowValue - $startValue)/ 86400);
                $prog = 'Behind Schedule';

                $courseId = $course->getId();

                //get all usernames

                $groupIds = $readConnection->fetchCol("SELECT DISTINCT group_id FROM {$tableProgress} WHERE course_id = {$courseId}");
                if(count($groupIds)){
                    foreach ($groupIds as $gId) {

                        //get all subjects first
                        $subjectIds = $readConnection->fetchCol("SELECT DISTINCT kstsubject_id FROM {$tableProgress} WHERE course_id = {$courseId} AND group_id = {$gId} ORDER BY subject_position ASC");

                        if(count($subjectIds) && $subjectIds[0] != null){
                            $totalItems = 0;
                            $totalComplete = 0;


                            $subjects = array();
                            $feedback = array();

                            foreach ($subjectIds as $subjectId) {
                                $progress = Mage::getModel('bs_kst/kstprogress')->getCollection()->addFieldToFilter('course_id', $course->getId())->addFieldToFilter('kstsubject_id', $subjectId)->addFieldToFilter('group_id', $gId)->setOrder('item_position', 'ASC');

                                $subjectName = Mage::getSingleton('bs_kst/kstsubject')->load($subjectId)->getName();
                                if($progress->count()){

                                    $total = $progress->count();
                                    $totalItems += $total;

                                    $complete = Mage::getModel('bs_kst/kstprogress')->getCollection()->addFieldToFilter('course_id', $course->getId())->addFieldToFilter('kstsubject_id', $subjectId)->addFieldToFilter('group_id', $gId)->addFieldToFilter('status',1);

                                    $feedbackItems = array();
                                    if($complete->count()){
                                        foreach ($complete as $item) {
                                            if($item->getTraineeFeedback() != ''){
                                                $feedbackItems[] = $item->getPosition();
                                            }
                                        }
                                    }

                                    $complete = $complete->count();
                                    $totalComplete += $complete;

                                    $percent = ceil($complete * 100 / $total);

                                    if(count($feedbackItems)){
                                        $feedback[] = array(
                                            'subject' => $subjectName,
                                            'feedback'  => $feedbackItems
                                        );
                                    }


                                    $subjects[] = array(
                                        'name'   => $subjectName,
                                        'percent'   => $percent
                                    );

                                }
                            }
                            $totalPercent = ceil($totalComplete * 100 / $totalItems);

                            $expectedTaskPerDay = round($totalItems / $days, 2);

                            $expectedTasks = round($dayPassed * $totalItems / $days);
                            if($totalComplete - $expectedTasks <= -5 ){
                                $prog = 'Behind Schedule';
                            }elseif($totalComplete - $expectedTasks > 5) {
                                $prog = 'Ahead of Schedule';
                            }else {
                                $prog = 'On Schedule';
                            }

                            if($expectedTasks > $totalItems){
                                $expectedTasks = $totalItems;
                            }

                            $required = $expectedTasks - $totalComplete;

                            $result[$courseId][$gId] = array(
                                'id'    => $courseId,
                                'sku'   => $course->getSku(),
                                'duration'  => $duration,
                                'subjects'   => $subjects,
                                'total' => $totalPercent,
                                'task'  => $totalItems,
                                'complete'  => $totalComplete,
                                'prog'  => $prog,
                                'expectedperday'  => $expectedTaskPerDay,
                                'expected'  => $expectedTasks,
                                'days'  => $days,
                                'pass'  => $dayPassed,
                                'required'  => $expectedTasks - $totalComplete,
                                'feedback'  => $feedback
                            );

                        }else {

                            $progress = Mage::getModel('bs_kst/kstprogress')->getCollection()->addFieldToFilter('course_id', $course->getId())->addFieldToFilter('group_id', $gId)->setOrder('item_position', 'ASC');

                            $feedback = array();
                            if($progress->count()){

                                $total = $progress->count();

                                $complete = Mage::getModel('bs_kst/kstprogress')->getCollection()->addFieldToFilter('course_id', $course->getId())->addFieldToFilter('group_id', $gId)->addFieldToFilter('status',1);

                                $feedbackItems = array();
                                if($complete->count()){
                                    foreach ($complete as $item) {
                                        if($item->getTraineeFeedback() != ''){
                                            $feedback[] = $item->getPosition();
                                        }
                                    }
                                }

                                $complete = $complete->count();

                                $percent = ceil($complete * 100 / $total);

                                $expectedTaskPerDay = round($total / $days, 2);

                                $expectedTasks = round($dayPassed * $total / $days);

                                if($complete - $expectedTasks <= -5 ){
                                    $prog = 'Behind Schedule';
                                }elseif($complete - $expectedTasks > 5) {
                                    $prog = 'Ahead of Schedule';
                                }else {
                                    $prog = 'On Schedule';
                                }

                                if($expectedTasks > $total){
                                    $expectedTasks = $total;
                                }



                                $result[] = array(
                                    'group_id'  => $gId,
                                    'result'    => array(
                                        'id'    => $courseId,
                                        'sku'   => $course->getSku(),
                                        'duration'  => $duration,
                                        'percent'   => $percent,
                                        'task'  => $total,
                                        'complete'  => $complete,
                                        'prog'  => $prog,
                                        'expectedperday'  => $expectedTaskPerDay,
                                        'expected'  => $expectedTasks,
                                        'days'  => $days,
                                        'pass'  => $dayPassed,
                                        'required'  => $expectedTasks - $complete,
                                        'feedback'  => $feedback


                                    )
                                );



                            }
                        }
                    }
                }



            }

            return $result;
        }

        return false;
    }

    public function getLeader($groupId){
        $leader = Mage::getModel('bs_kst/kstmember')->getCollection()->addFieldToFilter('kstgroup_id', $groupId)->addFieldToFilter('is_leader', 1)->getFirstItem();
        if($leader->getId()){
            return $leader->getName();
        }
        return false;
    }

    public function getMembers($groupId){

        $m = Mage::getModel('bs_kst/kstmember')->getCollection()->addFieldToFilter('kstgroup_id', $groupId);
        if($m->count()){
            $result = array();
            foreach ($m as $item) {
                $result[] = $item->getName().' ('.$item->getVaecoId().')';
            }

            return $result;
        }

        return false;

    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getHeaderText()
    {

        return Mage::helper('bs_kst')->__('KST Courses Report');

    }
}
