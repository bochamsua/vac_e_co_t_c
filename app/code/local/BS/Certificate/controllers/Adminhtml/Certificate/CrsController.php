<?php
/**
 * BS_Certificate extension
 * 
 * @category       BS
 * @package        BS_Certificate
 * @copyright      Copyright (c) 2015
 */
/**
 * CRS admin controller
 *
 * @category    BS
 * @package     BS_Certificate
 * @author Bui Phong
 */
class BS_Certificate_Adminhtml_Certificate_CrsController extends BS_Certificate_Controller_Adminhtml_Certificate
{
    /**
     * init the crs
     *
     * @access protected
     * @return BS_Certificate_Model_Crs
     */
    protected function _initCrs()
    {
        $crsId  = (int) $this->getRequest()->getParam('id');
        $crs    = Mage::getModel('bs_certificate/crs');
        if ($crsId) {
            $crs->load($crsId);
        }
        Mage::register('current_crs', $crs);
        return $crs;
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
             ->_title(Mage::helper('bs_certificate')->__('CRS'));
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
     * edit crs - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $crsId    = $this->getRequest()->getParam('id');
        $crs      = $this->_initCrs();
        if ($crsId && !$crs->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_certificate')->__('This crs no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getCrsData(true);
        if (!empty($data)) {
            $crs->setData($data);
        }
        Mage::register('crs_data', $crs);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_certificate')->__('CAAV Certificate'))
             ->_title(Mage::helper('bs_certificate')->__('CRS'));
        if ($crs->getId()) {
            $this->_title($crs->getName());
        } else {
            $this->_title(Mage::helper('bs_certificate')->__('Add crs'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new crs action
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
     * save crs - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('crs')) {
            try {
                $data = $this->_filterDates($data, array('issue_date' ,'expire_date'));
                $crs = $this->_initCrs();
                $crs->addData($data);
                $crs->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_certificate')->__('CRS was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $crs->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCrsData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_certificate')->__('There was a problem saving the crs.')
                );
                Mage::getSingleton('adminhtml/session')->setCrsData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_certificate')->__('Unable to find crs to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete crs - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $crs = Mage::getModel('bs_certificate/crs');
                $crs->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_certificate')->__('CRS was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_certificate')->__('There was an error deleting crs.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_certificate')->__('Could not find crs to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete crs - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $crsIds = $this->getRequest()->getParam('crs');
        if (!is_array($crsIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_certificate')->__('Please select crs to delete.')
            );
        } else {
            try {
                foreach ($crsIds as $crsId) {
                    $crs = Mage::getModel('bs_certificate/crs');
                    $crs->setId($crsId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_certificate')->__('Total of %d crs were successfully deleted.', count($crsIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_certificate')->__('There was an error deleting crs.')
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
        $crsIds = $this->getRequest()->getParam('crs');
        if (!is_array($crsIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_certificate')->__('Please select crs.')
            );
        } else {
            try {
                foreach ($crsIds as $crsId) {
                $crs = Mage::getSingleton('bs_certificate/crs')->load($crsId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d crs were successfully updated.', count($crsIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_certificate')->__('There was an error updating crs.')
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
        $fileName   = 'crs.csv';
        $content    = $this->getLayout()->createBlock('bs_certificate/adminhtml_crs_grid')
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
        $fileName   = 'crs.xls';
        $content    = $this->getLayout()->createBlock('bs_certificate/adminhtml_crs_grid')
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
        $fileName   = 'crs.xml';
        $content    = $this->getLayout()->createBlock('bs_certificate/adminhtml_crs_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('customer/crs');
    }
}
