<?php
/**
 * BS_InstructorInfo extension
 * 
 * @category       BS
 * @package        BS_InstructorInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Other Info admin controller
 *
 * @category    BS
 * @package     BS_InstructorInfo
 * @author Bui Phong
 */
class BS_InstructorInfo_Adminhtml_Instructorinfo_OtherinfoController extends BS_InstructorInfo_Controller_Adminhtml_InstructorInfo
{
    /**
     * init the other info
     *
     * @access protected
     * @return BS_InstructorInfo_Model_Otherinfo
     */
    protected function _initOtherinfo()
    {
        $otherinfoId  = (int) $this->getRequest()->getParam('id');
        $otherinfo    = Mage::getModel('bs_instructorinfo/otherinfo');
        if ($otherinfoId) {
            $otherinfo->load($otherinfoId);
        }
        Mage::register('current_otherinfo', $otherinfo);
        return $otherinfo;
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
             ->_title(Mage::helper('bs_instructorinfo')->__('Training Info'));
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
     * edit other info - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $otherinfoId    = $this->getRequest()->getParam('id');
        $otherinfo      = $this->_initOtherinfo();
        if ($otherinfoId && !$otherinfo->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_instructorinfo')->__('This other info no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getOtherinfoData(true);
        if (!empty($data)) {
            $otherinfo->setData($data);
        }
        Mage::register('otherinfo_data', $otherinfo);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_instructorinfo')->__('Instructor Info'))
             ->_title(Mage::helper('bs_instructorinfo')->__('Other Infos'));
        if ($otherinfo->getId()) {
            $this->_title($otherinfo->getTitle());
        } else {
            $this->_title(Mage::helper('bs_instructorinfo')->__('Add other info'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new other info action
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
     * save other info - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('otherinfo')) {
            try {
                $data = $this->_filterDates($data, array('start_date' ,'end_date'));
                $otherinfo = $this->_initOtherinfo();
                if($data['import'] != ''){
                    $import = $data['import'];
                    $import = explode("\r\n", $import);
                    $instructorId = $data['instructor_id'];
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
                                $startDate = DateTime::createFromFormat('d-M-y', $startDate)->format('Y-m-d');
                                $endDate = DateTime::createFromFormat('d-M-y', $endDate)->format('Y-m-d');



                            }else {
                                $startDate = trim($item[2]);
                                if($startDate != '' && $startDate != 'N/A'){

                                    $test = explode("/", $startDate);
                                    $year = $test[count($test)-1];
                                    if(count($test) == 2){
                                        //just m/y
                                        if(strlen($year)==4){
                                            $startDate = DateTime::createFromFormat('m/Y', $startDate)->format('Y-m-d');
                                        }else {
                                            $startDate = DateTime::createFromFormat('m/y', $startDate)->format('Y-m-d');
                                        }

                                    }elseif(count($test) == 3){
                                        if(strlen($year)==4){
                                            $startDate = DateTime::createFromFormat('m/d/Y', $startDate)->format('Y-m-d');
                                        }else {
                                            $startDate = DateTime::createFromFormat('m/d/y', $startDate)->format('Y-m-d');
                                        }

                                    }else {
                                        $startDate = null;
                                    }
                                }else {
                                    $startDate = null;
                                }

                                $endDate = trim($item[3]);
                                if($endDate != '' && $endDate != 'N/A'){

                                    $test = explode("/", $endDate);
                                    $year = $test[count($test)-1];
                                    if(count($test) == 2){
                                        //just m/y
                                        if(strlen($year)==4){
                                            $endDate = DateTime::createFromFormat('m/Y', $endDate)->format('Y-m-d');
                                        }else {
                                            $endDate = DateTime::createFromFormat('m/y', $endDate)->format('Y-m-d');
                                        }

                                    }elseif(count($test) == 3){
                                        if(strlen($year)==4){
                                            $endDate = DateTime::createFromFormat('m/d/Y', $endDate)->format('Y-m-d');
                                        }else {
                                            $endDate = DateTime::createFromFormat('m/d/y', $endDate)->format('Y-m-d');
                                        }


                                    }else {
                                        $endDate = null;
                                    }
                                }else {
                                    $endDate = null;
                                }
                                $certInfo = trim($item[4]);
                                $certInfo = str_replace("TTBT", "TTĐT", $certInfo);

                            }




                            $info = Mage::getModel('bs_instructorinfo/otherinfo');
                            $info->setInstructorId($instructorId)
                                ->setTitle($title)
                                ->setCountry($country)
                                ->setStartDate($startDate)
                                ->setEndDate($endDate)
                                ->setCertInfo($certInfo)
                            ;
                            $info->save();
                        }



                    }

                }else {
                    $otherinfo->addData($data);
                    $otherinfo->save();
                }


                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.otherinfor_gridJsObject.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructorinfo')->__('Other Info was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $otherinfo->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setOtherinfoData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorinfo')->__('There was a problem saving the other info.')
                );
                Mage::getSingleton('adminhtml/session')->setOtherinfoData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_instructorinfo')->__('Unable to find other info to save.')
        );
        $this->_redirect('*/*/');
    }

