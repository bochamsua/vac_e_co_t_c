<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Minutes of Handover V2 admin controller
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Adminhtml_Handover_HandovertwoController extends BS_Handover_Controller_Adminhtml_Handover
{
    /**
     * init the minutes of handover v2
     *
     * @access protected
     * @return BS_Handover_Model_Handovertwo
     */
    protected function _initHandovertwo()
    {
        $handovertwoId  = (int) $this->getRequest()->getParam('id');
        $handovertwo    = Mage::getModel('bs_handover/handovertwo');
        if ($handovertwoId) {
            $handovertwo->load($handovertwoId);
        }
        Mage::register('current_handovertwo', $handovertwo);
        return $handovertwo;
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
        $this->_title(Mage::helper('bs_handover')->__('Minutes of Handover'))
             ->_title(Mage::helper('bs_handover')->__('Minutes of Handovers V2'));
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
     * edit minutes of handover v2 - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $handovertwoId    = $this->getRequest()->getParam('id');
        $handovertwo      = $this->_initHandovertwo();
        if ($handovertwoId && !$handovertwo->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_handover')->__('This minutes of handover v2 no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getHandovertwoData(true);
        if (!empty($data)) {
            $handovertwo->setData($data);
        }
        Mage::register('handovertwo_data', $handovertwo);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_handover')->__('Minutes of Handover'))
             ->_title(Mage::helper('bs_handover')->__('Minutes of Handovers V2'));
        if ($handovertwo->getId()) {
            $this->_title($handovertwo->getCourseId());
        } else {
            $this->_title(Mage::helper('bs_handover')->__('Add minutes of handover v2'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new minutes of handover v2 action
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
     * save minutes of handover v2 - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('handovertwo')) {
            try {
                $handovertwo = $this->_initHandovertwo();
                $handovertwo->addData($data);
                $handovertwo->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_handover')->__('Minutes of Handover V2 was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $handovertwo->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setHandovertwoData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_handover')->__('There was a problem saving the minutes of handover v2.')
                );
                Mage::getSingleton('adminhtml/session')->setHandovertwoData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_handover')->__('Unable to find minutes of handover v2 to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete minutes of handover v2 - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $handovertwo = Mage::getModel('bs_handover/handovertwo');
                $handovertwo->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_handover')->__('Minutes of Handover V2 was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_handover')->__('There was an error deleting minutes of handover v2.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_handover')->__('Could not find minutes of handover v2 to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete minutes of handover v2 - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $handovertwoIds = $this->getRequest()->getParam('handovertwo');
        if (!is_array($handovertwoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_handover')->__('Please select minutes of handovers v2 to delete.')
            );
        } else {
            try {
                foreach ($handovertwoIds as $handovertwoId) {
                    $handovertwo = Mage::getModel('bs_handover/handovertwo');
                    $handovertwo->setId($handovertwoId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_handover')->__('Total of %d minutes of handovers v2 were successfully deleted.', count($handovertwoIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_handover')->__('There was an error deleting minutes of handovers v2.')
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
        $handovertwoIds = $this->getRequest()->getParam('handovertwo');
        if (!is_array($handovertwoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_handover')->__('Please select minutes of handovers v2.')
            );
        } else {
            try {
                foreach ($handovertwoIds as $handovertwoId) {
                $handovertwo = Mage::getSingleton('bs_handover/handovertwo')->load($handovertwoId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d minutes of handovers v2 were successfully updated.', count($handovertwoIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_handover')->__('There was an error updating minutes of handovers v2.')
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
        $fileName   = 'handovertwo.csv';
        $content    = $this->getLayout()->createBlock('bs_handover/adminhtml_handovertwo_grid')
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
        $fileName   = 'handovertwo.xls';
        $content    = $this->getLayout()->createBlock('bs_handover/adminhtml_handovertwo_grid')
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
        $fileName   = 'handovertwo.xml';
        $content    = $this->getLayout()->createBlock('bs_handover/adminhtml_handovertwo_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_material/bs_handover/handovertwo');
    }
}
