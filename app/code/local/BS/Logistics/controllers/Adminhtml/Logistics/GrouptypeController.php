<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Type admin controller
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Adminhtml_Logistics_GrouptypeController extends BS_Logistics_Controller_Adminhtml_Logistics
{
    /**
     * init the type
     *
     * @access protected
     * @return BS_Logistics_Model_Grouptype
     */
    protected function _initGrouptype()
    {
        $grouptypeId  = (int) $this->getRequest()->getParam('id');
        $grouptype    = Mage::getModel('bs_logistics/grouptype');
        if ($grouptypeId) {
            $grouptype->load($grouptypeId);
        }
        Mage::register('current_grouptype', $grouptype);
        return $grouptype;
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
             ->_title(Mage::helper('bs_logistics')->__('Types'));
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
     * edit type - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $grouptypeId    = $this->getRequest()->getParam('id');
        $grouptype      = $this->_initGrouptype();
        if ($grouptypeId && !$grouptype->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_logistics')->__('This type no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getGrouptypeData(true);
        if (!empty($data)) {
            $grouptype->setData($data);
        }
        Mage::register('grouptype_data', $grouptype);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
             ->_title(Mage::helper('bs_logistics')->__('Types'));
        if ($grouptype->getId()) {
            $this->_title($grouptype->getName());
        } else {
            $this->_title(Mage::helper('bs_logistics')->__('Add type'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new type action
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
     * save type - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('grouptype')) {
            try {
                $grouptype = $this->_initGrouptype();
                $grouptype->addData($data);
                $grouptype->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Type was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $grouptype->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setGrouptypeData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was a problem saving the type.')
                );
                Mage::getSingleton('adminhtml/session')->setGrouptypeData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Unable to find type to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete type - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $grouptype = Mage::getModel('bs_logistics/grouptype');
                $grouptype->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Type was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting type.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find type to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete type - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $grouptypeIds = $this->getRequest()->getParam('grouptype');
        if (!is_array($grouptypeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select types to delete.')
            );
        } else {
            try {
                foreach ($grouptypeIds as $grouptypeId) {
                    $grouptype = Mage::getModel('bs_logistics/grouptype');
                    $grouptype->setId($grouptypeId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Total of %d types were successfully deleted.', count($grouptypeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting types.')
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
        $grouptypeIds = $this->getRequest()->getParam('grouptype');
        if (!is_array($grouptypeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select types.')
            );
        } else {
            try {
                foreach ($grouptypeIds as $grouptypeId) {
                $grouptype = Mage::getSingleton('bs_logistics/grouptype')->load($grouptypeId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d types were successfully updated.', count($grouptypeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating types.')
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
        $fileName   = 'grouptype.csv';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_grouptype_grid')
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
        $fileName   = 'grouptype.xls';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_grouptype_grid')
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
        $fileName   = 'grouptype.xml';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_grouptype_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_logistics/grouptype');
    }
}
