<?php
/**
 * BS_StaffInfo extension
 * 
 * @category       BS
 * @package        BS_StaffInfo
 * @copyright      Copyright (c) 2015
 */
/**
 * Related Working admin controller
 *
 * @category    BS
 * @package     BS_StaffInfo
 * @author Bui Phong
 */
class BS_StaffInfo_Adminhtml_Staffinfo_WorkingController extends BS_StaffInfo_Controller_Adminhtml_StaffInfo
{
    /**
     * init the related working
     *
     * @access protected
     * @return BS_StaffInfo_Model_Working
     */
    protected function _initWorking()
    {
        $workingId  = (int) $this->getRequest()->getParam('id');
        $working    = Mage::getModel('bs_staffinfo/working');
        if ($workingId) {
            $working->load($workingId);
        }
        Mage::register('current_working', $working);
        return $working;
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
             ->_title(Mage::helper('bs_staffinfo')->__('Related Working'));
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
     * edit related working - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $workingId    = $this->getRequest()->getParam('id');
        $staffId    = $this->getRequest()->getParam('staff_id', false);
        $working      = $this->_initWorking();
        if ($workingId && !$working->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_staffinfo')->__('This related working no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }

        if(!$staffId && !$working->getStaffId()){
            $this->_getSession()->addError(
                Mage::helper('bs_staffinfo')->__('Please specify the Staff.')
            );
            $this->_redirect('*/*/');
            return;

        }

        $data = Mage::getSingleton('adminhtml/session')->getWorkingData(true);
        if (!empty($data)) {
            $working->setData($data);
        }
        Mage::register('working_data', $working);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_staffinfo')->__('Related Info'))
             ->_title(Mage::helper('bs_staffinfo')->__('Related Working'));
        if ($working->getId()) {
            $this->_title($working->getDivisionDept());
        } else {
            $this->_title(Mage::helper('bs_staffinfo')->__('Add related working'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new related working action
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
     * save related working - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('working')) {
            try {
                $data = $this->_filterDates($data, array('start_date' ,'end_date'));
                $working = $this->_initWorking();

                if($data['import'] != ''){
                    $import = $data['import'];
                    $import = explode("\r\n", $import);
                    $instructorId = $data['staff_id'];
                    foreach ($import as $line) {
                        $item = explode("\t", $line);
                        $count = count($item);
                        if($count > 1){

                            $startDate = trim($item[0]);
                            $startDate = str_replace(" ", "", $startDate);
                            $startDate = DateTime::createFromFormat('d-M-y', $startDate)->format('Y-m-d');

                            $endDate = trim($item[1]);
                            $endDate = str_replace(" ", "", $endDate);
                            $endDate = DateTime::createFromFormat('d-M-y', $endDate)->format('Y-m-d');

                            $workingPlace = trim($item[2]);
                            $workingAs = trim($item[3]);
                            $workingOn = trim($item[4]);
                            $remark = trim($item[5]);

                            $info = Mage::getModel('bs_staffinfo/working');
                            $info->setStaffId($instructorId)
                                ->setStartDate($startDate)
                                ->setEndDate($endDate)
                                ->setWorkingPlace($workingPlace)
                                ->setWorkingAs($workingAs)
                                ->setWorkingOn($workingOn)
                                ->setRemark($remark)
                            ;
                            $info->save();

                        }

                    }

                }else {
                    $working->addData($data);
                    $working->save();
                }


                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.working_gridJsObject.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_staffinfo')->__('Related Working was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $working->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWorkingData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_staffinfo')->__('There was a problem saving the related working.')
                );
                Mage::getSingleton('adminhtml/session')->setWorkingData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_staffinfo')->__('Unable to find related working to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete related working - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $working = Mage::getModel('bs_staffinfo/working');
                $working->setId($this->getRequest()->getParam('id'))->delete();

                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.working_gridJsObject.reload(); window.close()</script>';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_staffinfo')->__('Related Working was successfully deleted. %s', $add)
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_staffinfo')->__('There was an error deleting related working.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_staffinfo')->__('Could not find related working to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete related working - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $workingIds = $this->getRequest()->getParam('working');
        if (!is_array($workingIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_staffinfo')->__('Please select related working to delete.')
            );
        } else {
            try {
                foreach ($workingIds as $workingId) {
                    $working = Mage::getModel('bs_staffinfo/working');
                    $working->setId($workingId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_staffinfo')->__('Total of %d related working were successfully deleted.', count($workingIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_staffinfo')->__('There was an error deleting related working.')
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
        $workingIds = $this->getRequest()->getParam('working');
        if (!is_array($workingIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_staffinfo')->__('Please select related working.')
            );
        } else {
            try {
                foreach ($workingIds as $workingId) {
                $working = Mage::getSingleton('bs_staffinfo/working')->load($workingId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d related working were successfully updated.', count($workingIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_staffinfo')->__('There was an error updating related working.')
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
        $fileName   = 'working.csv';
        $content    = $this->getLayout()->createBlock('bs_staffinfo/adminhtml_working_grid')
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
        $fileName   = 'working.xls';
        $content    = $this->getLayout()->createBlock('bs_staffinfo/adminhtml_working_grid')
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
        $fileName   = 'working.xml';
        $content    = $this->getLayout()->createBlock('bs_staffinfo/adminhtml_working_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('customer/working');
    }
}
