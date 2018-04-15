<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule admin controller
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Adminhtml_Register_ScheduleController extends BS_Register_Controller_Adminhtml_Register
{
    /**
     * init the course schedule
     *
     * @access protected
     * @return BS_Register_Model_Schedule
     */
    protected function _initSchedule()
    {
        $scheduleId  = (int) $this->getRequest()->getParam('id');
        $schedule    = Mage::getModel('bs_register/schedule');
        if ($scheduleId) {
            $schedule->load($scheduleId);
        }
        Mage::register('current_schedule', $schedule);
        return $schedule;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('bs_register')->__('Register'))
             ->_title(Mage::helper('bs_register')->__('Course Schedule'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    public function updateSubInsRoomsAction(){
        $result = array();
        $courseId = $this->getRequest()->getPost('course_id');
        $result['subjects'] = '<option value="">There is no subject found</option>';
        $result['instructors'] = '<option value="">There is no instructor assigned to this class</option>';
        $result['rooms'] = '<option value="">There is no room assigned to this class</option>';
        if($courseId){
            $curriculumId = '';


            $curriculums = Mage::getModel('bs_traininglist/curriculum')->getCollection();
            $curriculums->addProductFilter($courseId);

            if($cu = $curriculums->getFirstItem()){
                $curriculumId = $cu->getId();

                $subjects = Mage::getResourceModel('bs_subject/subject_collection')->addFieldToFilter('curriculum_id',$curriculumId)->setOrder('entity_id', 'ASC');
                if($subjects->count()){
                    $text = '<option value="0"></option>';
                    foreach ($subjects as $sub) {

                        $note = $sub->getSubjectNote();
                        if($note != ''){
                            $note = ' -- '.$note;
                        }
                        $text  .= '<option value="'.$sub->getId().'">'.$sub->getSubjectName().$note.' -- ('.$sub->getSubjectHour().' hours)'.'</option>';
                        //$text  .= '<option value="'.$sub->getId().'">'.$sub->getTraineeName().' - '.$add.'</option>';
                    }
                    $result['subjects'] = $text;
                }
            }




            $instructors = Mage::getResourceModel('bs_instructor/instructor_collection')
                ->addAttributeToSelect('*')->addProductFilter($courseId);
            if($instructors->count()){
                $text = '';
                foreach ($instructors as $ins) {

                    $text  .= '<option value="'.$ins->getId().'">'.$ins->getIname().' - '.$ins->getIvaecoid().'</option>';
                }
                $result['instructors'] = $text;
            }


            $rooms = Mage::getResourceModel('bs_logistics/classroom_collection')
                ->addFieldToSelect('*')->addProductFilter($courseId);
            if($rooms->count()){
                $text = '';
                foreach ($rooms as $room) {

                    $text  .= '<option value="'.$room->getId().'">'.$room->getClassroomCode().'</option>';
                }
                $result['rooms'] = $text;
            }
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * edit course schedule - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $scheduleId    = $this->getRequest()->getParam('id');
        $schedule      = $this->_initSchedule();
        if ($scheduleId && !$schedule->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_register')->__('This course schedule no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getScheduleData(true);
        if (!empty($data)) {
            $schedule->setData($data);
        }
        Mage::register('schedule_data', $schedule);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_register')->__('Register'))
             ->_title(Mage::helper('bs_register')->__('Course Schedule'));
        if ($schedule->getId()) {
            $this->_title($schedule->getScheduleNote());
        } else {
            $this->_title(Mage::helper('bs_register')->__('Add course schedule'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new course schedule action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save course schedule - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('schedule')) {
            try {
                //check the first item
                $first = reset($data);
                if(is_array($first)){
                    //version 2
                    $i = 1;
                    //get current schedules to increase the counter
                    $schedules = Mage::getModel('bs_register/schedule')->getCollection()->addFieldToFilter('course_id', $first['course_id']);
                    if($schedules->count()){
                        $i = $schedules->count() + 1;
                    }


                    $data = $this->sortData($data);
                    foreach ($data as $item) {
                        $item = $this->_filterDates($item, array('schedule_start_date' ,'schedule_finish_date'));
                        $res = $this->validateInvalidDate($item['course_id'],$item['schedule_start_date'],$item['schedule_finish_date']);
                        if(!$res){
                            /*if($item['subject_type'] == 0){
                                unset($item['schedule_subjects']);
                            }else{
                                unset($item['subject_id']);
                            }*/

                            if($item['subject_type'] == 0){
                                $item['schedule_subjects']= array($item['subject_id']);
                            }else{
                                unset($item['subject_id']);
                            }

                            $subjectIds = $item['schedule_subjects'];
                            $subNames = array();
                            foreach ($subjectIds as $subjectId) {
                                $subject = Mage::getModel('bs_subject/subject')->load($subjectId);
                                $subNames[] = $subject->getSubjectName();
                            }
                            $subNames = implode(", ", $subNames);
                            $item['schedule_subject_names'] = $subNames;
                            $item['schedule_order'] = $i;

                            $schedule = Mage::getModel('bs_register/schedule');
                            $schedule->addData($item);
                            $schedule->save();


                            $i++;
                        }



                        
                    }

                    $add = '';
                    if($this->getRequest()->getParam('popup')){
                        $add = '<script>window.opener.schedule_gridJsObject.reload(); window.close()</script>';
                    }

                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('bs_register')->__('Course Schedule was successfully saved. %s', $add)
                    );

                    $this->_redirect('*/catalog_product/edit', array('id'=>$first['course_id'], 'tab'=>'product_info_tabs_schedules'));

                    return;
                }else {
                    $data = $this->_filterDates($data, array('schedule_start_date' ,'schedule_finish_date'));

                    $res = $this->validateInvalidDate($data['course_id'],$data['schedule_start_date'],$data['schedule_finish_date']);
                    if($res){
                        Mage::getSingleton('adminhtml/session')->addError($res);
                        Mage::getSingleton('adminhtml/session')->setScheduleData($data);
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'popup'=>true, 'product_id'=>$data['course_id']));
                        return;
                    }

                    if($data['subject_id'] == 0 && $data['schedule_subjects'] == ''){
                        Mage::getSingleton('adminhtml/session')->addError('Please select subject(s)!');
                        Mage::getSingleton('adminhtml/session')->setScheduleData($data);
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'popup'=>true, 'product_id'=>$data['course_id']));
                        return;
                    }

                    if($data['subject_id'] != 0 && $data['schedule_subjects'] != ''){
                        Mage::getSingleton('adminhtml/session')->addError('You can only set schedule by one subject OR by multi subjects once at a time!');
                        Mage::getSingleton('adminhtml/session')->setScheduleData($data);
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'popup'=>true, 'product_id'=>$data['course_id']));
                        return;
                    }


                    //Make sure we convert single subject type to multiple before saving
                    if(isset($data['subject_id'])){
                        $data['schedule_subjects']= array($data['subject_id']);
                        //unset($data['subject_id']);
                    }

                    $subjectIds = $data['schedule_subjects'];
                    $subNames = array();
                    foreach ($subjectIds as $subjectId) {
                        $subject = Mage::getModel('bs_subject/subject')->load($subjectId);
                        $subNames[] = $subject->getSubjectName();
                    }
                    $subNames = implode(", ", $subNames);
                    $data['schedule_subject_names'] = $subNames;

                    $schedule = $this->_initSchedule();
                    $schedule->addData($data);
                    $schedule->save();

                    $add = '';
                    if($this->getRequest()->getParam('popup')){
                        $add = '<script>window.opener.schedule_gridJsObject.reload(); window.close()</script>';
                    }

                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('bs_register')->__('Course Schedule was successfully saved. %s', $add)
                    );
                    Mage::getSingleton('adminhtml/session')->setFormData(false);
                    if ($this->getRequest()->getParam('back')) {
                        $this->_redirect('*/*/edit', array('id' => $schedule->getId()));
                        return;
                    }
                    $this->_redirect('*/catalog_product/edit', array('id'=>$data['course_id'], 'tab'=>'product_info_tabs_schedules'));
                    return;
                }
                
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setScheduleData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was a problem saving the course schedule.')
                );
                Mage::getSingleton('adminhtml/session')->setScheduleData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_register')->__('Unable to find course schedule to save.')
        );
        $this->_redirect('*/*/');
    }

    public function sortData($array){
        $keys = array_keys($array);
        asort($keys);
        $newArray = array();
        foreach ($keys as $key) {
            $newArray[$key] = $array[$key];
        }

        return $newArray;
    }

    public function clearAction()
    {
        try {

            $productId = $this->getRequest()->getParam('product_id');

            $shedules = Mage::getModel('bs_register/schedule')->getCollection()->addFieldToFilter('course_id', $productId);
            if($shedules->count()){
                $shedules->walk('delete');
            }

            $add = '';
            if($this->getRequest()->getParam('popup')){
                $add = '<script>window.opener.schedule_gridJsObject.reload(); window.close()</script>';
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('bs_register')->__('All course schedules were successfully deleted. %s', $add)
            );
            Mage::getSingleton('adminhtml/session')->setFormData(false);

            $this->_redirect('*/catalog_product/edit', array('id'=>$productId, 'tab'=>'product_info_tabs_schedules'));
            return;
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

            $this->_redirect('*/catalog_product/edit', array('id'=>$productId, 'tab'=>'product_info_tabs_schedules'));
            return;
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_register')->__('There was a problem saving the course schedule.')
            );
            $this->_redirect('*/catalog_product/edit', array('id'=>$productId, 'tab'=>'product_info_tabs_schedules'));
            return;
        }

        $this->_redirect('*/catalog_product/edit', array('id'=>$productId, 'tab'=>'product_info_tabs_schedules'));
    }

    /**
     * validate Date
     *
     * @access public
     * @return bool
     * @author Bui Phong
     */

    public function validateInvalidDate($courseId, $startDate, $finishDate){
        $startDate = str_replace(" 00:00:00","", $startDate);
        $finishDate = str_replace(" 00:00:00","", $finishDate);

        $startDate = strtotime($startDate);
        $finishDate = strtotime($finishDate);

        if($startDate > $finishDate){
            return Mage::helper('bs_register')->__('Finish Date should be equal or greater than Start Date');
        }

        $course = Mage::getModel('catalog/product')->load($courseId);



        $courseStartDate = str_replace(" 00:00:00","",$course->getCourseStartDate());
        $courseFinishDate = str_replace(" 00:00:00","",$course->getCourseFinishDate());

        $courseStartDate = strtotime($courseStartDate);
        $courseFinishDate = strtotime($courseFinishDate);


        if($courseStartDate > $startDate){
            return Mage::helper('bs_register')->__('Start Date should be equal or greater than Course Start Date: %s', Mage::getModel('core/date')->date('M d, Y',$courseStartDate));
        }


        if($courseFinishDate < $finishDate){
            return Mage::helper('bs_register')->__('Finish Date should be equal or lower than Course Finish Date: %s', Mage::getModel('core/date')->date('M d, Y',$courseFinishDate));
        }



        return false;



    }

    /**
     * delete course schedule - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $schedule = Mage::getModel('bs_register/schedule');
                $schedule->setId($this->getRequest()->getParam('id'))->delete();

                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.schedule_gridJsObject.reload(); window.close()</script>';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_register')->__('Course Schedule was successfully deleted. %s', $add)
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was an error deleting course schedule.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_register')->__('Could not find course schedule to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete course schedule - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $scheduleIds = $this->getRequest()->getParam('schedule');
        if (!is_array($scheduleIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_register')->__('Please select course schedule to delete.')
            );
        } else {
            try {
                foreach ($scheduleIds as $scheduleId) {
                    $schedule = Mage::getModel('bs_register/schedule');
                    $schedule->setId($scheduleId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_register')->__('Total of %d course schedule were successfully deleted.', count($scheduleIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was an error deleting course schedule.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massStatusAction()
    {
        $scheduleIds = $this->getRequest()->getParam('schedule');
        if (!is_array($scheduleIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_register')->__('Please select course schedule.')
            );
        } else {
            try {
                foreach ($scheduleIds as $scheduleId) {
                $schedule = Mage::getSingleton('bs_register/schedule')->load($scheduleId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d course schedule were successfully updated.', count($scheduleIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was an error updating course schedule.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Start Time change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massScheduleStartTimeAction()
    {
        $scheduleIds = $this->getRequest()->getParam('schedule');
        if (!is_array($scheduleIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_register')->__('Please select course schedule.')
            );
        } else {
            try {
                foreach ($scheduleIds as $scheduleId) {
                $schedule = Mage::getSingleton('bs_register/schedule')->load($scheduleId)
                    ->setScheduleStartTime($this->getRequest()->getParam('flag_schedule_start_time'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d course schedule were successfully updated.', count($scheduleIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was an error updating course schedule.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Finish Time change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massScheduleFinishTimeAction()
    {
        $scheduleIds = $this->getRequest()->getParam('schedule');
        if (!is_array($scheduleIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_register')->__('Please select course schedule.')
            );
        } else {
            try {
                foreach ($scheduleIds as $scheduleId) {
                $schedule = Mage::getSingleton('bs_register/schedule')->load($scheduleId)
                    ->setScheduleFinishTime($this->getRequest()->getParam('flag_schedule_finish_time'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d course schedule were successfully updated.', count($scheduleIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was an error updating course schedule.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportCsvAction()
    {
        $fileName   = 'schedule.csv';
        $content    = $this->getLayout()->createBlock('bs_register/adminhtml_schedule_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportExcelAction()
    {
        $fileName   = 'schedule.xls';
        $content    = $this->getLayout()->createBlock('bs_register/adminhtml_schedule_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportXmlAction()
    {
        $fileName   = 'schedule.xml';
        $content    = $this->getLayout()->createBlock('bs_register/adminhtml_schedule_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Bui Phong
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/bs_register/schedule');
    }
}
