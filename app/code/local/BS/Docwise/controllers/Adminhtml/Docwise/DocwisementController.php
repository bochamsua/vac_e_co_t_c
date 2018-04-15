<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Docwise Document admin controller
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Adminhtml_Docwise_DocwisementController extends BS_Docwise_Controller_Adminhtml_Docwise
{
    /**
     * init the docwise document
     *
     * @access protected
     * @return BS_Docwise_Model_Docwisement
     */
    protected function _initDocwisement()
    {
        $docwisementId  = (int) $this->getRequest()->getParam('id');
        $docwisement    = Mage::getModel('bs_docwise/docwisement');
        if ($docwisementId) {
            $docwisement->load($docwisementId);
        }
        Mage::register('current_docwisement', $docwisement);
        return $docwisement;
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
        $this->_title(Mage::helper('bs_docwise')->__('Docwise'))
             ->_title(Mage::helper('bs_docwise')->__('Docwise Documents'));
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
     * edit docwise document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $docwisementId    = $this->getRequest()->getParam('id');
        $docwisement      = $this->_initDocwisement();
        if ($docwisementId && !$docwisement->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_docwise')->__('This docwise document no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getDocwisementData(true);
        if (!empty($data)) {
            $docwisement->setData($data);
        }
        Mage::register('docwisement_data', $docwisement);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_docwise')->__('Docwise'))
             ->_title(Mage::helper('bs_docwise')->__('Docwise Documents'));
        if ($docwisement->getId()) {
            $this->_title($docwisement->getDocName());
        } else {
            $this->_title(Mage::helper('bs_docwise')->__('Add docwise document'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new docwise document action
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
     * save docwise document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('docwisement')) {
            try {
                $data = $this->_filterDates($data, array('doc_date'));
                $docwisement = $this->_initDocwisement();
                $docwisement->addData($data);
                $docFileName = $this->_uploadAndGetName(
                    'doc_file',
                    Mage::helper('bs_docwise/docwisement')->getFileBaseDir(),
                    $data
                );
                $docwisement->setData('doc_file', $docFileName);
                $docwisement->save();

                $exams = $this->getRequest()->getPost('exams', -1);
                if ($exams == -1) {
                    if(isset($data['exam_id']) && $data['exam_id'] > 0){

                        $post = array(
                            $data['exam_id'] => array(
                                'position' => ""
                            )
                        );
                        $examDocwisement = Mage::getResourceSingleton('bs_docwise/exam_docwisement')
                            ->saveDocwisementRelation($docwisement, $post);
                        

                        $url = $this->getUrl('*/docwise_exam/edit', array('id'=>$data['exam_id'], 'back'=> 'edit', 'tab'=>'exam_tabs_docwisements'));
                    }
                }




                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.location.href=\''.$url.'\'; window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Docwise Document was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $docwisement->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['doc_file']['value'])) {
                    $data['doc_file'] = $data['doc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setDocwisementData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['doc_file']['value'])) {
                    $data['doc_file'] = $data['doc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was a problem saving the docwise document.')
                );
                Mage::getSingleton('adminhtml/session')->setDocwisementData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Unable to find docwise document to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete docwise document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $docwisement = Mage::getModel('bs_docwise/docwisement');
                $docwisement->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Docwise Document was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting docwise document.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Could not find docwise document to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete docwise document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $docwisementIds = $this->getRequest()->getParam('docwisement');
        if (!is_array($docwisementIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select docwise documents to delete.')
            );
        } else {
            try {
                foreach ($docwisementIds as $docwisementId) {
                    $docwisement = Mage::getModel('bs_docwise/docwisement');
                    $docwisement->setId($docwisementId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Total of %d docwise documents were successfully deleted.', count($docwisementIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting docwise documents.')
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
        $docwisementIds = $this->getRequest()->getParam('docwisement');
        if (!is_array($docwisementIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select docwise documents.')
            );
        } else {
            try {
                foreach ($docwisementIds as $docwisementId) {
                $docwisement = Mage::getSingleton('bs_docwise/docwisement')->load($docwisementId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d docwise documents were successfully updated.', count($docwisementIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating docwise documents.')
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
    public function massDocTypeAction()
    {
        $docwisementIds = $this->getRequest()->getParam('docwisement');
        if (!is_array($docwisementIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select docwise documents.')
            );
        } else {
            try {
                foreach ($docwisementIds as $docwisementId) {
                $docwisement = Mage::getSingleton('bs_docwise/docwisement')->load($docwisementId)
                    ->setDocType($this->getRequest()->getParam('flag_doc_type'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d docwise documents were successfully updated.', count($docwisementIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating docwise documents.')
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
        $fileName   = 'docwisement.csv';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_docwisement_grid')
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
        $fileName   = 'docwisement.xls';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_docwisement_grid')
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
        $fileName   = 'docwisement.xml';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_docwisement_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/bs_docwise/docwisement');
    }
}
