<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Attendance admin controller
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_Register_Adminhtml_Register_AttendanceController extends BS_Register_Controller_Adminhtml_Register
{
    /**
     * init the attendance
     *
     * @access protected
     * @return BS_Register_Model_Attendance
     */
    protected function _initAttendance()
    {
        $attendanceId  = (int) $this->getRequest()->getParam('id');
        $attendance    = Mage::getModel('bs_register/attendance');
        if ($attendanceId) {
            $attendance->load($attendanceId);
        }
        Mage::register('current_attendance', $attendance);
        return $attendance;
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
             ->_title(Mage::helper('bs_register')->__('Absence Record'));
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


    public function updateTraineesAction(){
        $result = array();
        $courseId = $this->getRequest()->getPost('course_id');
         
        $result['trainees'] = '<option value="">There is no trainee in this class</option>';
        if($courseId > 0){
            $trainees = Mage::getResourceModel('bs_trainee/trainee_collection')
                ->addAttributeToSelect('*')->addProductFilter($courseId);
            if($trainees->count()){
                $text = '';
                foreach ($trainees as $tn) {
                    $add = $tn->getTraineeCode();

                    if($tn->getVaecoId() != ''){
                        $add = $tn->getVaecoId();
                    }
                    $text  .= '<option value="'.$tn->getId().'">'.$tn->getTraineeName().' - '.$add.'</option>';
                }
                $result['trainees'] = $text;
            }
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * edit attendance - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $attendanceId    = $this->getRequest()->getParam('id');
        $attendance      = $this->_initAttendance();
        if ($attendanceId && !$attendance->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_register')->__('This attendance no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getAttendanceData(true);
        if (!empty($data)) {
            $attendance->setData($data);
        }
        Mage::register('attendance_data', $attendance);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_register')->__('Register'))
             ->_title(Mage::helper('bs_register')->__('Absence Record'));
        if ($attendance->getId()) {
            $this->_title($attendance->getAttNote());
        } else {
            $this->_title(Mage::helper('bs_register')->__('Add Absence Record'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new attendance action
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
     * save attendance - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('attendance')) {
            try {
                $data = $this->_filterDates($data, array('att_start_date' ,'att_finish_date'));

                $res = $this->validateInvalidDate($data['course_id'],$data['att_start_date'],$data['att_finish_date']);
                if($res){
                    Mage::getSingleton('adminhtml/session')->addError($res);
                    Mage::getSingleton('adminhtml/session')->setAttendanceData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
                $attendance = $this->_initAttendance();
                $attendance->addData($data);
                $attFileName = $this->_uploadAndGetName(
                    'att_file',
                    Mage::helper('bs_register/attendance')->getFileBaseDir(),
                    $data
                );
                $attendance->setData('att_file', $attFileName);


                $attendance->save();

                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.absence_gridJsObject.reload(); window.close()</script>';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_register')->__('Attendance Absence Record was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $attendance->getId()));
                    return;
                }
                $this->_redirect('*/catalog_product/edit', array('id'=>$data['course_id'], 'tab'=>'product_info_tabs_adsences'));
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['att_file']['value'])) {
                    $data['att_file'] = $data['att_file']['value'];
                }

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setAttendanceData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                if (isset($data['att_file']['value'])) {
                    $data['att_file'] = $data['att_file']['value'];
                }

                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was a problem saving the attendance.')
                );
                Mage::getSingleton('adminhtml/session')->setAttendanceData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_register')->__('Unable to find attendance to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * validate Date
     *
     * @access public
     * @return bool
     * @author Bui Phong
     */

    public function validateInvalidDate($courseId, $startDate, $finishDate){
        if($startDate > $finishDate){
            return Mage::helper('bs_register')->__('OFF To Date should be equal or greater than OFF From Date');
        }

        $course = Mage::getModel('catalog/product')->load($courseId);

        $courseStartDate = str_replace(" 00:00:00","",$course->getCourseStartDate());
        $courseFinishDate = str_replace(" 00:00:00","",$course->getCourseFinishDate());


        if($courseStartDate > $startDate){
            return Mage::helper('bs_register')->__('OFF From Date should be equal or greater than Course Start Date: %s', Mage::getModel('core/date')->date('M d, Y',$courseStartDate));
        }


        if($courseFinishDate < $finishDate){
            return Mage::helper('bs_register')->__('OFF To Date should be equal or lower than Course Finish Date: %s', Mage::getModel('core/date')->date('M d, Y',$courseFinishDate));
        }



        return false;



    }


    /**
     * delete attendance - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $attendance = Mage::getModel('bs_register/attendance');
                $attendance->setId($this->getRequest()->getParam('id'))->delete();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.absence_gridJsObject.reload();window.close()</script>';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_register')->__('Attendance was successfully deleted. %s', $add)
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was an error deleting attendance.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_register')->__('Could not find attendance to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete attendance - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $attendanceIds = $this->getRequest()->getParam('attendance');
        if (!is_array($attendanceIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_register')->__('Please select attendance to delete.')
            );
        } else {
            try {
                foreach ($attendanceIds as $attendanceId) {
                    $attendance = Mage::getModel('bs_register/attendance');
                    $attendance->setId($attendanceId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_register')->__('Total of %d attendance were successfully deleted.', count($attendanceIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was an error deleting attendance.')
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
        $attendanceIds = $this->getRequest()->getParam('attendance');
        if (!is_array($attendanceIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_register')->__('Please select attendance.')
            );
        } else {
            try {
                foreach ($attendanceIds as $attendanceId) {
                $attendance = Mage::getSingleton('bs_register/attendance')->load($attendanceId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d attendance were successfully updated.', count($attendanceIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was an error updating attendance.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Time Start change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massAttStartTimeAction()
    {
        $attendanceIds = $this->getRequest()->getParam('attendance');
        if (!is_array($attendanceIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_register')->__('Please select attendance.')
            );
        } else {
            try {
                foreach ($attendanceIds as $attendanceId) {
                $attendance = Mage::getSingleton('bs_register/attendance')->load($attendanceId)
                    ->setAttStartTime($this->getRequest()->getParam('flag_att_start_time'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d attendance were successfully updated.', count($attendanceIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was an error updating attendance.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Time End change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massAttFinishTimeAction()
    {
        $attendanceIds = $this->getRequest()->getParam('attendance');
        if (!is_array($attendanceIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_register')->__('Please select attendance.')
            );
        } else {
            try {
                foreach ($attendanceIds as $attendanceId) {
                $attendance = Mage::getSingleton('bs_register/attendance')->load($attendanceId)
                    ->setAttFinishTime($this->getRequest()->getParam('flag_att_finish_time'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d attendance were successfully updated.', count($attendanceIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was an error updating attendance.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Excuse change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massAttExcuseAction()
    {
        $attendanceIds = $this->getRequest()->getParam('attendance');
        if (!is_array($attendanceIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_register')->__('Please select attendance.')
            );
        } else {
            try {
                foreach ($attendanceIds as $attendanceId) {
                $attendance = Mage::getSingleton('bs_register/attendance')->load($attendanceId)
                    ->setAttExcuse($this->getRequest()->getParam('flag_att_excuse'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d attendance were successfully updated.', count($attendanceIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_register')->__('There was an error updating attendance.')
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
        $fileName   = 'attendance.csv';
        $content    = $this->getLayout()->createBlock('bs_register/adminhtml_attendance_grid')
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
        $fileName   = 'attendance.xls';
        $content    = $this->getLayout()->createBlock('bs_register/adminhtml_attendance_grid')
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
        $fileName   = 'attendance.xml';
        $content    = $this->getLayout()->createBlock('bs_register/adminhtml_attendance_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('catalog/bs_register/attendance');
    }
}
