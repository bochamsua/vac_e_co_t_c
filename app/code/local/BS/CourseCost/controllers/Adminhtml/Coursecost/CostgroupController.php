<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Manage Cost Group admin controller
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Adminhtml_Coursecost_CostgroupController extends BS_CourseCost_Controller_Adminhtml_CourseCost
{
    /**
     * init the manage cost group
     *
     * @access protected
     * @return BS_CourseCost_Model_Costgroup
     */
    protected function _initCostgroup()
    {
        $costgroupId  = (int) $this->getRequest()->getParam('id');
        $costgroup    = Mage::getModel('bs_coursecost/costgroup');
        if ($costgroupId) {
            $costgroup->load($costgroupId);
        }
        Mage::register('current_costgroup', $costgroup);
        return $costgroup;
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
        $this->_title(Mage::helper('bs_coursecost')->__('Course Cost'))
             ->_title(Mage::helper('bs_coursecost')->__('Manage Cost Groups'));
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
     * edit manage cost group - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $costgroupId    = $this->getRequest()->getParam('id');
        $costgroup      = $this->_initCostgroup();
        if ($costgroupId && !$costgroup->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_coursecost')->__('This manage cost group no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getCostgroupData(true);
        if (!empty($data)) {
            $costgroup->setData($data);
        }
        Mage::register('costgroup_data', $costgroup);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_coursecost')->__('Course Cost'))
             ->_title(Mage::helper('bs_coursecost')->__('Manage Cost Groups'));
        if ($costgroup->getId()) {
            $this->_title($costgroup->getGroupName());
        } else {
            $this->_title(Mage::helper('bs_coursecost')->__('Add manage cost group'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new manage cost group action
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
     * save manage cost group - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('costgroup')) {
            try {
                $costgroup = $this->_initCostgroup();
                $costgroup->addData($data);
                $costgroup->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_coursecost')->__('Manage Cost Group was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $costgroup->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCostgroupData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was a problem saving the manage cost group.')
                );
                Mage::getSingleton('adminhtml/session')->setCostgroupData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_coursecost')->__('Unable to find manage cost group to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete manage cost group - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $costgroup = Mage::getModel('bs_coursecost/costgroup');
                $costgroup->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_coursecost')->__('Manage Cost Group was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was an error deleting manage cost group.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_coursecost')->__('Could not find manage cost group to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete manage cost group - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $costgroupIds = $this->getRequest()->getParam('costgroup');
        if (!is_array($costgroupIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursecost')->__('Please select manage cost groups to delete.')
            );
        } else {
            try {
                foreach ($costgroupIds as $costgroupId) {
                    $costgroup = Mage::getModel('bs_coursecost/costgroup');
                    $costgroup->setId($costgroupId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_coursecost')->__('Total of %d manage cost groups were successfully deleted.', count($costgroupIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was an error deleting manage cost groups.')
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
        $costgroupIds = $this->getRequest()->getParam('costgroup');
        if (!is_array($costgroupIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursecost')->__('Please select manage cost groups.')
            );
        } else {
            try {
                foreach ($costgroupIds as $costgroupId) {
                $costgroup = Mage::getSingleton('bs_coursecost/costgroup')->load($costgroupId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d manage cost groups were successfully updated.', count($costgroupIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was an error updating manage cost groups.')
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
        $fileName   = 'costgroup.csv';
        $content    = $this->getLayout()->createBlock('bs_coursecost/adminhtml_costgroup_grid')
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
        $fileName   = 'costgroup.xls';
        $content    = $this->getLayout()->createBlock('bs_coursecost/adminhtml_costgroup_grid')
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
        $fileName   = 'costgroup.xml';
        $content    = $this->getLayout()->createBlock('bs_coursecost/adminhtml_costgroup_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('catalog/bs_coursecost/costgroup');
    }
}
