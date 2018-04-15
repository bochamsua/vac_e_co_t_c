<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Item admin controller
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Adminhtml_Kst_KstitemController extends BS_KST_Controller_Adminhtml_KST
{
    /**
     * init the item
     *
     * @access protected
     * @return BS_KST_Model_Kstitem
     */
    protected function _initKstitem()
    {
        $kstitemId  = (int) $this->getRequest()->getParam('id');
        $kstitem    = Mage::getModel('bs_kst/kstitem');
        if ($kstitemId) {
            $kstitem->load($kstitemId);
        }
        Mage::register('current_kstitem', $kstitem);
        return $kstitem;
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
        $this->_title(Mage::helper('bs_kst')->__('KST'))
             ->_title(Mage::helper('bs_kst')->__('Items'));
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
     * edit item - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $kstitemId    = $this->getRequest()->getParam('id');
        $kstitem      = $this->_initKstitem();
        if ($kstitemId && !$kstitem->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_kst')->__('This item no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getKstitemData(true);
        if (!empty($data)) {
            $kstitem->setData($data);
        }
        Mage::register('kstitem_data', $kstitem);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_kst')->__('KST'))
             ->_title(Mage::helper('bs_kst')->__('Items'));
        if ($kstitem->getId()) {
            $this->_title($kstitem->getName());
        } else {
            $this->_title(Mage::helper('bs_kst')->__('Add item'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new item action
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
     * save item - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('kstitem')) {
            try {
                $kstitem = $this->_initKstitem();
                $kstitem->addData($data);
                $kstitem->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Item was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $kstitem->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setKstitemData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was a problem saving the item.')
                );
                Mage::getSingleton('adminhtml/session')->setKstitemData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Unable to find item to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete item - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $kstitem = Mage::getModel('bs_kst/kstitem');
                $kstitem->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Item was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting item.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Could not find item to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete item - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $kstitemIds = $this->getRequest()->getParam('kstitem');
        if (!is_array($kstitemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select items to delete.')
            );
        } else {
            try {
                foreach ($kstitemIds as $kstitemId) {
                    $kstitem = Mage::getModel('bs_kst/kstitem');
                    $kstitem->setId($kstitemId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Total of %d items were successfully deleted.', count($kstitemIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting items.')
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
        $kstitemIds = $this->getRequest()->getParam('kstitem');
        if (!is_array($kstitemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select items.')
            );
        } else {
            try {
                foreach ($kstitemIds as $kstitemId) {
                $kstitem = Mage::getSingleton('bs_kst/kstitem')->load($kstitemId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d items were successfully updated.', count($kstitemIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating items.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass subject change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massKstsubjectIdAction()
    {
        $kstitemIds = $this->getRequest()->getParam('kstitem');
        if (!is_array($kstitemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select items.')
            );
        } else {
            try {
                foreach ($kstitemIds as $kstitemId) {
                $kstitem = Mage::getSingleton('bs_kst/kstitem')->load($kstitemId)
                    ->setKstsubjectId($this->getRequest()->getParam('flag_kstsubject_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d items were successfully updated.', count($kstitemIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating items.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massChangeCaseAction()
    {
        $kstitemIds = $this->getRequest()->getParam('kstitem');
        if (!is_array($kstitemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select items.')
            );
        } else {
            try {
                $case = $this->getRequest()->getParam('changecase');
                foreach ($kstitemIds as $kstitemId) {
                    $kstitem = Mage::getSingleton('bs_kst/kstitem')->load($kstitemId);
                    if($case == 'ref'){
                        $kstitem->setRef(strtoupper($kstitem->getRef()));
                    }elseif($case == 'code'){
                        $kstitem->setTaskcode(strtoupper($kstitem->getTaskcode()));
                    }elseif($case == 'cat'){
                        $kstitem->setTaskcat(strtoupper($kstitem->getTaskcat()));
                    }


                    $kstitem->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d items were successfully updated.', count($kstitemIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating items.')
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
        $fileName   = 'kstitem.csv';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstitem_grid')
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
        $fileName   = 'kstitem.xls';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstitem_grid')
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
        $fileName   = 'kstitem.xml';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstitem_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_kst/kstitem');
    }
}
