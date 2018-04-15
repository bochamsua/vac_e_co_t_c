<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Inquiry admin controller
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Adminhtml_Docwise_InquiryController extends BS_Docwise_Controller_Adminhtml_Docwise
{
    /**
     * init the inquiry
     *
     * @access protected
     * @return BS_Docwise_Model_Inquiry
     */
    protected function _initInquiry()
    {
        $inquiryId  = (int) $this->getRequest()->getParam('id');
        $inquiry    = Mage::getModel('bs_docwise/inquiry');
        if ($inquiryId) {
            $inquiry->load($inquiryId);
        }
        Mage::register('current_inquiry', $inquiry);
        return $inquiry;
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
        $this->_title(Mage::helper('bs_docwise')->__('Docwise'))
             ->_title(Mage::helper('bs_docwise')->__('Inquiries'));
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
     * edit inquiry - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $inquiryId    = $this->getRequest()->getParam('id');
        $inquiry      = $this->_initInquiry();
        if ($inquiryId && !$inquiry->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_docwise')->__('This inquiry no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getInquiryData(true);
        if (!empty($data)) {
            $inquiry->setData($data);
        }
        Mage::register('inquiry_data', $inquiry);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_docwise')->__('Docwise'))
             ->_title(Mage::helper('bs_docwise')->__('Inquiries'));
        if ($inquiry->getId()) {
            $this->_title($inquiry->getVaecoId());
        } else {
            $this->_title(Mage::helper('bs_docwise')->__('Add inquiry'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new inquiry action
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
     * save inquiry - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('inquiry')) {
            try {
                $inquiry = $this->_initInquiry();
                $inquiry->addData($data);
                $inquiry->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Inquiry was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $inquiry->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setInquiryData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was a problem saving the inquiry.')
                );
                Mage::getSingleton('adminhtml/session')->setInquiryData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Unable to find inquiry to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete inquiry - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $inquiry = Mage::getModel('bs_docwise/inquiry');
                $inquiry->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Inquiry was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting inquiry.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Could not find inquiry to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete inquiry - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $inquiryIds = $this->getRequest()->getParam('inquiry');
        if (!is_array($inquiryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select inquiries to delete.')
            );
        } else {
            try {
                foreach ($inquiryIds as $inquiryId) {
                    $inquiry = Mage::getModel('bs_docwise/inquiry');
                    $inquiry->setId($inquiryId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Total of %d inquiries were successfully deleted.', count($inquiryIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting inquiries.')
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
        $inquiryIds = $this->getRequest()->getParam('inquiry');
        if (!is_array($inquiryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select inquiries.')
            );
        } else {
            try {
                foreach ($inquiryIds as $inquiryId) {
                $inquiry = Mage::getSingleton('bs_docwise/inquiry')->load($inquiryId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d inquiries were successfully updated.', count($inquiryIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating inquiries.')
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
        $fileName   = 'inquiry.csv';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_inquiry_grid')
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
        $fileName   = 'inquiry.xls';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_inquiry_grid')
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
        $fileName   = 'inquiry.xml';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_inquiry_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/bs_docwise/inquiry');
    }
}
