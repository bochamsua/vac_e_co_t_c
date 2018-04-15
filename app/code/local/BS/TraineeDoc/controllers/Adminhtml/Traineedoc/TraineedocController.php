<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee Document admin controller
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Adminhtml_Traineedoc_TraineedocController extends BS_TraineeDoc_Controller_Adminhtml_TraineeDoc
{
    /**
     * init the trainee document
     *
     * @access protected
     * @return BS_TraineeDoc_Model_Traineedoc
     */
    protected function _initTraineedoc()
    {
        $traineedocId  = (int) $this->getRequest()->getParam('id');
        $traineedoc    = Mage::getModel('bs_traineedoc/traineedoc');
        if ($traineedocId) {
            $traineedoc->load($traineedocId);
        }
        Mage::register('current_traineedoc', $traineedoc);
        return $traineedoc;
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
        $this->_title(Mage::helper('bs_traineedoc')->__('Trainee Document'))
             ->_title(Mage::helper('bs_traineedoc')->__('Trainee Document'));
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
     * edit trainee document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $traineedocId    = $this->getRequest()->getParam('id');
        $traineedoc      = $this->_initTraineedoc();
        if ($traineedocId && !$traineedoc->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_traineedoc')->__('This trainee document no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getTraineedocData(true);
        if (!empty($data)) {
            $traineedoc->setData($data);
        }
        Mage::register('traineedoc_data', $traineedoc);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_traineedoc')->__('Trainee Document'))
             ->_title(Mage::helper('bs_traineedoc')->__('Trainee Document'));
        if ($traineedoc->getId()) {
            $this->_title($traineedoc->getTraineeDocName());
        } else {
            $this->_title(Mage::helper('bs_traineedoc')->__('Add trainee document'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new trainee document action
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
     * save trainee document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('traineedoc')) {
            try {
                $data = $this->_filterDates($data, array('trainee_doc_date'));

                $traineedoc = $this->_initTraineedoc();
                $traineedoc->addData($data);
                $traineeDocFileName = $this->_uploadAndGetName(
                    'trainee_doc_file',
                    Mage::helper('bs_traineedoc/traineedoc')->getFileBaseDir(),
                    $data
                );
                $traineedoc->setData('trainee_doc_file', $traineeDocFileName);
                $trainees = $this->getRequest()->getPost('trainees', -1);
                if ($trainees != -1) {
                    $traineedoc->setTraineesData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($trainees));
                }else {
                    if(isset($data['hidden_trainee_id']) && $data['hidden_trainee_id'] > 0){
                        $traineedoc->setTraineesData(
                            array(
                                $data['hidden_trainee_id'] => array(
                                    'position' => ""
                                )
                            )
                        );
                    }
                }
                $traineedoc->save();

                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.location.reload(); window.close()</script>';
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_traineedoc')->__('Trainee Document was successfully saved %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $traineedoc->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['trainee_doc_file']['value'])) {
                    $data['trainee_doc_file'] = $data['trainee_doc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setTraineedocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['trainee_doc_file']['value'])) {
                    $data['trainee_doc_file'] = $data['trainee_doc_file']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traineedoc')->__('There was a problem saving the trainee document.')
                );
                Mage::getSingleton('adminhtml/session')->setTraineedocData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_traineedoc')->__('Unable to find trainee document to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete trainee document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $traineedoc = Mage::getModel('bs_traineedoc/traineedoc');
                $traineedoc->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_traineedoc')->__('Trainee Document was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traineedoc')->__('There was an error deleting trainee document.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_traineedoc')->__('Could not find trainee document to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete trainee document - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $traineedocIds = $this->getRequest()->getParam('traineedoc');
        if (!is_array($traineedocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traineedoc')->__('Please select trainee document to delete.')
            );
        } else {
            try {
                foreach ($traineedocIds as $traineedocId) {
                    $traineedoc = Mage::getModel('bs_traineedoc/traineedoc');
                    $traineedoc->setId($traineedocId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_traineedoc')->__('Total of %d trainee document were successfully deleted.', count($traineedocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traineedoc')->__('There was an error deleting trainee document.')
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
        $traineedocIds = $this->getRequest()->getParam('traineedoc');
        if (!is_array($traineedocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traineedoc')->__('Please select trainee document.')
            );
        } else {
            try {
                foreach ($traineedocIds as $traineedocId) {
                $traineedoc = Mage::getSingleton('bs_traineedoc/traineedoc')->load($traineedocId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d trainee document were successfully updated.', count($traineedocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traineedoc')->__('There was an error updating trainee document.')
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
    public function massTraineeDocTypeAction()
    {
        $traineedocIds = $this->getRequest()->getParam('traineedoc');
        if (!is_array($traineedocIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_traineedoc')->__('Please select trainee document.')
            );
        } else {
            try {
                foreach ($traineedocIds as $traineedocId) {
                $traineedoc = Mage::getSingleton('bs_traineedoc/traineedoc')->load($traineedocId)
                    ->setTraineeDocType($this->getRequest()->getParam('flag_trainee_doc_type'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d trainee document were successfully updated.', count($traineedocIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_traineedoc')->__('There was an error updating trainee document.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get grid of trainees action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function traineesAction()
    {
        $this->_initTraineedoc();
        $this->loadLayout();
        $this->getLayout()->getBlock('traineedoc.edit.tab.trainee')
            ->setTraineedocTrainees($this->getRequest()->getPost('traineedoc_trainees', null));
        $this->renderLayout();
    }

    /**
     * get grid of trainees action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function traineesgridAction()
    {
        $this->_initTraineedoc();
        $this->loadLayout();
        $this->getLayout()->getBlock('traineedoc.edit.tab.trainee')
            ->setTraineedocTrainees($this->getRequest()->getPost('traineedoc_trainees', null));
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
        $fileName   = 'traineedoc.csv';
        $content    = $this->getLayout()->createBlock('bs_traineedoc/adminhtml_traineedoc_grid')
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
        $fileName   = 'traineedoc.xls';
        $content    = $this->getLayout()->createBlock('bs_traineedoc/adminhtml_traineedoc_grid')
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
        $fileName   = 'traineedoc.xml';
        $content    = $this->getLayout()->createBlock('bs_traineedoc/adminhtml_traineedoc_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_material/traineedoc');
    }
}
