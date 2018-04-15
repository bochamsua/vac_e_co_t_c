<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Folder admin controller
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Adminhtml_Logistics_FilefolderController extends BS_Logistics_Controller_Adminhtml_Logistics
{
    /**
     * init the file folder
     *
     * @access protected
     * @return BS_Logistics_Model_Filefolder
     */
    protected function _initFilefolder()
    {
        $filefolderId  = (int) $this->getRequest()->getParam('id');
        $filefolder    = Mage::getModel('bs_logistics/filefolder');
        if ($filefolderId) {
            $filefolder->load($filefolderId);
        }
        Mage::register('current_filefolder', $filefolder);
        return $filefolder;
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
             ->_title(Mage::helper('bs_logistics')->__('File Folders'));
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
     * edit file folder - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $filefolderId    = $this->getRequest()->getParam('id');
        $filefolder      = $this->_initFilefolder();
        if ($filefolderId && !$filefolder->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_logistics')->__('This file folder no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getFilefolderData(true);
        if (!empty($data)) {
            $filefolder->setData($data);
        }
        Mage::register('filefolder_data', $filefolder);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_logistics')->__('Logistics'))
             ->_title(Mage::helper('bs_logistics')->__('File Folders'));
        if ($filefolder->getId()) {
            $this->_title($filefolder->getFilefolderName());
        } else {
            $this->_title(Mage::helper('bs_logistics')->__('Add file folder'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new file folder action
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
     * save file folder - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('filefolder')) {
            try {
                $filefolder = $this->_initFilefolder();
                $filefolder->addData($data);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $filefolder->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }


                $exams = $this->getRequest()->getPost('exams', -1);
                if ($exams != -1) {
                    $filefolder->setExamsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($exams));
                }


                //save Position
                $positions = $this->getRequest()->getPost('position');
                foreach ($positions as $key => $value) {
                    $subject = Mage::getModel('bs_logistics/foldercontent')->load($key);
                    $subject->setPosition($value)->save();
                }


                $filefolder->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('File Folder was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $filefolder->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFilefolderData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was a problem saving the file folder.')
                );
                Mage::getSingleton('adminhtml/session')->setFilefolderData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Unable to find file folder to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete file folder - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $filefolder = Mage::getModel('bs_logistics/filefolder');
                $filefolder->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('File Folder was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting file folder.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_logistics')->__('Could not find file folder to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete file folder - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $filefolderIds = $this->getRequest()->getParam('filefolder');
        if (!is_array($filefolderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select file folders to delete.')
            );
        } else {
            try {
                foreach ($filefolderIds as $filefolderId) {
                    $filefolder = Mage::getModel('bs_logistics/filefolder');
                    $filefolder->setId($filefolderId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_logistics')->__('Total of %d file folders were successfully deleted.', count($filefolderIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error deleting file folders.')
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
        $filefolderIds = $this->getRequest()->getParam('filefolder');
        if (!is_array($filefolderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select file folders.')
            );
        } else {
            try {
                foreach ($filefolderIds as $filefolderId) {
                $filefolder = Mage::getSingleton('bs_logistics/filefolder')->load($filefolderId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d file folders were successfully updated.', count($filefolderIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating file folders.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass file cabinet change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massFilecabinetIdAction()
    {
        $filefolderIds = $this->getRequest()->getParam('filefolder');
        if (!is_array($filefolderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_logistics')->__('Please select file folders.')
            );
        } else {
            try {
                foreach ($filefolderIds as $filefolderId) {
                $filefolder = Mage::getSingleton('bs_logistics/filefolder')->load($filefolderId)
                    ->setFilecabinetId($this->getRequest()->getParam('flag_filecabinet_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d file folders were successfully updated.', count($filefolderIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_logistics')->__('There was an error updating file folders.')
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
        $this->_initFilefolder();
        $this->loadLayout();
        $this->getLayout()->getBlock('filefolder.edit.tab.product')
            ->setFilefolderProducts($this->getRequest()->getPost('filefolder_products', null));
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
        $this->_initFilefolder();
        $this->loadLayout();
        $this->getLayout()->getBlock('filefolder.edit.tab.product')
            ->setFilefolderProducts($this->getRequest()->getPost('filefolder_products', null));
        $this->renderLayout();
    }


    public function examsAction()
    {
        $this->_initFilefolder();
        $this->loadLayout();
        $this->getLayout()->getBlock('filefolder.edit.tab.exam')
            ->setFilefolderExams($this->getRequest()->getPost('filefolder_exams', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function examsgridAction()
    {
        $this->_initFilefolder();
        $this->loadLayout();
        $this->getLayout()->getBlock('filefolder.edit.tab.exam')
            ->setFilefolderExams($this->getRequest()->getPost('filefolder_exams', null));
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
        $fileName   = 'filefolder.csv';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_filefolder_grid')
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
        $fileName   = 'filefolder.xls';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_filefolder_grid')
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
        $fileName   = 'filefolder.xml';
        $content    = $this->getLayout()->createBlock('bs_logistics/adminhtml_filefolder_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_logistics/filefolder');
    }

    public function getCorrectValueAction(){


        $result = array();
        $value = $this->getRequest()->getPost('file_folder');
        $value = str_replace(" ", "", $value);


        $filefolder = Mage::getModel('bs_logistics/filefolder')->getCollection()
            ->addFieldToFilter('filefolder_code', array('like'=>$value))->getFirstItem();

        if($filefolder->getId()){
            $result['file_folder'] = $filefolder->getFilefolderCode();



        }



        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }


    public function foldercontentsAction()
    {
        $this->_initFilefolder();
        $this->loadLayout();
        $this->getLayout()->getBlock('filefolder.edit.tab.foldercontent');
        //->setProductSchedules($this->getRequest()->getPost('product_schedules', null));
        $this->renderLayout();
    }

    /**
     * course schedule grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function foldercontentsGridAction()
    {
        $this->_initFilefolder();
        $this->loadLayout();
        $this->getLayout()->getBlock('filefolder.edit.tab.foldercontent');
        //->setProductSchedules($this->getRequest()->getPost('product_schedules', null));
        $this->renderLayout();
    }
}
