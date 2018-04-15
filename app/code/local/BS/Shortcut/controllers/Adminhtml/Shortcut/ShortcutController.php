<?php
/**
 * BS_Shortcut extension
 * 
 * @category       BS
 * @package        BS_Shortcut
 * @copyright      Copyright (c) 2015
 */
/**
 * Shortcut admin controller
 *
 * @category    BS
 * @package     BS_Shortcut
 * @author Bui Phong
 */
class BS_Shortcut_Adminhtml_Shortcut_ShortcutController extends BS_Shortcut_Controller_Adminhtml_Shortcut
{
    /**
     * init the shortcut
     *
     * @access protected
     * @return BS_Shortcut_Model_Shortcut
     */
    protected function _initShortcut()
    {
        $shortcutId  = (int) $this->getRequest()->getParam('id');
        $shortcut    = Mage::getModel('bs_shortcut/shortcut');
        if ($shortcutId) {
            $shortcut->load($shortcutId);
        }
        Mage::register('current_shortcut', $shortcut);
        return $shortcut;
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
        $this->_title(Mage::helper('bs_shortcut')->__('Shortcut'))
             ->_title(Mage::helper('bs_shortcut')->__('Shortcuts'));
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
     * edit shortcut - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $shortcutId    = $this->getRequest()->getParam('id');
        $shortcut      = $this->_initShortcut();
        if ($shortcutId && !$shortcut->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_shortcut')->__('This shortcut no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getShortcutData(true);
        if (!empty($data)) {
            $shortcut->setData($data);
        }
        Mage::register('shortcut_data', $shortcut);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_shortcut')->__('Shortcut'))
             ->_title(Mage::helper('bs_shortcut')->__('Shortcuts'));
        if ($shortcut->getId()) {
            $this->_title($shortcut->getShortcut());
        } else {
            $this->_title(Mage::helper('bs_shortcut')->__('Add shortcut'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new shortcut action
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
     * save shortcut - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('shortcut')) {
            try {
                $shortcut = $this->_initShortcut();
                $shortcut->addData($data);
                $shortcut->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_shortcut')->__('Shortcut was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $shortcut->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setShortcutData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_shortcut')->__('There was a problem saving the shortcut.')
                );
                Mage::getSingleton('adminhtml/session')->setShortcutData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_shortcut')->__('Unable to find shortcut to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete shortcut - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $shortcut = Mage::getModel('bs_shortcut/shortcut');
                $shortcut->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_shortcut')->__('Shortcut was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_shortcut')->__('There was an error deleting shortcut.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_shortcut')->__('Could not find shortcut to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete shortcut - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $shortcutIds = $this->getRequest()->getParam('shortcut');
        if (!is_array($shortcutIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_shortcut')->__('Please select shortcuts to delete.')
            );
        } else {
            try {
                foreach ($shortcutIds as $shortcutId) {
                    $shortcut = Mage::getModel('bs_shortcut/shortcut');
                    $shortcut->setId($shortcutId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_shortcut')->__('Total of %d shortcuts were successfully deleted.', count($shortcutIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_shortcut')->__('There was an error deleting shortcuts.')
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
        $shortcutIds = $this->getRequest()->getParam('shortcut');
        if (!is_array($shortcutIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_shortcut')->__('Please select shortcuts.')
            );
        } else {
            try {
                foreach ($shortcutIds as $shortcutId) {
                $shortcut = Mage::getSingleton('bs_shortcut/shortcut')->load($shortcutId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d shortcuts were successfully updated.', count($shortcutIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_shortcut')->__('There was an error updating shortcuts.')
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
        $fileName   = 'shortcut.csv';
        $content    = $this->getLayout()->createBlock('bs_shortcut/adminhtml_shortcut_grid')
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
        $fileName   = 'shortcut.xls';
        $content    = $this->getLayout()->createBlock('bs_shortcut/adminhtml_shortcut_grid')
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
        $fileName   = 'shortcut.xml';
        $content    = $this->getLayout()->createBlock('bs_shortcut/adminhtml_shortcut_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('cms/shortcut');
    }
}
