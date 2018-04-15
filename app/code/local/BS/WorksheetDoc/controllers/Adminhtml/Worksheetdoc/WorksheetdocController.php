<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document admin controller
 *
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
class BS_WorksheetDoc_Adminhtml_Worksheetdoc_WorksheetdocController extends BS_WorksheetDoc_Controller_Adminhtml_WorksheetDoc
{
    /**
     * init the worksheet doc
     *
     * @access protected
     * @return BS_WorksheetDoc_Model_Worksheetdoc
     */
    protected function _initWorksheetdoc()
    {
        $worksheetdocId  = (int) $this->getRequest()->getParam('id');
        $worksheetdoc    = Mage::getModel('bs_worksheetdoc/worksheetdoc');
        if ($worksheetdocId) {
            $worksheetdoc->load($worksheetdocId);
        }
        Mage::register('current_worksheetdoc', $worksheetdoc);
        return $worksheetdoc;
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
        $this->_title(Mage::helper('bs_worksheetdoc')->__('Worksheet Documents'))
             ->_title(Mage::helper('bs_worksheetdoc')->__('Worksheet Documents'));
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
     * edit worksheet doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $worksheetdocId    = $this->getRequest()->getParam('id');
        $worksheetdoc      = $this->_initWorksheetdoc();
        if ($worksheetdocId && !$worksheetdoc->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_worksheetdoc')->__('This worksheet doc no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getWorksheetdocData(true);
        if (!empty($data)) {
            $worksheetdoc->setData($data);
        }
        Mage::register('worksheetdoc_data', $worksheetdoc);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_worksheetdoc')->__('Worksheet Documents'))
             ->_title(Mage::helper('bs_worksheetdoc')->__('Worksheet Documents'));
        if ($worksheetdoc->getId()) {
            $this->_title($worksheetdoc->getWsdocName());
        } else {
            $this->_title(Mage::helper('bs_worksheetdoc')->__('Add worksheet doc'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new worksheet doc action
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
     * save worksheet doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('worksheetdoc')) {
            try {
                $worksheetdoc = $this->_initWorksheetdoc();
                $worksheetdoc->addData($data);
                $wsdocFileName = $this->_uploadAndGetName(
                    'wsdoc_file',
                    Mage::helper('bs_worksheetdoc/worksheetdoc')->getFileBaseDir(),
                    $data
                );
                $worksheetdoc->setData('wsdoc_file', $wsdocFileName);
                $worksheets = $this->getRequest()->getPost('worksheets', -1);
                if ($worksheets != -1) {
                    $worksheetdoc->setWorksheetsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($worksheets));
                }else {
                    if(isset($data['hidden_worksheet_id']) && $data['hidden_worksheet_id'] > 0){
                        $worksheetdoc->setWorksheetsData(
                            array(
                                $data['hidden_worksheet_id'] => array(
                                    'position' => ""
                                )
                            )
                        );
                    }
                }
                $worksheetdoc->save();

                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.location.reload(); window.close()</script>';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_worksheetdoc')->__('Worksheet Document was successfully saved %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $worksheetdoc->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['wsdoc_file']['value'])) {
                    $data['wsdoc_file'] = $data['wsdoc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWorksheetdocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['wsdoc_file']['value'])) {
                    $data['wsdoc_file'] = $data['wsdoc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheetdoc')->__('There was a problem saving the worksheet doc.')
                );
                Mage::getSingleton('adminhtml/session')->setWorksheetdocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_worksheetdoc')->__('Unable to find worksheet doc to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete worksheet doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $worksheetdoc = Mage::getModel('bs_worksheetdoc/worksheetdoc');
                $worksheetdoc->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_worksheetdoc')->__('Worksheet Document was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheetdoc')->__('There was an error deleting worksheet doc.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_worksheetdoc')->__('Could not find worksheet doc to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete worksheet doc - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $worksheetdocIds = $this->getRequest()->getParam('worksheetdoc');
        if (!is_array($worksheetdocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_worksheetdoc')->__('Please select worksheet docs to delete.')
            );
        } else {
            try {
                foreach ($worksheetdocIds as $worksheetdocId) {
                    $worksheetdoc = Mage::getModel('bs_worksheetdoc/worksheetdoc');
                    $worksheetdoc->setId($worksheetdocId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_worksheetdoc')->__('Total of %d worksheet docs were successfully deleted.', count($worksheetdocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheetdoc')->__('There was an error deleting worksheet docs.')
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
        $worksheetdocIds = $this->getRequest()->getParam('worksheetdoc');
        if (!is_array($worksheetdocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_worksheetdoc')->__('Please select worksheet docs.')
            );
        } else {
            try {
                foreach ($worksheetdocIds as $worksheetdocId) {
                $worksheetdoc = Mage::getSingleton('bs_worksheetdoc/worksheetdoc')->load($worksheetdocId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d worksheet docs were successfully updated.', count($worksheetdocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheetdoc')->__('There was an error updating worksheet docs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Document Type change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massWsdocTypeAction()
    {
        $worksheetdocIds = $this->getRequest()->getParam('worksheetdoc');
        if (!is_array($worksheetdocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_worksheetdoc')->__('Please select worksheet docs.')
            );
        } else {
            try {
                foreach ($worksheetdocIds as $worksheetdocId) {
                $worksheetdoc = Mage::getSingleton('bs_worksheetdoc/worksheetdoc')->load($worksheetdocId)
                    ->setWsdocType($this->getRequest()->getParam('flag_wsdoc_type'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d worksheet docs were successfully updated.', count($worksheetdocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheetdoc')->__('There was an error updating worksheet docs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Revision change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massWsdocRevAction()
    {
        $worksheetdocIds = $this->getRequest()->getParam('worksheetdoc');
        if (!is_array($worksheetdocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_worksheetdoc')->__('Please select worksheet docs.')
            );
        } else {
            try {
                foreach ($worksheetdocIds as $worksheetdocId) {
                $worksheetdoc = Mage::getSingleton('bs_worksheetdoc/worksheetdoc')->load($worksheetdocId)
                    ->setWsdocRev($this->getRequest()->getParam('flag_wsdoc_rev'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d worksheet docs were successfully updated.', count($worksheetdocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheetdoc')->__('There was an error updating worksheet docs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get grid of worksheets action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function worksheetsAction()
    {
        $this->_initWorksheetdoc();
        $this->loadLayout();
        $this->getLayout()->getBlock('worksheetdoc.edit.tab.worksheet')
            ->setWorksheetdocWorksheets($this->getRequest()->getPost('worksheetdoc_worksheets', null));
        $this->renderLayout();
    }

    /**
     * get grid of worksheets action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function worksheetsgridAction()
    {
        $this->_initWorksheetdoc();
        $this->loadLayout();
        $this->getLayout()->getBlock('worksheetdoc.edit.tab.worksheet')
            ->setWorksheetdocWorksheets($this->getRequest()->getPost('worksheetdoc_worksheets', null));
        $this->renderLayout();
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
        $fileName   = 'worksheetdoc.csv';
        $content    = $this->getLayout()->createBlock('bs_worksheetdoc/adminhtml_worksheetdoc_grid')
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
        $fileName   = 'worksheetdoc.xls';
        $content    = $this->getLayout()->createBlock('bs_worksheetdoc/adminhtml_worksheetdoc_grid')
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
        $fileName   = 'worksheetdoc.xml';
        $content    = $this->getLayout()->createBlock('bs_worksheetdoc/adminhtml_worksheetdoc_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_material/worksheetdoc');
    }
}
