<?php
/**
 * BS_Certificate extension
 * 
 * @category       BS
 * @package        BS_Certificate
 * @copyright      Copyright (c) 2015
 */
/**
 * Certificate admin controller
 *
 * @category    BS
 * @package     BS_Certificate
 * @author Bui Phong
 */
class BS_Certificate_Adminhtml_Certificate_CertificateController extends BS_Certificate_Controller_Adminhtml_Certificate
{
    /**
     * init the certificate
     *
     * @access protected
     * @return BS_Certificate_Model_Certificate
     */
    protected function _initCertificate()
    {
        $certificateId  = (int) $this->getRequest()->getParam('id');
        $certificate    = Mage::getModel('bs_certificate/certificate');
        if ($certificateId) {
            $certificate->load($certificateId);
        }
        Mage::register('current_certificate', $certificate);
        return $certificate;
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
        $this->_title(Mage::helper('bs_certificate')->__('CAAV Certificate'))
             ->_title(Mage::helper('bs_certificate')->__('Certificates'));
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
     * edit certificate - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $certificateId    = $this->getRequest()->getParam('id');
        $certificate      = $this->_initCertificate();
        if ($certificateId && !$certificate->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_certificate')->__('This certificate no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getCertificateData(true);
        if (!empty($data)) {
            $certificate->setData($data);
        }
        Mage::register('certificate_data', $certificate);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_certificate')->__('CAAV Certificate'))
             ->_title(Mage::helper('bs_certificate')->__('Certificates'));
        if ($certificate->getId()) {
            $this->_title($certificate->getStaffName());
        } else {
            $this->_title(Mage::helper('bs_certificate')->__('Add certificate'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new certificate action
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
     * save certificate - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('certificate')) {
            try {
                $data = $this->_filterDates($data, array('issue_date' ,'expire_date'));
                $certificate = $this->_initCertificate();
                $certificate->addData($data);
                $certificate->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_certificate')->__('Certificate was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $certificate->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCertificateData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_certificate')->__('There was a problem saving the certificate.')
                );
                Mage::getSingleton('adminhtml/session')->setCertificateData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_certificate')->__('Unable to find certificate to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete certificate - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $certificate = Mage::getModel('bs_certificate/certificate');
                $certificate->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_certificate')->__('Certificate was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_certificate')->__('There was an error deleting certificate.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_certificate')->__('Could not find certificate to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete certificate - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $certificateIds = $this->getRequest()->getParam('certificate');
        if (!is_array($certificateIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_certificate')->__('Please select certificates to delete.')
            );
        } else {
            try {
                foreach ($certificateIds as $certificateId) {
                    $certificate = Mage::getModel('bs_certificate/certificate');
                    $certificate->setId($certificateId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_certificate')->__('Total of %d certificates were successfully deleted.', count($certificateIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_certificate')->__('There was an error deleting certificates.')
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
        $certificateIds = $this->getRequest()->getParam('certificate');
        if (!is_array($certificateIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_certificate')->__('Please select certificates.')
            );
        } else {
            try {
                foreach ($certificateIds as $certificateId) {
                $certificate = Mage::getSingleton('bs_certificate/certificate')->load($certificateId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d certificates were successfully updated.', count($certificateIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_certificate')->__('There was an error updating certificates.')
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
        $fileName   = 'certificate.csv';
        $content    = $this->getLayout()->createBlock('bs_certificate/adminhtml_certificate_grid')
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
        $fileName   = 'certificate.xls';
        $content    = $this->getLayout()->createBlock('bs_certificate/adminhtml_certificate_grid')
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
        $fileName   = 'certificate.xml';
        $content    = $this->getLayout()->createBlock('bs_certificate/adminhtml_certificate_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('customer/certificate');
    }
}