    public function massUseAction()
    {
        $otherinfoIds = $this->getRequest()->getParam('otherinfo');
        if (!is_array($otherinfoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructorinfo')->__('Please select info.')
            );
        } else {
            try {
                foreach ($otherinfoIds as $otherinfoId) {
                    $otherinfo = Mage::getSingleton('bs_instructorinfo/otherinfo')->load($otherinfoId);
                    $title = $otherinfo->getTitle();
                    $country = $otherinfo->getCountry();
                    $cert = $otherinfo->getCertInfo();
                    $year = '';
                    if($otherinfo->getStartDate()){
                        $year = new DateTime($otherinfo->getStartDate());
                        $year = $year->format("Y");
                    }

                    $instructor = Mage::getModel('bs_instructor/instructor')->load($otherinfo->getInstructorId());
                    $vaecoId = $instructor->getIvaecoid();


                    //Now echo strings

                    $this->_getSession()->addSuccess($vaecoId.'--'.$title.'--'.$country.'--'.$year.'--'.$cert.'<br>');




                }

            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorinfo')->__('There was an error updating info.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * delete other info - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $otherinfo = Mage::getModel('bs_instructorinfo/otherinfo');
                $otherinfo->setId($this->getRequest()->getParam('id'))->delete();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.otherinfor_gridJsObject.reload(); window.close()</script>';
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
                    Mage::helper('bs_instructorinfo')->__('There was an error deleting other info.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_instructorinfo')->__('Could not find other info to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete other info - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $otherinfoIds = $this->getRequest()->getParam('otherinfo');
        if (!is_array($otherinfoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructorinfo')->__('Please select other infos to delete.')
            );
        } else {
            try {
                foreach ($otherinfoIds as $otherinfoId) {
                    $otherinfo = Mage::getModel('bs_instructorinfo/otherinfo');
                    $otherinfo->setId($otherinfoId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructorinfo')->__('Total of %d other infos were successfully deleted.', count($otherinfoIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorinfo')->__('There was an error deleting other infos.')
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
        $otherinfoIds = $this->getRequest()->getParam('otherinfo');
        if (!is_array($otherinfoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructorinfo')->__('Please select other info.')
            );
        } else {
            try {
                foreach ($otherinfoIds as $otherinfoId) {
                $otherinfo = Mage::getSingleton('bs_instructorinfo/otherinfo')->load($otherinfoId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d other infos were successfully updated.', count($otherinfoIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorinfo')->__('There was an error updating other infos.')
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
        $fileName   = 'otherinfo.csv';
        $content    = $this->getLayout()->createBlock('bs_instructorinfo/adminhtml_otherinfo_grid')
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
        $fileName   = 'otherinfo.xls';
        $content    = $this->getLayout()->createBlock('bs_instructorinfo/adminhtml_otherinfo_grid')
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
        $fileName   = 'otherinfo.xml';
        $content    = $this->getLayout()->createBlock('bs_instructorinfo/adminhtml_otherinfo_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/bs_instructor/otherinfo');
    }
}
