<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Group admin controller
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Adminhtml_Kst_KstgroupController extends BS_KST_Controller_Adminhtml_KST
{
    /**
     * init the group
     *
     * @access protected
     * @return BS_KST_Model_Kstgroup
     */
    protected function _initKstgroup()
    {
        $kstgroupId  = (int) $this->getRequest()->getParam('id');
        $kstgroup    = Mage::getModel('bs_kst/kstgroup');
        if ($kstgroupId) {
            $kstgroup->load($kstgroupId);
        }
        Mage::register('current_kstgroup', $kstgroup);
        return $kstgroup;
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
             ->_title(Mage::helper('bs_kst')->__('Groups'));
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
     * edit group - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $kstgroupId    = $this->getRequest()->getParam('id');
        $kstgroup      = $this->_initKstgroup();
        if ($kstgroupId && !$kstgroup->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_kst')->__('This group no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getKstgroupData(true);
        if (!empty($data)) {
            $kstgroup->setData($data);
        }
        Mage::register('kstgroup_data', $kstgroup);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_kst')->__('KST'))
             ->_title(Mage::helper('bs_kst')->__('Groups'));
        if ($kstgroup->getId()) {
            $this->_title($kstgroup->getName());
        } else {
            $this->_title(Mage::helper('bs_kst')->__('Add group'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new group action
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
     * save group - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('kstgroup')) {
            try {
                $kstgroup = $this->_initKstgroup();
                $kstgroup->addData($data);
                $kstgroup->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Group was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $kstgroup->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setKstgroupData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was a problem saving the group.')
                );
                Mage::getSingleton('adminhtml/session')->setKstgroupData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Unable to find group to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete group - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $kstgroup = Mage::getModel('bs_kst/kstgroup');
                $kstgroup->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Group was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting group.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_kst')->__('Could not find group to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete group - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $kstgroupIds = $this->getRequest()->getParam('kstgroup');
        if (!is_array($kstgroupIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select groups to delete.')
            );
        } else {
            try {
                foreach ($kstgroupIds as $kstgroupId) {
                    $kstgroup = Mage::getModel('bs_kst/kstgroup');
                    $kstgroup->setId($kstgroupId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_kst')->__('Total of %d groups were successfully deleted.', count($kstgroupIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error deleting groups.')
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
        $kstgroupIds = $this->getRequest()->getParam('kstgroup');
        if (!is_array($kstgroupIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_kst')->__('Please select groups.')
            );
        } else {
            try {
                foreach ($kstgroupIds as $kstgroupId) {
                $kstgroup = Mage::getSingleton('bs_kst/kstgroup')->load($kstgroupId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d groups were successfully updated.', count($kstgroupIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_kst')->__('There was an error updating groups.')
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
        $fileName   = 'kstgroup.csv';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstgroup_grid')
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
        $fileName   = 'kstgroup.xls';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstgroup_grid')
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
        $fileName   = 'kstgroup.xml';
        $content    = $this->getLayout()->createBlock('bs_kst/adminhtml_kstgroup_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_kst/kstgroup');
    }
}
