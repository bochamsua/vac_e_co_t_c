<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Trainee admin controller
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Adminhtml_Docwise_ImporttraineeController extends BS_Docwise_Controller_Adminhtml_Docwise
{
    /**
     * init the import trainee
     *
     * @access protected
     * @return BS_Docwise_Model_Importtrainee
     */
    protected function _initImporttrainee()
    {
        $importtraineeId  = (int) $this->getRequest()->getParam('id');
        $importtrainee    = Mage::getModel('bs_docwise/importtrainee');
        if ($importtraineeId) {
            $importtrainee->load($importtraineeId);
        }
        Mage::register('current_importtrainee', $importtrainee);
        return $importtrainee;
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
             ->_title(Mage::helper('bs_docwise')->__('Import Trainees'));
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
     * edit import trainee - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $importtraineeId    = $this->getRequest()->getParam('id');
        $importtrainee      = $this->_initImporttrainee();
        if ($importtraineeId && !$importtrainee->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_docwise')->__('This import trainee no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getImporttraineeData(true);
        if (!empty($data)) {
            $importtrainee->setData($data);
        }
        Mage::register('importtrainee_data', $importtrainee);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_docwise')->__('Docwise'))
             ->_title(Mage::helper('bs_docwise')->__('Import Trainees'));
        if ($importtrainee->getId()) {
            $this->_title($importtrainee->getTitle());
        } else {
            $this->_title(Mage::helper('bs_docwise')->__('Add import trainee'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new import trainee action
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
     * save import trainee - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('importtrainee')) {
            try {

                $examId = $data['exam_id'];
                $clear = $data['clearall'];
                $exam = Mage::getModel('bs_docwise/exam')->load($examId);

                $i=1;

                $collection = Mage::getModel('bs_docwise/examtrainee')->getCollection()->addFieldToFilter('exam_id', $examId);
                if($collection->count()){
                    if($clear){
                        $collection->walk('delete');
                    }else {
                        $i = $collection->count() + 1;
                    }
                }



                $examDate = $exam->getExamDate();
                /*$examCode = $exam->getExamCode();
                $examDate = $exam->getExamDate();
                $examType = $exam->getExamType();
                $certType = $exam->getCertType();

                $add = '5 years';

                if($certType == 3){
                    $add = '2 years';
                }
                if($examType == 2){
                    $add = '6 months';
                }

                $date = date_create($examDate);
                date_add($date, date_interval_create_from_date_string($add));
                $expireDate =  date_format($date, 'Y-m-d');*/




                $vaecoIds = explode("\r\n", $data['vaeco_ids']);
                if(count($vaecoIds)){
                    $newIds = array();
                    foreach ($vaecoIds as $id) {
                        $id = trim($id);
                        if(!strpos("moke".strtolower($id),"hv")){
                            if(strlen($id) == 5){
                                $id = "VAE".$id;
                            }elseif (strlen($id) == 4){
                                $id = "VAE0".$id;
                            }
                        }

                        $id = strtoupper($id);

                        $newIds[$id] = 1;
                    }

                    $vaecoIds = array_keys($newIds);

                    $err = '';
                    foreach ($vaecoIds as $id) {

                        $pass = $this->checkTrainee($id, $examDate);


                        if(!$pass){
                            $err .= 'Trainee '.$id.' is not qualified to take the exam \n';


                        }

                        $trainees = Mage::getModel('bs_docwise/trainee')->getCollection()->addFieldToFilter('vaeco_id', $id)->getFirstItem();
                        if($trainees->getId()){//if existed, just add to the course

                            $extn = Mage::getModel('bs_docwise/examtrainee');
                            $extn->setExamId($examId)->setTraineeId($trainees->getId())->setPosition($i)->save();


                        }else {// we will add to the trainee table then add to the course
                            $trainee = Mage::getModel('bs_docwise/trainee');

                            //get info
                            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                            $name = '';
                            $vaecoId = '';
                            $dob = '';

                            if($customer->getId()){
                                $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                $name = $cus->getName();

                                $dob = $cus->getDob();

                            }else {
                                $tn = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('trainee_code', $id)->getFirstItem();
                                if($tn->getId()){
                                    $tnM = Mage::getModel('bs_trainee/trainee')->load($tn->getId());
                                    $name = $tnM->getTraineeName();
                                    $dob = $tnM->getTraineeDob();
                                }
                            }


                            if($name != ''){
                                $trainee->setVaecoId($id);
                                $trainee->setTraineeName($name);
                                if($dob != ''){
                                    $trainee->setDob($dob);
                                }


                                $trainee->save();

                                $extn = Mage::getModel('bs_docwise/examtrainee');
                                $extn->setExamId($examId)->setTraineeId($trainee->getId())->setPosition($i)->save();
                            }



                        }

                        $i++;

                    }

                }







                $add = '';
                if($err != ''){
                    $err = 'alert(\''.$err.'\'); ';
                }
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>'.$err.'window.opener.trainee_gridJsObject.reload(); window.close();</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Import Trainee was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setImporttraineeData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was a problem saving the import trainee.')
                );
                Mage::getSingleton('adminhtml/session')->setImporttraineeData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Unable to find import trainee to save.')
        );
        $this->_redirect('*/*/');
    }

    public function checkTrainee($vaecoId, $examDate){
        $scores = Mage::getModel('bs_docwise/score')->getCollection()->addFieldToFilter('vaeco_id', $vaecoId)->setPageSize(2)->setOrder('exam_date', 'DESC');
        if($scores->getFirstItem()->getId()){
            if((int)$scores->getFirstItem()->getScore() < 75){
                if((int)$scores->getLastItem()->getScore() < 75){
                    return false;
                }

                $date1=  new DateTime($scores->getFirstItem()->getExamDate());
                $date2= new DateTime(now());
                $diff =  $date1->diff($date2)->format("%a");

                $diff = (int)$diff;
                if($diff < 91){
                    return false;
                }


            }
        }



        return true;
    }

    /**
     * delete import trainee - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $importtrainee = Mage::getModel('bs_docwise/importtrainee');
                $importtrainee->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Import Trainee was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting import trainee.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_docwise')->__('Could not find import trainee to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete import trainee - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $importtraineeIds = $this->getRequest()->getParam('importtrainee');
        if (!is_array($importtraineeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select import trainees to delete.')
            );
        } else {
            try {
                foreach ($importtraineeIds as $importtraineeId) {
                    $importtrainee = Mage::getModel('bs_docwise/importtrainee');
                    $importtrainee->setId($importtraineeId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_docwise')->__('Total of %d import trainees were successfully deleted.', count($importtraineeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error deleting import trainees.')
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
        $importtraineeIds = $this->getRequest()->getParam('importtrainee');
        if (!is_array($importtraineeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select import trainees.')
            );
        } else {
            try {
                foreach ($importtraineeIds as $importtraineeId) {
                $importtrainee = Mage::getSingleton('bs_docwise/importtrainee')->load($importtraineeId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d import trainees were successfully updated.', count($importtraineeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating import trainees.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Clear all current? change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massClearallAction()
    {
        $importtraineeIds = $this->getRequest()->getParam('importtrainee');
        if (!is_array($importtraineeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_docwise')->__('Please select import trainees.')
            );
        } else {
            try {
                foreach ($importtraineeIds as $importtraineeId) {
                $importtrainee = Mage::getSingleton('bs_docwise/importtrainee')->load($importtraineeId)
                    ->setClearall($this->getRequest()->getParam('flag_clearall'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d import trainees were successfully updated.', count($importtraineeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_docwise')->__('There was an error updating import trainees.')
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
        $fileName   = 'importtrainee.csv';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_importtrainee_grid')
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
        $fileName   = 'importtrainee.xls';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_importtrainee_grid')
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
        $fileName   = 'importtrainee.xml';
        $content    = $this->getLayout()->createBlock('bs_docwise/adminhtml_importtrainee_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_exam/bs_docwise/importtrainee');
    }
}
