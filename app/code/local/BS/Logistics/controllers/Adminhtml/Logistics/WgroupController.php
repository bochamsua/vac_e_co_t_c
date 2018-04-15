<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Group admin controller
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Adminhtml_Logistics_WgroupController extends BS_Logistics_Controller_Adminhtml_Logistics
{
    /**
     * init the group
     *
     * @access protected
     * @return BS_Logistics_Model_Wgroup
     */
    protected function _initWgroup()
    {
        $wgroupId  = (int) $this->getRequest()->getParam('id');
        $wgroup    = Mage::getModel('bs_logistics/wgroup');
        if ($wgroupId) {
            $wgroup->load($wgroupId);
        }
        Mage::register('current_wgroup', $wgroup);
        return $wgroup;
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
             ->_title(Mage::helper('bs_logistics')->__('Groups'));
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
        $wgroupId    = $this->getRequest()->getParam('id');
        $wgroup      = $this->_initWgroup();
        if ($wgroupId && !$wgroup->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_logistics')->__('This group no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getWgroupData(true);
        if (!empty($data)) {
            $wgroup->setData($data);
        }
        Mage::register('wgroup_data', $wgroup);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
             ->_title(Mage::helper('bs_logistics')->__('Groups'));
        if ($wgroup->getId()) {
            $this->_title($wgroup->getName());
        } else {
            $this->_title(Mage::helper('bs_logistics')->__('Add group'));
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
        if ($data = $this->getRequest()->getPost('wgroup')) {
            try {
                $wgroup = $this->_initWgroup();
                $wgroup->addData($data);
                $wgroup->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Group was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $wgroup->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWgroupData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was a problem saving the group.')
                );
                Mage::getSingleton('adminhtml/session')->setWgroupData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Unable to find group to save.')
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
                $wgroup = Mage::getModel('bs_logistics/wgroup');
                $wgroup->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Group was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting group.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find group to delete.')
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
        $wgroupIds = $this->getRequest()->getParam('wgroup');
        if (!is_array($wgroupIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select groups to delete.')
            );
        } else {
            try {
                foreach ($wgroupIds as $wgroupId) {
                    $wgroup = Mage::getModel('bs_logistics/wgroup');
                    $wgroup->setId($wgroupId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Total of %d groups were successfully deleted.', count($wgroupIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting groups.')
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
        $wgroupIds = $this->getRequest()->getParam('wgroup');
        if (!is_array($wgroupIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select groups.')
            );
        } else {
            try {
                foreach ($wgroupIds as $wgroupId) {
                $wgroup = Mage::getSingleton('bs_logistics/wgroup')->load($wgroupId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d groups were successfully updated.', count($wgroupIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating groups.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass type change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massGrouptypeIdAction()
    {
        $wgroupIds = $this->getRequest()->getParam('wgroup');
        if (!is_array($wgroupIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select groups.')
            );
        } else {
            try {
                foreach ($wgroupIds as $wgroupId) {
                    $wgroup = Mage::getSingleton('bs_logistics/wgroup')->load($wgroupId)
                        ->setGrouptypeId($this->getRequest()->getParam('flag_grouptype_id'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d groups were successfully updated.', count($wgroupIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating groups.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }


    /**
     * mass workshop change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massWorkshopIdAction()
    {
        $wgroupIds = $this->getRequest()->getParam('wgroup');
        if (!is_array($wgroupIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select groups.')
            );
        } else {
            try {
                foreach ($wgroupIds as $wgroupId) {
                $wgroup = Mage::getSingleton('bs_logistics/wgroup')->load($wgroupId)
                    ->setWorkshopId($this->getRequest()->getParam('flag_workshop_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d groups were successfully updated.', count($wgroupIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating groups.')
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
        $fileName   = 'wgroup.csv';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_wgroup_grid')
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
        $fileName   = 'wgroup.xls';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_wgroup_grid')
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
        $fileName   = 'wgroup.xml';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_wgroup_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_logistics/workshop/wgroup');
    }
}
