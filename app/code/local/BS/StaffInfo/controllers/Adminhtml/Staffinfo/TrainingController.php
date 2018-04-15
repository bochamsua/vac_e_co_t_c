<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Related Training admin controller
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Adminhtml_Staffinfo_TrainingController extends BS_StaffInfo_Controller_Adminhtml_StaffInfo
{
    /**
     * init the related training
     *
     * @access protected
     * @return BS_StaffInfo_Model_Training
     */
    protected function _initTraining()
    {
        $trainingId  = (int) $this->getRequest()->getParam('id');
        $training    = Mage::getModel('bs_staffinfo/training');
        if ($trainingId) {
            $training->load($trainingId);
        }
        Mage::register('current_training', $training);
        return $training;
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
        $this->_title(Mage::helper('bs_staffinfo')->__('Related Info'))
             ->_title(Mage::helper('bs_staffinfo')->__('Related Trainings'));
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
     * edit related training - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $trainingId    = $this->getRequest()->getParam('id');
        $staffId    = $this->getRequest()->getParam('staff_id', false);
        $training      = $this->_initTraining();
        if ($trainingId && !$training->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_staffinfo')->__('This related training no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }



        if(!$staffId && !$training->getStaffId()){
            $this->_getSession()->addError(
                Mage::helper('bs_staffinfo')->__('Please specify the Staff.')
            );
            $this->_redirect('*/*/');
            return;

        }
        $data = Mage::getSingleton('adminhtml/session')->getTrainingData(true);
        if (!empty($data)) {
            $training->setData($data);
        }
        Mage::register('training_data', $training);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_staffinfo')->__('Related Info'))
             ->_title(Mage::helper('bs_staffinfo')->__('Related Trainings'));
        if ($training->getId()) {
            $this->_title($training->getCourse());
        } else {
            $this->_title(Mage::helper('bs_staffinfo')->__('Add related training'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new related training action
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
     * save related training - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('training')) {
            try {
                $data = $this->_filterDates($data, array('start_date' ,'end_date'));
                $training = $this->_initTraining();

                if($data['import'] != ''){
                    $import = $data['import'];
                    $import = explode("\r\n", $import);
                    $instructorId = $data['staff_id'];
                    foreach ($import as $line) {
                        $item = explode("\t", $line);
                        $count = count($item);
                        if($count > 1){

                            $certInfo = '';
                            $startDate = null;
                            $endDate = null;
                            $title = trim($item[0]);
                            $country = trim($item[1]);

                            if($count == 6){//This should be the importing from the Employee Training Record
                                $startDate = trim($item[2]);
                                $startDate = str_replace(" ", "", $startDate);
                                $endDate = trim($item[3]);
                                $endDate = str_replace(" ", "", $endDate);
                                $certInfo = trim($item[5]);
                                $certInfo = str_replace("TTBT", "TTĐT", $certInfo);
                                if(strpos($certInfo,"-")){
                                    $certInfo = str_replace(" ", "", $certInfo);
                                }
                                $startDate = DateTime::createFromFormat('d-M-y', $startDate)->format('Y-m-d');
                                $endDate = DateTime::createFromFormat('d-M-y', $endDate)->format('Y-m-d');



                            }else {
                                $startDate = trim($item[2]);
                                if($startDate != '' && $startDate != 'N/A'){

                                    $test = explode("/", $startDate);
                                    if(count($test) == 2){
                                        //just m/y
                                        $startDate = DateTime::createFromFormat('m/y', $startDate)->format('Y-m-d');
                                    }elseif(count($test) == 3){
                                        $startDate = DateTime::createFromFormat('m/d/y', $startDate)->format('Y-m-d');
                                    }
                                }

                                $endDate = trim($item[3]);
                                if($endDate != '' && $endDate != 'N/A'){

                                    $test = explode("/", $endDate);
                                    if(count($test) == 2){
                                        //just m/y
                                        $endDate = DateTime::createFromFormat('m/y', $endDate)->format('Y-m-d');
                                    }elseif(count($test) == 3){
                                        $endDate = DateTime::createFromFormat('m/d/y', $endDate)->format('Y-m-d');
                                    }
                                }
                                $certInfo = trim($item[4]);
                                $certInfo = str_replace("TTBT", "TTĐT", $certInfo);
                                if(strpos($certInfo,"-")){
                                    $certInfo = str_replace(" ", "", $certInfo);
                                }

                            }




                            $info = Mage::getModel('bs_staffinfo/training');
                            $info->setStaffId($instructorId)
                                ->setCourse($title)
                                ->setOrganization($country)
                                ->setStartDate($startDate)
                                ->setEndDate($endDate)
                                ->setCertificate($certInfo)
                            ;
                            $info->save();
                        }



                    }

                }else {
                    $training->addData($data);
                    $training->save();
                }


                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.training_gridJsObject.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_staffinfo')->__('Related Training was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $training->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setTrainingData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_staffinfo')->__('There was a problem saving the related training.')
                );
                Mage::getSingleton('adminhtml/session')->setTrainingData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_staffinfo')->__('Unable to find related training to save.')
        );
        $this->_redirect('*/*/');
    }

    public function massUseAction()
    {
        $otherinfoIds = $this->getRequest()->getParam('training');
        if (!is_array($otherinfoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_staffinfo')->__('Please select info.')
            );
        } else {
            try {
                foreach ($otherinfoIds as $otherinfoId) {
                    $otherinfo = Mage::getSingleton('bs_staffinfo/training')->load($otherinfoId);
                    $title = $otherinfo->getCourse();
                    $country = $otherinfo->getOrganization();
                    $cert = $otherinfo->getCertificate();
                    $year = '';
                    if($otherinfo->getStartDate()){
                        $year = new DateTime($otherinfo->getStartDate());
                        $year = $year->format("Y");
                    }

                    $cus= Mage::getModel('customer/customer')->load($otherinfo->getStaffId());
                    $vaecoId = $cus->getVaecoId();


                    //Now echo strings

                    $this->_getSession()->addSuccess($vaecoId.'--'.$title.'--'.$country.'--'.$year.'--'.$cert.'<br>');




                }

            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_staffinfo')->__('There was an error updating info.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    public function massReplaceCountryAction()
    {
        $otherinfoIds = $this->getRequest()->getParam('training');
        if (!is_array($otherinfoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_staffinfo')->__('Please select info.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('replace_country');
                $param = explode("|", $param);
                if(count($param) == 2){
                    $search = $param[0];
                    $replace = $param[1];
                    foreach ($otherinfoIds as $otherinfoId) {
                        $otherinfo = Mage::getSingleton('bs_staffinfo/training')->load($otherinfoId);

                        $country = $otherinfo->getOrganization();
                        $country = str_replace($search, $replace, $country);

                        $otherinfo->setOrganization($country)->save();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d info were successfully updated.', count($otherinfoIds))
                    );
                }else {
                    $this->_getSession()->addNotice(
                        $this->__('Invalid format for replacement!')
                    );
                }



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_staffinfo')->__('There was an error updating info.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massReplaceCourseAction()
    {
        $otherinfoIds = $this->getRequest()->getParam('training');
        if (!is_array($otherinfoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_staffinfo')->__('Please select info.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('replace_course');
                $param = explode("|", $param);
                if(count($param) == 2){
                    $search = $param[0];
                    $replace = $param[1];
                    foreach ($otherinfoIds as $otherinfoId) {
                        $otherinfo = Mage::getSingleton('bs_staffinfo/training')->load($otherinfoId);

                        $course = $otherinfo->getCourse();
                        $course = str_replace($search, $replace, $course);

                        $otherinfo->setCourse($course)->save();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d info were successfully updated.', count($otherinfoIds))
                    );
                }else {
                    $this->_getSession()->addNotice(
                        $this->__('Invalid format for replacement!')
                    );
                }



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_staffinfo')->__('There was an error updating info.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massReplaceCertAction()
    {
        $otherinfoIds = $this->getRequest()->getParam('training');
        if (!is_array($otherinfoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_staffinfo')->__('Please select info.')
            );
        } else {
            try {
                $param = $this->getRequest()->getParam('replace_cert');
                $param = explode("|", $param);
                if(count($param) == 2){
                    $search = $param[0];
                    $replace = $param[1];
                    foreach ($otherinfoIds as $otherinfoId) {
                        $otherinfo = Mage::getSingleton('bs_staffinfo/training')->load($otherinfoId);

                        $course = $otherinfo->getCertificate();
                        $course = str_replace($search, $replace, $course);

                        $otherinfo->setCertificate($course)->save();
                    }
                    $this->_getSession()->addSuccess(
                        $this->__('Total of %d info were successfully updated.', count($otherinfoIds))
                    );
                }else {
                    $this->_getSession()->addNotice(
                        $this->__('Invalid format for replacement!')
                    );
                }



            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_staffinfo')->__('There was an error updating info.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }


    /**
     * delete related training - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $training = Mage::getModel('bs_staffinfo/training');
                $training->setId($this->getRequest()->getParam('id'))->delete();

                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.training_gridJsObject.reload(); window.close()</script>';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_staffinfo')->__('Related Training was successfully deleted. %s', $add)
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_staffinfo')->__('There was an error deleting related training.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_staffinfo')->__('Could not find related training to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete related training - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $trainingIds = $this->getRequest()->getParam('training');
        if (!is_array($trainingIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_staffinfo')->__('Please select related trainings to delete.')
            );
        } else {
            try {
                foreach ($trainingIds as $trainingId) {
                    $training = Mage::getModel('bs_staffinfo/training');
                    $training->setId($trainingId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_staffinfo')->__('Total of %d related trainings were successfully deleted.', count($trainingIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_staffinfo')->__('There was an error deleting related trainings.')
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
        $trainingIds = $this->getRequest()->getParam('training');
        if (!is_array($trainingIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_staffinfo')->__('Please select related trainings.')
            );
        } else {
            try {
                foreach ($trainingIds as $trainingId) {
                $training = Mage::getSingleton('bs_staffinfo/training')->load($trainingId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d related trainings were successfully updated.', count($trainingIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_staffinfo')->__('There was an error updating related trainings.')
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
        $fileName   = 'training.csv';
        $content    = $this->getLayout()->createBlock('bs_staffinfo/adminhtml_training_grid')
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
        $fileName   = 'training.xls';
        $content    = $this->getLayout()->createBlock('bs_staffinfo/adminhtml_training_grid')
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
        $fileName   = 'training.xml';
        $content    = $this->getLayout()->createBlock('bs_staffinfo/adminhtml_training_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('customer/training');
    }
}
