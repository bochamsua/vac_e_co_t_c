<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Other\'s Course Document admin controller
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Adminhtml_Otherdoc_OtherdocController extends BS_Otherdoc_Controller_Adminhtml_Otherdoc
{
    /**
     * init the other\'s course document
     *
     * @access protected
     * @return BS_Otherdoc_Model_Otherdoc
     */
    protected function _initOtherdoc()
    {
        $otherdocId  = (int) $this->getRequest()->getParam('id');
        $otherdoc    = Mage::getModel('bs_otherdoc/otherdoc');
        if ($otherdocId) {
            $otherdoc->load($otherdocId);
        }
        Mage::register('current_otherdoc', $otherdoc);
        return $otherdoc;
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
        $this->_title(Mage::helper('bs_otherdoc')->__('Other\'s Course Document'))
             ->_title(Mage::helper('bs_otherdoc')->__('Other\'s Course Documents'));
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
     * edit other\'s course document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $otherdocId    = $this->getRequest()->getParam('id');
        $otherdoc      = $this->_initOtherdoc();
        if ($otherdocId && !$otherdoc->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_otherdoc')->__('This other\'s course document no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getOtherdocData(true);
        if (!empty($data)) {
            $otherdoc->setData($data);
        }
        Mage::register('otherdoc_data', $otherdoc);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_otherdoc')->__('Other\'s Course Document'))
             ->_title(Mage::helper('bs_otherdoc')->__('Other\'s Course Documents'));
        if ($otherdoc->getId()) {
            $this->_title($otherdoc->getOtherdocName());
        } else {
            $this->_title(Mage::helper('bs_otherdoc')->__('Add other\'s course document'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new other\'s course document action
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
     * save other\'s course document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('otherdoc')) {
            try {
                $data = $this->_filterDates($data, array('otherdoc_date'));
                $otherdoc = $this->_initOtherdoc();
                $otherdoc->addData($data);
                $otherdocFileName = $this->_uploadAndGetName(
                    'otherdoc_file',
                    Mage::helper('bs_otherdoc/otherdoc')->getFileBaseDir(),
                    $data
                );
                $otherdoc->setData('otherdoc_file', $otherdocFileName);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $otherdoc->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }else {
                    if(isset($data['hidden_course_id']) && $data['hidden_course_id'] > 0){
                        $otherdoc->setProductsData(
                            array(
                                $data['hidden_course_id'] => array(
                                    'position' => ""
                                )
                            )
                        );


                    }
                }
                $otherdoc->save();
                $add = '';
                if($this->getRequest()->getParam('popup')){
                    //$add = '<script>window.opener.otherdoc_gridJsObject.reload(); window.close()</script>';
                    $add = '<script>window.opener.document.location.href = \''.$this->getUrl('*/catalog_product/edit', array('id'=>$data['hidden_course_id'], 'back'=>'edit', 'tab'=>'product_info_tabs_otherdocs')).'\'; window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_otherdoc')->__('Other\'s Course Document was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $otherdoc->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['otherdoc_file']['value'])) {
                    $data['otherdoc_file'] = $data['otherdoc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setOtherdocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['otherdoc_file']['value'])) {
                    $data['otherdoc_file'] = $data['otherdoc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_otherdoc')->__('There was a problem saving the other\'s course document.')
                );
                Mage::getSingleton('adminhtml/session')->setOtherdocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_otherdoc')->__('Unable to find other\'s course document to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete other\'s course document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $otherdoc = Mage::getModel('bs_otherdoc/otherdoc');
                $otherdoc->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_otherdoc')->__('Other\'s Course Document was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_otherdoc')->__('There was an error deleting other\'s course document.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_otherdoc')->__('Could not find other\'s course document to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete other\'s course document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $otherdocIds = $this->getRequest()->getParam('otherdoc');
        if (!is_array($otherdocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_otherdoc')->__('Please select other\'s course documents to delete.')
            );
        } else {
            try {
                foreach ($otherdocIds as $otherdocId) {
                    $otherdoc = Mage::getModel('bs_otherdoc/otherdoc');
                    $otherdoc->setId($otherdocId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_otherdoc')->__('Total of %d other\'s course documents were successfully deleted.', count($otherdocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_otherdoc')->__('There was an error deleting other\'s course documents.')
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
        $otherdocIds = $this->getRequest()->getParam('otherdoc');
        if (!is_array($otherdocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_otherdoc')->__('Please select other\'s course documents.')
            );
        } else {
            try {
                foreach ($otherdocIds as $otherdocId) {
                $otherdoc = Mage::getSingleton('bs_otherdoc/otherdoc')->load($otherdocId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d other\'s course documents were successfully updated.', count($otherdocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_otherdoc')->__('There was an error updating other\'s course documents.')
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
    public function massOtherdocTypeAction()
    {
        $otherdocIds = $this->getRequest()->getParam('otherdoc');
        if (!is_array($otherdocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_otherdoc')->__('Please select other\'s course documents.')
            );
        } else {
            try {
                foreach ($otherdocIds as $otherdocId) {
                $otherdoc = Mage::getSingleton('bs_otherdoc/otherdoc')->load($otherdocId)
                    ->setOtherdocType($this->getRequest()->getParam('flag_otherdoc_type'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d other\'s course documents were successfully updated.', count($otherdocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_otherdoc')->__('There was an error updating other\'s course documents.')
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
    public function massOtherdocRevAction()
    {
        $otherdocIds = $this->getRequest()->getParam('otherdoc');
        if (!is_array($otherdocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_otherdoc')->__('Please select other\'s course documents.')
            );
        } else {
            try {
                foreach ($otherdocIds as $otherdocId) {
                $otherdoc = Mage::getSingleton('bs_otherdoc/otherdoc')->load($otherdocId)
                    ->setOtherdocRev($this->getRequest()->getParam('flag_otherdoc_rev'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d other\'s course documents were successfully updated.', count($otherdocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_otherdoc')->__('There was an error updating other\'s course documents.')
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
        $this->_initOtherdoc();
        $this->loadLayout();
        $this->getLayout()->getBlock('otherdoc.edit.tab.product')
            ->setOtherdocProducts($this->getRequest()->getPost('otherdoc_products', null));
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
        $this->_initOtherdoc();
        $this->loadLayout();
        $this->getLayout()->getBlock('otherdoc.edit.tab.product')
            ->setOtherdocProducts($this->getRequest()->getPost('otherdoc_products', null));
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
        $fileName   = 'otherdoc.csv';
        $content    = $this->getLayout()->createBlock('bs_otherdoc/adminhtml_otherdoc_grid')
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
        $fileName   = 'otherdoc.xls';
        $content    = $this->getLayout()->createBlock('bs_otherdoc/adminhtml_otherdoc_grid')
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
        $fileName   = 'otherdoc.xml';
        $content    = $this->getLayout()->createBlock('bs_otherdoc/adminhtml_otherdoc_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_material/otherdoc');
    }
}
