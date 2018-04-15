<?php
/**
 * BS_Car extension
 * 
 * @category       BS
 * @package        BS_Car
 * @copyright      Copyright (c) 2016
 */
/**
 * CAR Document admin controller
 *
 * @category    BS
 * @package     BS_Car
 * @author Bui Phong
 */
class BS_Car_Adminhtml_Car_CardocController extends BS_Car_Controller_Adminhtml_Car
{
    /**
     * init the car document
     *
     * @access protected
     * @return BS_Car_Model_Cardoc
     */
    protected function _initCardoc()
    {
        $cardocId  = (int) $this->getRequest()->getParam('id');
        $cardoc    = Mage::getModel('bs_car/cardoc');
        if ($cardocId) {
            $cardoc->load($cardocId);
        }
        Mage::register('current_cardoc', $cardoc);
        return $cardoc;
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
        $this->_title(Mage::helper('bs_car')->__('CAR'))
             ->_title(Mage::helper('bs_car')->__('CAR Documents'));
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
     * edit car document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $cardocId    = $this->getRequest()->getParam('id');
        $cardoc      = $this->_initCardoc();
        if ($cardocId && !$cardoc->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_car')->__('This car document no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getCardocData(true);
        if (!empty($data)) {
            $cardoc->setData($data);
        }
        Mage::register('cardoc_data', $cardoc);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_car')->__('CAR'))
             ->_title(Mage::helper('bs_car')->__('CAR Documents'));
        if ($cardoc->getId()) {
            $this->_title($cardoc->getDocName());
        } else {
            $this->_title(Mage::helper('bs_car')->__('Add car document'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new car document action
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
     * save car document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('cardoc')) {
            try {
                $data = $this->_filterDates($data, array('doc_date'));
                $cardoc = $this->_initCardoc();
                $cardoc->addData($data);
                $docFileName = $this->_uploadAndGetName(
                    'doc_file',
                    Mage::helper('bs_car/cardoc')->getFileBaseDir(),
                    $data
                );
                $cardoc->setData('doc_file', $docFileName);
                $cardoc->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.'.$this->getJsObjectName().'.reload(); window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_car')->__('CAR Document was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $cardoc->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['doc_file']['value'])) {
                    $data['doc_file'] = $data['doc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCardocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['doc_file']['value'])) {
                    $data['doc_file'] = $data['doc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_car')->__('There was a problem saving the car document.')
                );
                Mage::getSingleton('adminhtml/session')->setCardocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_car')->__('Unable to find car document to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete car document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $cardoc = Mage::getModel('bs_car/cardoc');
                $cardoc->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_car')->__('CAR Document was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_car')->__('There was an error deleting car document.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_car')->__('Could not find car document to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete car document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $cardocIds = $this->getRequest()->getParam('cardoc');
        if (!is_array($cardocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_car')->__('Please select car documents to delete.')
            );
        } else {
            try {
                foreach ($cardocIds as $cardocId) {
                    $cardoc = Mage::getModel('bs_car/cardoc');
                    $cardoc->setId($cardocId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_car')->__('Total of %d car documents were successfully deleted.', count($cardocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_car')->__('There was an error deleting car documents.')
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
        $cardocIds = $this->getRequest()->getParam('cardoc');
        if (!is_array($cardocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_car')->__('Please select car documents.')
            );
        } else {
            try {
                foreach ($cardocIds as $cardocId) {
                $cardoc = Mage::getSingleton('bs_car/cardoc')->load($cardocId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d car documents were successfully updated.', count($cardocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_car')->__('There was an error updating car documents.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass qa car change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massQacarIdAction()
    {
        $cardocIds = $this->getRequest()->getParam('cardoc');
        if (!is_array($cardocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_car')->__('Please select car documents.')
            );
        } else {
            try {
                foreach ($cardocIds as $cardocId) {
                $cardoc = Mage::getSingleton('bs_car/cardoc')->load($cardocId)
                    ->setQacarId($this->getRequest()->getParam('flag_qacar_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d car documents were successfully updated.', count($cardocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_car')->__('There was an error updating car documents.')
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
        $fileName   = 'cardoc.csv';
        $content    = $this->getLayout()->createBlock('bs_car/adminhtml_cardoc_grid')
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
        $fileName   = 'cardoc.xls';
        $content    = $this->getLayout()->createBlock('bs_car/adminhtml_cardoc_grid')
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
        $fileName   = 'cardoc.xml';
        $content    = $this->getLayout()->createBlock('bs_car/adminhtml_cardoc_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_car/cardoc');
    }
}
