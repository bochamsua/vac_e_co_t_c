<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Administrative Document admin controller
 *
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Adminhtml_Administrativedoc_AdministrativedocumentController extends BS_AdministrativeDoc_Controller_Adminhtml_AdministrativeDoc
{
    /**
     * init the administrative document
     *
     * @access protected
     * @return BS_AdministrativeDoc_Model_Administrativedocument
     */
    protected function _initAdministrativedocument()
    {
        $administrativedocumentId  = (int) $this->getRequest()->getParam('id');
        $administrativedocument    = Mage::getModel('bs_administrativedoc/administrativedocument');
        if ($administrativedocumentId) {
            $administrativedocument->load($administrativedocumentId);
        }
        Mage::register('current_administrativedocument', $administrativedocument);
        return $administrativedocument;
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
        $this->_title(Mage::helper('bs_administrativedoc')->__('Administrative Document'))
             ->_title(Mage::helper('bs_administrativedoc')->__('Administrative Documents'));
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
     * edit administrative document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $administrativedocumentId    = $this->getRequest()->getParam('id');
        $administrativedocument      = $this->_initAdministrativedocument();
        if ($administrativedocumentId && !$administrativedocument->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_administrativedoc')->__('This administrative document no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getAdministrativedocumentData(true);
        if (!empty($data)) {
            $administrativedocument->setData($data);
        }
        Mage::register('administrativedocument_data', $administrativedocument);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_administrativedoc')->__('Administrative Document'))
             ->_title(Mage::helper('bs_administrativedoc')->__('Administrative Documents'));
        if ($administrativedocument->getId()) {
            $this->_title($administrativedocument->getDocName());
        } else {
            $this->_title(Mage::helper('bs_administrativedoc')->__('Add document'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new administrative document action
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
     * save administrative document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('administrativedocument')) {
            try {
                $data = $this->_filterDates($data, array('doc_date'));
                $administrativedocument = $this->_initAdministrativedocument();
                $administrativedocument->addData($data);
                $docFileName = $this->_uploadAndGetName(
                    'doc_file',
                    Mage::helper('bs_administrativedoc/administrativedocument')->getFileBaseDir(),
                    $data
                );
                $administrativedocument->setData('doc_file', $docFileName);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $administrativedocument->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }
                $administrativedocument->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_administrativedoc')->__('Administrative Document was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $administrativedocument->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['doc_file']['value'])) {
                    $data['doc_file'] = $data['doc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setAdministrativedocumentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['doc_file']['value'])) {
                    $data['doc_file'] = $data['doc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_administrativedoc')->__('There was a problem saving the administrative document.')
                );
                Mage::getSingleton('adminhtml/session')->setAdministrativedocumentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_administrativedoc')->__('Unable to find administrative document to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete administrative document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $administrativedocument = Mage::getModel('bs_administrativedoc/administrativedocument');
                $administrativedocument->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_administrativedoc')->__('Administrative Document was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_administrativedoc')->__('There was an error deleting administrative document.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_administrativedoc')->__('Could not find administrative document to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete administrative document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $administrativedocumentIds = $this->getRequest()->getParam('administrativedocument');
        if (!is_array($administrativedocumentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_administrativedoc')->__('Please select administrative documents to delete.')
            );
        } else {
            try {
                foreach ($administrativedocumentIds as $administrativedocumentId) {
                    $administrativedocument = Mage::getModel('bs_administrativedoc/administrativedocument');
                    $administrativedocument->setId($administrativedocumentId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_administrativedoc')->__('Total of %d administrative documents were successfully deleted.', count($administrativedocumentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_administrativedoc')->__('There was an error deleting administrative documents.')
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
        $administrativedocumentIds = $this->getRequest()->getParam('administrativedocument');
        if (!is_array($administrativedocumentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_administrativedoc')->__('Please select administrative documents.')
            );
        } else {
            try {
                foreach ($administrativedocumentIds as $administrativedocumentId) {
                $administrativedocument = Mage::getSingleton('bs_administrativedoc/administrativedocument')->load($administrativedocumentId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d administrative documents were successfully updated.', count($administrativedocumentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_administrativedoc')->__('There was an error updating administrative documents.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function productsAction()
    {
        $this->_initAdministrativedocument();
        $this->loadLayout();
        $this->getLayout()->getBlock('administrativedocument.edit.tab.product')
            ->setAdministrativedocumentProducts($this->getRequest()->getPost('administrativedocument_products', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function productsgridAction()
    {
        $this->_initAdministrativedocument();
        $this->loadLayout();
        $this->getLayout()->getBlock('administrativedocument.edit.tab.product')
            ->setAdministrativedocumentProducts($this->getRequest()->getPost('administrativedocument_products', null));
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
        $fileName   = 'administrativedocument.csv';
        $content    = $this->getLayout()->createBlock('bs_administrativedoc/adminhtml_administrativedocument_grid')
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
        $fileName   = 'administrativedocument.xls';
        $content    = $this->getLayout()->createBlock('bs_administrativedoc/adminhtml_administrativedocument_grid')
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
        $fileName   = 'administrativedocument.xml';
        $content    = $this->getLayout()->createBlock('bs_administrativedoc/adminhtml_administrativedocument_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_material/administrativedocument');
    }
}
