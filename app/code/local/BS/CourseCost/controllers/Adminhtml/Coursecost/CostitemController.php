<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Manage Group Items admin controller
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Adminhtml_Coursecost_CostitemController extends BS_CourseCost_Controller_Adminhtml_CourseCost
{
    /**
     * init the manage group items
     *
     * @access protected
     * @return BS_CourseCost_Model_Costitem
     */
    protected function _initCostitem()
    {
        $costitemId  = (int) $this->getRequest()->getParam('id');
        $costitem    = Mage::getModel('bs_coursecost/costitem');
        if ($costitemId) {
            $costitem->load($costitemId);
        }
        Mage::register('current_costitem', $costitem);
        return $costitem;
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
             ->_title(Mage::helper('bs_coursecost')->__('Manage Group Items'));
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
     * edit manage group items - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $costitemId    = $this->getRequest()->getParam('id');
        $costitem      = $this->_initCostitem();
        if ($costitemId && !$costitem->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_coursecost')->__('This manage group items no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getCostitemData(true);
        if (!empty($data)) {
            $costitem->setData($data);
        }
        Mage::register('costitem_data', $costitem);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_coursecost')->__('Course Cost'))
             ->_title(Mage::helper('bs_coursecost')->__('Manage Group Items'));
        if ($costitem->getId()) {
            $this->_title($costitem->getItemName());
        } else {
            $this->_title(Mage::helper('bs_coursecost')->__('Add manage group items'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new manage group items action
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
     * save manage group items - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('costitem')) {
            try {
                $data = $this->_filterDates($data, array('update_date'));
                $costitem = $this->_initCostitem();
                $costitem->addData($data);
                $costitem->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_coursecost')->__('Manage Group Items was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $costitem->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCostitemData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was a problem saving the manage group items.')
                );
                Mage::getSingleton('adminhtml/session')->setCostitemData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_coursecost')->__('Unable to find manage group items to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete manage group items - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $costitem = Mage::getModel('bs_coursecost/costitem');
                $costitem->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_coursecost')->__('Manage Group Items was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was an error deleting manage group items.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_coursecost')->__('Could not find manage group items to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete manage group items - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $costitemIds = $this->getRequest()->getParam('costitem');
        if (!is_array($costitemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursecost')->__('Please select manage group items to delete.')
            );
        } else {
            try {
                foreach ($costitemIds as $costitemId) {
                    $costitem = Mage::getModel('bs_coursecost/costitem');
                    $costitem->setId($costitemId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_coursecost')->__('Total of %d manage group items were successfully deleted.', count($costitemIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was an error deleting manage group items.')
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
        $costitemIds = $this->getRequest()->getParam('costitem');
        if (!is_array($costitemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursecost')->__('Please select manage group items.')
            );
        } else {
            try {
                foreach ($costitemIds as $costitemId) {
                $costitem = Mage::getSingleton('bs_coursecost/costitem')->load($costitemId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d manage group items were successfully updated.', count($costitemIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was an error updating manage group items.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass manage cost group change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCostgroupIdAction()
    {
        $costitemIds = $this->getRequest()->getParam('costitem');
        if (!is_array($costitemIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_coursecost')->__('Please select manage group items.')
            );
        } else {
            try {
                foreach ($costitemIds as $costitemId) {
                $costitem = Mage::getSingleton('bs_coursecost/costitem')->load($costitemId)
                    ->setCostgroupId($this->getRequest()->getParam('flag_costgroup_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d manage group items were successfully updated.', count($costitemIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_coursecost')->__('There was an error updating manage group items.')
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
        $fileName   = 'costitem.csv';
        $content    = $this->getLayout()->createBlock('bs_coursecost/adminhtml_costitem_grid')
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
        $fileName   = 'costitem.xls';
        $content    = $this->getLayout()->createBlock('bs_coursecost/adminhtml_costitem_grid')
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
        $fileName   = 'costitem.xml';
        $content    = $this->getLayout()->createBlock('bs_coursecost/adminhtml_costitem_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('catalog/bs_coursecost/costitem');
    }
}
