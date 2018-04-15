<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Folder Content admin controller
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Adminhtml_Logistics_FoldercontentController extends BS_Logistics_Controller_Adminhtml_Logistics
{
    /**
     * init the folder content
     *
     * @access protected
     * @return BS_Logistics_Model_Foldercontent
     */
    protected function _initFoldercontent()
    {
        $foldercontentId  = (int) $this->getRequest()->getParam('id');
        $foldercontent    = Mage::getModel('bs_logistics/foldercontent');
        if ($foldercontentId) {
            $foldercontent->load($foldercontentId);
        }
        Mage::register('current_foldercontent', $foldercontent);
        return $foldercontent;
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
             ->_title(Mage::helper('bs_logistics')->__('Folder Contents'));
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
     * edit folder content - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $foldercontentId    = $this->getRequest()->getParam('id');
        $foldercontent      = $this->_initFoldercontent();
        if ($foldercontentId && !$foldercontent->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_logistics')->__('This folder content no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getFoldercontentData(true);
        if (!empty($data)) {
            $foldercontent->setData($data);
        }
        Mage::register('foldercontent_data', $foldercontent);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
             ->_title(Mage::helper('bs_logistics')->__('Folder Contents'));
        if ($foldercontent->getId()) {
            $this->_title($foldercontent->getTitle());
        } else {
            $this->_title(Mage::helper('bs_logistics')->__('Add folder content'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new folder content action
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
     * save folder content - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('foldercontent')) {
            try {
                $data = $this->_filterDates($data, array('updated_date'));
                $foldercontent = $this->_initFoldercontent();
                $foldercontent->addData($data);
                $foldercontent->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.foldercontentGridJsObject.reload();window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Folder Content was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $foldercontent->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFoldercontentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was a problem saving the folder content.')
                );
                Mage::getSingleton('adminhtml/session')->setFoldercontentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Unable to find folder content to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete folder content - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $foldercontent = Mage::getModel('bs_logistics/foldercontent');
                $foldercontent->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Folder Content was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting folder content.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find folder content to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete folder content - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $foldercontentIds = $this->getRequest()->getParam('foldercontent');
        if (!is_array($foldercontentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select folder contents to delete.')
            );
        } else {
            try {
                foreach ($foldercontentIds as $foldercontentId) {
                    $foldercontent = Mage::getModel('bs_logistics/foldercontent');
                    $foldercontent->setId($foldercontentId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Total of %d folder contents were successfully deleted.', count($foldercontentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting folder contents.')
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
        $foldercontentIds = $this->getRequest()->getParam('foldercontent');
        if (!is_array($foldercontentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select folder contents.')
            );
        } else {
            try {
                foreach ($foldercontentIds as $foldercontentId) {
                $foldercontent = Mage::getSingleton('bs_logistics/foldercontent')->load($foldercontentId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d folder contents were successfully updated.', count($foldercontentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating folder contents.')
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
        $fileName   = 'foldercontent.csv';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_foldercontent_grid')
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
        $fileName   = 'foldercontent.xls';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_foldercontent_grid')
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
        $fileName   = 'foldercontent.xml';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_foldercontent_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_logistics/foldercontent');
    }
}
