<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Cabinet admin controller
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Adminhtml_Logistics_FilecabinetController extends BS_Logistics_Controller_Adminhtml_Logistics
{
    /**
     * init the file cabinet
     *
     * @access protected
     * @return BS_Logistics_Model_Filecabinet
     */
    protected function _initFilecabinet()
    {
        $filecabinetId  = (int) $this->getRequest()->getParam('id');
        $filecabinet    = Mage::getModel('bs_logistics/filecabinet');
        if ($filecabinetId) {
            $filecabinet->load($filecabinetId);
        }
        Mage::register('current_filecabinet', $filecabinet);
        return $filecabinet;
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
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
             ->_title(Mage::helper('bs_logistics')->__('File Cabinets'));
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
     * edit file cabinet - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $filecabinetId    = $this->getRequest()->getParam('id');
        $filecabinet      = $this->_initFilecabinet();
        if ($filecabinetId && !$filecabinet->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_logistics')->__('This file cabinet no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getFilecabinetData(true);
        if (!empty($data)) {
            $filecabinet->setData($data);
        }
        Mage::register('filecabinet_data', $filecabinet);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
             ->_title(Mage::helper('bs_logistics')->__('File Cabinets'));
        if ($filecabinet->getId()) {
            $this->_title($filecabinet->getFilecabinetName());
        } else {
            $this->_title(Mage::helper('bs_logistics')->__('Add file cabinet'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new file cabinet action
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
     * save file cabinet - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('filecabinet')) {
            try {
                $filecabinet = $this->_initFilecabinet();
                $filecabinet->addData($data);
                $filecabinet->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('File Cabinet was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $filecabinet->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFilecabinetData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was a problem saving the file cabinet.')
                );
                Mage::getSingleton('adminhtml/session')->setFilecabinetData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Unable to find file cabinet to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete file cabinet - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $filecabinet = Mage::getModel('bs_logistics/filecabinet');
                $filecabinet->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('File Cabinet was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting file cabinet.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find file cabinet to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete file cabinet - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $filecabinetIds = $this->getRequest()->getParam('filecabinet');
        if (!is_array($filecabinetIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select file cabinets to delete.')
            );
        } else {
            try {
                foreach ($filecabinetIds as $filecabinetId) {
                    $filecabinet = Mage::getModel('bs_logistics/filecabinet');
                    $filecabinet->setId($filecabinetId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Total of %d file cabinets were successfully deleted.', count($filecabinetIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting file cabinets.')
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
        $filecabinetIds = $this->getRequest()->getParam('filecabinet');
        if (!is_array($filecabinetIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select file cabinets.')
            );
        } else {
            try {
                foreach ($filecabinetIds as $filecabinetId) {
                $filecabinet = Mage::getSingleton('bs_logistics/filecabinet')->load($filecabinetId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d file cabinets were successfully updated.', count($filecabinetIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating file cabinets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass other room change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massOtherroomIdAction()
    {
        $filecabinetIds = $this->getRequest()->getParam('filecabinet');
        if (!is_array($filecabinetIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select file cabinets.')
            );
        } else {
            try {
                foreach ($filecabinetIds as $filecabinetId) {
                $filecabinet = Mage::getSingleton('bs_logistics/filecabinet')->load($filecabinetId)
                    ->setOtherroomId($this->getRequest()->getParam('flag_otherroom_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d file cabinets were successfully updated.', count($filecabinetIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating file cabinets.')
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
        $fileName   = 'filecabinet.csv';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_filecabinet_grid')
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
        $fileName   = 'filecabinet.xls';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_filecabinet_grid')
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
        $fileName   = 'filecabinet.xml';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_filecabinet_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_logistics/filecabinet');
    }
}
