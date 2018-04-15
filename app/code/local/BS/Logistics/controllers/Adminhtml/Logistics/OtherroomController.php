<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Other room admin controller
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Adminhtml_Logistics_OtherroomController extends BS_Logistics_Controller_Adminhtml_Logistics
{
    /**
     * init the other room
     *
     * @access protected
     * @return BS_Logistics_Model_Otherroom
     */
    protected function _initOtherroom()
    {
        $otherroomId  = (int) $this->getRequest()->getParam('id');
        $otherroom    = Mage::getModel('bs_logistics/otherroom');
        if ($otherroomId) {
            $otherroom->load($otherroomId);
        }
        Mage::register('current_otherroom', $otherroom);
        return $otherroom;
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
             ->_title(Mage::helper('bs_logistics')->__('Other room'));
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
     * edit other room - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $otherroomId    = $this->getRequest()->getParam('id');
        $otherroom      = $this->_initOtherroom();
        if ($otherroomId && !$otherroom->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_logistics')->__('This other room no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getOtherroomData(true);
        if (!empty($data)) {
            $otherroom->setData($data);
        }
        Mage::register('otherroom_data', $otherroom);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
             ->_title(Mage::helper('bs_logistics')->__('Other room'));
        if ($otherroom->getId()) {
            $this->_title($otherroom->getOtherroomName());
        } else {
            $this->_title(Mage::helper('bs_logistics')->__('Add other room'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new other room action
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
     * save other room - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('otherroom')) {
            try {
                $otherroom = $this->_initOtherroom();
                $otherroom->addData($data);
                $otherroom->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Other room was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $otherroom->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setOtherroomData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was a problem saving the other room.')
                );
                Mage::getSingleton('adminhtml/session')->setOtherroomData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Unable to find other room to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete other room - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $otherroom = Mage::getModel('bs_logistics/otherroom');
                $otherroom->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Other room was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting other room.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find other room to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete other room - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $otherroomIds = $this->getRequest()->getParam('otherroom');
        if (!is_array($otherroomIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select other room to delete.')
            );
        } else {
            try {
                foreach ($otherroomIds as $otherroomId) {
                    $otherroom = Mage::getModel('bs_logistics/otherroom');
                    $otherroom->setId($otherroomId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Total of %d other room were successfully deleted.', count($otherroomIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting other room.')
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
        $otherroomIds = $this->getRequest()->getParam('otherroom');
        if (!is_array($otherroomIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select other room.')
            );
        } else {
            try {
                foreach ($otherroomIds as $otherroomId) {
                $otherroom = Mage::getSingleton('bs_logistics/otherroom')->load($otherroomId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d other room were successfully updated.', count($otherroomIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating other room.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Location change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massOtherroomLocationAction()
    {
        $otherroomIds = $this->getRequest()->getParam('otherroom');
        if (!is_array($otherroomIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select other room.')
            );
        } else {
            try {
                foreach ($otherroomIds as $otherroomId) {
                $otherroom = Mage::getSingleton('bs_logistics/otherroom')->load($otherroomId)
                    ->setOtherroomLocation($this->getRequest()->getParam('flag_otherroom_location'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d other room were successfully updated.', count($otherroomIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating other room.')
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
        $fileName   = 'otherroom.csv';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_otherroom_grid')
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
        $fileName   = 'otherroom.xls';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_otherroom_grid')
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
        $fileName   = 'otherroom.xml';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_otherroom_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_logistics/otherroom');
    }
}
