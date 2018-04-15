<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Info admin controller
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
class BS_InstructorInfo_Adminhtml_Instructorinfo_InfoController extends BS_InstructorInfo_Controller_Adminhtml_InstructorInfo
{
    /**
     * init the info
     *
     * @access protected
     * @return BS_InstructorInfo_Model_Info
     */
    protected function _initInfo()
    {
        $infoId  = (int) $this->getRequest()->getParam('id');
        $info    = Mage::getModel('bs_instructorinfo/info');
        if ($infoId) {
            $info->load($infoId);
        }
        Mage::register('current_info', $info);
        return $info;
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
        $this->_title(Mage::helper('bs_instructorinfo')->__('Instructor Info'))
             ->_title(Mage::helper('bs_instructorinfo')->__('Infos'));
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
     * edit info - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $infoId    = $this->getRequest()->getParam('id');
        $info      = $this->_initInfo();
        if ($infoId && !$info->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_instructorinfo')->__('This info no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getInfoData(true);
        if (!empty($data)) {
            $info->setData($data);
        }
        Mage::register('info_data', $info);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_instructorinfo')->__('Instructor Info'))
             ->_title(Mage::helper('bs_instructorinfo')->__('Infos'));
        if ($info->getId()) {
            $this->_title($info->getApprovedCourse());
        } else {
            $this->_title(Mage::helper('bs_instructorinfo')->__('Add info'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new info action
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
     * save info - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('info')) {
            try {
                $data = $this->_filterDates($data, array('approved_date' ,'expire_date'));
                $info = $this->_initInfo();

                if($data['import'] != ''){
                    $import = $data['import'];
                    $import = explode("\r\n", $import);
                    $instructorId = $data['instructor_id'];
                    foreach ($import as $line) {
                        $item = explode("\t", $line);
                        if(count($item) > 1){
                            $compliance = Mage::getSingleton('bs_instructorinfo/info_attribute_source_compliancewith')->getOptionValue(trim($item[0]));

                            $approvedDate = trim($item[4]);
                            if($approvedDate != '' && $approvedDate != 'N/A'){
                                $test = explode("/", $approvedDate);
                                $year = $test[count($test)-1];

                                if(count($test) == 2){
                                    //just m/y
                                    if(strlen($year)==4){
                                        $approvedDate = DateTime::createFromFormat('m/Y', $approvedDate)->format('Y-m-d');
                                    }else {
                                        $approvedDate = DateTime::createFromFormat('m/y', $approvedDate)->format('Y-m-d');
                                    }

                                }elseif(count($test) == 3){
                                    if(strlen($year)==4){
                                        $approvedDate = DateTime::createFromFormat('m/d/Y', $approvedDate)->format('Y-m-d');
                                    }else {
                                        $approvedDate = DateTime::createFromFormat('m/d/y', $approvedDate)->format('Y-m-d');
                                    }

                                }




                            }else {
                                $approvedDate = null;
                            }
                            $expireDate = trim($item[5]);
                            if($expireDate != '' && $expireDate != 'N/A'){
                                $test = explode("/", $expireDate);
                                $year = $test[count($test)-1];

                                if(count($test) == 2){
                                    //just m/y
                                    if(strlen($year)==4){
                                        $expireDate = DateTime::createFromFormat('m/Y', $expireDate)->format('Y-m-d');
                                    }else {
                                        $expireDate = DateTime::createFromFormat('m/y', $expireDate)->format('Y-m-d');
                                    }

                                }elseif(count($test) == 3){
                                    if(strlen($year)==4){
                                        $expireDate = DateTime::createFromFormat('m/d/Y', $expireDate)->format('Y-m-d');
                                    }else {
                                        $expireDate = DateTime::createFromFormat('m/d/y', $expireDate)->format('Y-m-d');
                                    }


                                }else {
                                    $expireDate = null;
                                }



                            }else {
                                $expireDate = null;
                            }

                            $info = Mage::getModel('bs_instructorinfo/info');
                            $info->setComplianceWith($compliance)
                                ->setInstructorId($instructorId)
                                ->setApprovedCourse(trim($item[1]))
                                ->setApprovedFunction(trim($item[2]))
                                ->setApprovedDoc(trim($item[3]))
                                ->setApprovedDate($approvedDate)
                                ->setExpireDate($expireDate)
                            ;
                            $info->save();
                        }



                    }

                }else {
                    $info->addData($data);
                    $info->save();
                }

                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.infor_gridJsObject.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructorinfo')->__('Info was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $info->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setInfoData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorinfo')->__('There was a problem saving the info.')
                );
                Mage::getSingleton('adminhtml/session')->setInfoData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_instructorinfo')->__('Unable to find info to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete info - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $info = Mage::getModel('bs_instructorinfo/info');
                $info->setId($this->getRequest()->getParam('id'))->delete();

                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.infor_gridJsObject.reload(); window.close()</script>';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructorinfo')->__('Info was successfully deleted. %s', $add)
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorinfo')->__('There was an error deleting info.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_instructorinfo')->__('Could not find info to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete info - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $infoIds = $this->getRequest()->getParam('info');
        if (!is_array($infoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructorinfo')->__('Please select infos to delete.')
            );
        } else {
            try {
                foreach ($infoIds as $infoId) {
                    $info = Mage::getModel('bs_instructorinfo/info');
                    $info->setId($infoId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructorinfo')->__('Total of %d infos were successfully deleted.', count($infoIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorinfo')->__('There was an error deleting infos.')
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
        $infoIds = $this->getRequest()->getParam('info');
        if (!is_array($infoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructorinfo')->__('Please select infos.')
            );
        } else {
            try {
                foreach ($infoIds as $infoId) {
                $info = Mage::getSingleton('bs_instructorinfo/info')->load($infoId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d infos were successfully updated.', count($infoIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorinfo')->__('There was an error updating infos.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Compliance With change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massComplianceWithAction()
    {
        $infoIds = $this->getRequest()->getParam('info');
        if (!is_array($infoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructorinfo')->__('Please select infos.')
            );
        } else {
            try {
                foreach ($infoIds as $infoId) {
                $info = Mage::getSingleton('bs_instructorinfo/info')->load($infoId)
                    ->setComplianceWith($this->getRequest()->getParam('flag_compliance_with'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d infos were successfully updated.', count($infoIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorinfo')->__('There was an error updating infos.')
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
        $fileName   = 'info.csv';
        $content    = $this->getLayout()->createBlock('bs_instructorinfo/adminhtml_info_grid')
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
        $fileName   = 'info.xls';
        $content    = $this->getLayout()->createBlock('bs_instructorinfo/adminhtml_info_grid')
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
        $fileName   = 'info.xml';
        $content    = $this->getLayout()->createBlock('bs_instructorinfo/adminhtml_info_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/bs_instructor/info');
    }
}
