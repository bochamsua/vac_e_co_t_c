<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Function admin controller
 *
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
class BS_Tasktraining_Adminhtml_Tasktraining_TaskfunctionController extends BS_Tasktraining_Controller_Adminhtml_Tasktraining
{
    /**
     * init the instructor function
     *
     * @access protected
     * @return BS_Tasktraining_Model_Taskfunction
     */
    protected function _initTaskfunction()
    {
        $taskfunctionId  = (int) $this->getRequest()->getParam('id');
        $taskfunction    = Mage::getModel('bs_tasktraining/taskfunction');
        if ($taskfunctionId) {
            $taskfunction->load($taskfunctionId);
        }
        Mage::register('current_taskfunction', $taskfunction);
        return $taskfunction;
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
        $this->_title(Mage::helper('bs_tasktraining')->__('Task Training Instructor'))
             ->_title(Mage::helper('bs_tasktraining')->__('Instructor Function'));
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

    /**
     * edit instructor function - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $taskfunctionId    = $this->getRequest()->getParam('id');
        $taskfunction      = $this->_initTaskfunction();
        if ($taskfunctionId && !$taskfunction->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_tasktraining')->__('This instructor function no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getTaskfunctionData(true);
        if (!empty($data)) {
            $taskfunction->setData($data);
        }
        Mage::register('taskfunction_data', $taskfunction);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_tasktraining')->__('Task Training Instructor'))
             ->_title(Mage::helper('bs_tasktraining')->__('Instructor Function'));
        if ($taskfunction->getId()) {
            $this->_title($taskfunction->getApprovedCourse());
        } else {
            $this->_title(Mage::helper('bs_tasktraining')->__('Add instructor function'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new instructor function action
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
     * save instructor function - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('taskfunction')) {

            if($data['import'] != ''){
                $import = $data['import'];
                $lines = explode("\r\n", $import);

                $i=0;
                foreach ($lines as $line) {
                    if(strpos($line, "--")){
                        $line = explode("--", $line);
                    }else {
                        $line = explode("\t", $line);
                    }
                    if(count($line)){

                        $line = array_map('trim', $line);
                        $catId = $line[0];
                        $vaecoId = $line[1];
                        $course = $line[2];
                        $function = $line[3];
                        $instructorId = null;

                        //$instructorId = $this->checkExisting($catId, $vaecoId);

                        //Check if we add this instructor yet
                        try {
                            $instructor = Mage::getModel('bs_tasktraining/taskinstructor')->getCollection()->addFieldToFilter('vaeco_id', $vaecoId)->getFirstItem();
                            $tfunction = Mage::getModel('bs_tasktraining/taskfunction');

                            if ($instructor->getId()) {
                                $instructorId = $instructor->getId();
                                //now check if this function is existed
                                $tf = Mage::getModel('bs_tasktraining/taskfunction')->getCollection()->addFieldToFilter('category_id', $catId)->addFieldToFilter('instructor_id', $instructorId)->getFirstItem();
                                if ($tf->getId()) {//we load existing
                                    $tfunction->load($tf->getId());
                                }
                            } else {//if instructor doesn't exist then we add he/she first

                                //get name
                                $cus = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
                                if($cus->getId()){
                                    $customer = Mage::getModel('customer/customer')->load($cus->getId());
                                    $name = $customer->getName();
                                    $instructor = Mage::getModel('bs_tasktraining/taskinstructor');
                                    $instructor->setName($name);
                                    $instructor->setVaecoId($vaecoId);
                                    $instructor->save();

                                    $instructorId = $instructor->getId();


                                }


                            }
                            $tfunction->setCategoryId($catId)
                                ->setInstructorId($instructorId)
                                ->setApprovedCourse($course)
                                ->setApprovedFunction($function)
                                ->setIsNew(true)
                                ->save()
                            ;

                        } catch (Exception $e) {
                            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                        }






                    }
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_tasktraining')->__('Total %s Instructor Function were successfully added. ', $i)
                );
                $this->_redirect('*/*/');
                return;



            }else {
                try {
                    $data = $this->_filterDates($data, array('approved_date','expire_date'));

                    $taskfunction = $this->_initTaskfunction();
                    $taskfunction->addData($data);
                    $taskfunction->save();
                    $add = '';
                    if($this->getRequest()->getParam('popup')){
                        $add = '<script>window.close()</script>';
                    }
                    Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('bs_tasktraining')->__('Instructor Function was successfully saved. %s', $add)
                    );
                    Mage::getSingleton('adminhtml/session')->setFormData(false);
                    if ($this->getRequest()->getParam('back')) {
                        $this->_redirect('*/*/edit', array('id' => $taskfunction->getId()));
                        return;
                    }
                    $this->_redirect('*/*/');
                    return;
                } catch (Mage_Core_Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setTaskfunctionData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                } catch (Exception $e) {
                    Mage::logException($e);
                    Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('bs_tasktraining')->__('There was a problem saving the instructor function.')
                    );
                    Mage::getSingleton('adminhtml/session')->setTaskfunctionData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
            }


        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_tasktraining')->__('Unable to find instructor function to save.')
        );
        $this->_redirect('*/*/');
    }

    public function checkExisting($catId, $vaecoId){
        //This must return false or instructor Id
        $instructor = Mage::getModel('bs_tasktraining/taskinstructor')->getCollection()->addFieldToFilter('vaeco_id', $vaecoId)->getFirstItem();
        if($instructor->getId()){
            $function = Mage::getModel('bs_tasktraining/taskfunction')->getCollection()->addFieldToFilter('category_id',$catId)->addFieldToFilter('instructor_id', $instructor->getId())->getFirstItem();
            if($function->getId()){
                return false;
            }

            return $instructor->getId();
        }

    }

    public function getCourseAction(){
        $result = array();

        $catId = $this->getRequest()->getPost('cat_id');
        $result['course'] = '';
        if($catId){

            $resource = Mage::getSingleton('core/resource');
            //$writeConnection = $resource->getConnection('core_write');
            $readConnection = $resource->getConnection('core_read');

            $courses = $readConnection->fetchCol("SELECT DISTINCT approved_course FROM bs_tasktraining_taskfunction WHERE category_id = ".$catId);
            $function = $readConnection->fetchCol("SELECT DISTINCT approved_function FROM bs_tasktraining_taskfunction WHERE category_id = ".$catId);
            $result['course'] = implode(" --- ", $courses);
            $result['function'] = implode(" --- ", $function);



        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * delete instructor function - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $taskfunction = Mage::getModel('bs_tasktraining/taskfunction');
                $taskfunction->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_tasktraining')->__('Instructor Function was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error deleting instructor function.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_tasktraining')->__('Could not find instructor function to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete instructor function - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $taskfunctionIds = $this->getRequest()->getParam('taskfunction');
        if (!is_array($taskfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tasktraining')->__('Please select instructor function to delete.')
            );
        } else {
            try {
                foreach ($taskfunctionIds as $taskfunctionId) {
                    $taskfunction = Mage::getModel('bs_tasktraining/taskfunction');
                    $taskfunction->setId($taskfunctionId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_tasktraining')->__('Total of %d instructor function were successfully deleted.', count($taskfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error deleting instructor function.')
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
        $taskfunctionIds = $this->getRequest()->getParam('taskfunction');
        if (!is_array($taskfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tasktraining')->__('Please select instructor function.')
            );
        } else {
            try {
                foreach ($taskfunctionIds as $taskfunctionId) {
                $taskfunction = Mage::getSingleton('bs_tasktraining/taskfunction')->load($taskfunctionId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor function were successfully updated.', count($taskfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error updating instructor function.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massChangeOldAction()
    {
        $taskfunctionIds = $this->getRequest()->getParam('taskfunction');
        if (!is_array($taskfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tasktraining')->__('Please select instructor function.')
            );
        } else {
            try {
                foreach ($taskfunctionIds as $taskfunctionId) {
                    $taskfunction = Mage::getSingleton('bs_tasktraining/taskfunction')->load($taskfunctionId)
                        ->setIsNew($this->getRequest()->getParam('is_new'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor function were successfully updated.', count($taskfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error updating instructor function.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massApprovedDocAction()
    {
        $taskfunctionIds = $this->getRequest()->getParam('taskfunction');
        if (!is_array($taskfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tasktraining')->__('Please select instructor function.')
            );
        } else {
            try {
                $doc = $this->getRequest()->getParam('approved_doc');

                foreach ($taskfunctionIds as $taskfunctionId) {
                    $taskfunction = Mage::getSingleton('bs_tasktraining/taskfunction')->load($taskfunctionId)
                        ->setApprovedDoc($doc)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor function were successfully updated.', count($taskfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error updating instructor function.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massUpdateFunctionAction()
    {
        $taskfunctionIds = $this->getRequest()->getParam('taskfunction');
        if (!is_array($taskfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tasktraining')->__('Please select instructor function.')
            );
        } else {
            try {
                $function = $this->getRequest()->getParam('update_function');
                //$function .= '[update]';

                foreach ($taskfunctionIds as $taskfunctionId) {
                    $taskfunction = Mage::getSingleton('bs_tasktraining/taskfunction')->load($taskfunctionId);

                    $currentFunction = $taskfunction->getApprovedFunction();

                    $taskfunction->setApprovedFunction($function)
                        ->setUpdateFunction(true)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor function were successfully updated.', count($taskfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error updating instructor function.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massApprovedDateAction()
    {
        $taskfunctionIds = $this->getRequest()->getParam('taskfunction');
        if (!is_array($taskfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tasktraining')->__('Please select instructor function.')
            );
        } else {
            try {
                $date = $this->getRequest()->getParam('approved_date');
                $dates = array('input_date'=>$date);

                $dates = $this->_filterDates($dates,array('input_date'));

                $date = $dates['input_date'];

                foreach ($taskfunctionIds as $taskfunctionId) {
                    $taskfunction = Mage::getSingleton('bs_tasktraining/taskfunction')->load($taskfunctionId)
                        ->setApprovedDate($date)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor function were successfully updated.', count($taskfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error updating instructor function.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massExpireDateAction()
    {
        $taskfunctionIds = $this->getRequest()->getParam('taskfunction');
        if (!is_array($taskfunctionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_tasktraining')->__('Please select instructor function.')
            );
        } else {
            try {
                $date = $this->getRequest()->getParam('expire_date');
                $dates = array('input_date'=>$date);
                $dates = $this->_filterDates($dates,array('input_date'));
                $date = $dates['input_date'];

                foreach ($taskfunctionIds as $taskfunctionId) {
                    $taskfunction = Mage::getSingleton('bs_tasktraining/taskfunction')->load($taskfunctionId)
                        ->setExpireDate($date)
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor function were successfully updated.', count($taskfunctionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_tasktraining')->__('There was an error updating instructor function.')
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
        $fileName   = 'taskfunction.csv';
        $content    = $this->getLayout()->createBlock('bs_tasktraining/adminhtml_taskfunction_grid')
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
        $fileName   = 'taskfunction.xls';
        $content    = $this->getLayout()->createBlock('bs_tasktraining/adminhtml_taskfunction_grid')
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
        $fileName   = 'taskfunction.xml';
        $content    = $this->getLayout()->createBlock('bs_tasktraining/adminhtml_taskfunction_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/bs_tasktraining/taskfunction');
    }
}
