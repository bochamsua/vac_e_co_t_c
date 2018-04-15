<?php
/**
 * BS_ImportTrainee extension
 * 
 * @category       BS
 * @package        BS_ImportTrainee
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Trainee admin controller
 *
 * @category    BS
 * @package     BS_ImportTrainee
 * @author Bui Phong
 */
class BS_ImportTrainee_Adminhtml_Importtrainee_ImporttraineeController extends BS_ImportTrainee_Controller_Adminhtml_ImportTrainee
{
    /**
     * init the import trainee
     *
     * @access protected
     * @return BS_ImportTrainee_Model_Importtrainee
     */
    protected function _initImporttrainee()
    {
        $importtraineeId  = (int) $this->getRequest()->getParam('id');
        $importtrainee    = Mage::getModel('bs_importtrainee/importtrainee');
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
        $this->_title(Mage::helper('bs_importtrainee')->__('Import Trainees'))
             ->_title(Mage::helper('bs_importtrainee')->__('Import Trainees'));
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
                Mage::helper('bs_importtrainee')->__('This import trainee no longer exists.')
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
        $this->_title(Mage::helper('bs_importtrainee')->__('Import Trainees'))
             ->_title(Mage::helper('bs_importtrainee')->__('Import Trainees'));
        if ($importtrainee->getId()) {
            $this->_title($importtrainee->getCourseId());
        } else {
            $this->_title(Mage::helper('bs_importtrainee')->__('Import trainee'));
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

                $productId = $data['course_id'];
                $clear = $data['clearall'];
                $product = Mage::getModel('catalog/product')->load($productId);

                $bypassDocwise = false;
                if($product->getBypassDocwise()){
                    $bypassDocwise = true;
                }


                $err = '';
                //get curriculum
                $docwise = false;
                $cur = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addProductFilter($product)->getFirstItem();
                if($cur->getId()){
                    $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($cur->getId());
                    $docwise = $curriculum->getCDocwise();
                }

                $vaecoIds = explode("\r\n", $data['vaeco_ids']);
                $traineeCodes = explode("\r\n", $data['trainee_ids']);
                $traineeIds = array();
                $i=1;

                if(!$clear){
                    $currentTrainees = Mage::getModel('bs_trainee/trainee')->getCollection()->addProductFilter($product);
                    if($currentTrainees->count()){
                        foreach ($currentTrainees as $tn) {
                            $traineeIds[$tn->getId()] = array('position'=>$i);

                            $i++;
                        }

                    }
                }

                $result = array();
                $blackListed = array();

                foreach ($vaecoIds as $id) {



                    $id = trim($id);
                    if(strlen($id) == 5){
                        $id = "VAE".$id;
                    }elseif (strlen($id) == 4){
                        $id = "VAE0".$id;
                    }
                    $id = strtoupper($id);
                    if($id != ''){
                        //check valid docwise if required
                        if($docwise && !$bypassDocwise){
                            $valid = Mage::helper('bs_trainee')->checkDocwise($id, $product->getCourseStartDate());
                            if(!$valid){
                                $traineeId = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem()->getId();
                                $trainee = Mage::getModel('bs_trainee/trainee')->load($traineeId);
                                $result[] = $trainee->getTraineeName()." ({$id})";
                                continue;
                            }
                        }

                        //check if this id existed in trainee table
                        $trainee = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                        if($trainee->getId()){//if existed, just add to the course
                            $traineeIds[$trainee->getId()] = array('position'=>$i);


                            //Check blacklist
                            $blackList = Mage::helper('bs_trainee')->checkBlacklist($trainee->getId(), $product->getCourseStartDate());
                            if($blackList){
                                $blackListed[] = $blackList;
                                continue;
                            }


                        }else {// we will add to the trainee table then add to the course
                            $trainee = Mage::getModel('bs_trainee/trainee');
                            $trainee->setAttributeSetId($trainee->getDefaultAttributeSetId());
                            //get info
                            $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                            if($customer->getId()){
                                $cus = Mage::getModel('customer/customer')->load($customer->getId());
                                $name = $cus->getName();
                                $phone = $cus->getPhone();
                                $departmentId = $cus->getGroupId();
                                $dob = $cus->getDob();
                                $pob = $cus->getPob();
                                $idNumber = $cus->getIdNumber();
                                $idDate = $cus->getIdDate();
                                $idPlace = $cus->getIdPlace();

                                $group = Mage::getModel('customer/group')->load($departmentId);
                                $dept = $group->getCustomerGroupCodeVi();

                                $trainee->setVaecoId($id);
                                $trainee->setTraineeName($name);
                                $trainee->setTraineeDept($dept);
                                $trainee->setTraineePhone($phone);
                                $trainee->setTraineeDob($dob);
                                $trainee->setTraineePob($pob);
                                $trainee->setTraineeCmnd($idNumber);
                                $trainee->setTraineeCmndDate($idDate);
                                $trainee->setTraineeCmndPlace($idPlace);
                                $trainee->save();

                                $traineeIds[$trainee->getId()] = array('position'=>$i);
                            }

                        }

                        $i++;
                    }


                }

                if(count($traineeCodes)){
                    foreach ($traineeCodes as $item) {

                        if(trim($item) != ''){
                            $item = strtoupper($item);
                            $trainee = Mage::getModel('bs_trainee/trainee')->getCollection()->addAttributeToFilter('trainee_code', $item)->getFirstItem();
                            if($trainee->getId()){//if existed, just add to the course
                                //Check blacklist
                                $blackList = Mage::helper('bs_trainee')->checkBlacklist($trainee->getId(), $product->getCourseStartDate());
                                if($blackList){
                                    $blackListed[] = $blackList;
                                    continue;
                                }
                                $traineeIds[$trainee->getId()] = array('position'=>$i);


                            }else {
                                $err .= $item.'! \n';
                            }
                        }

                    }
                }

                if(count($result) || count($blackListed)){

                    if(count($result)){
                        $idstring = implode(",", $result);
                        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bs_importtrainee')->__('These trainees: %s don\'t have valid DOCWISE certificate!',$idstring));
                    }

                    if(count($blackListed)){
                        $idstring = implode(",", $blackListed);
                        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('bs_importtrainee')->__('These trainees: %s are blacklisted!',$idstring));
                    }




                    Mage::getSingleton('adminhtml/session')->setImporttraineeData($data);
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'popup'=>1, 'product_id'=>$productId));
                    return;
                }

                if(count($traineeIds)){
                    $duplicate = array();
                    foreach ($traineeIds as $id=>$value) {
                        $check = Mage::helper('bs_trainee')->checkDuplicate($product, $id);
                        if($check){
                            $duplicate[$id] = $check;
                        }
                    }

                    if(count($duplicate)){
                        foreach ($duplicate as $key => $values) {
                            $tn = Mage::getModel('bs_trainee/trainee')->load($key);
                            $name = $tn->getTraineeName();

                            $string = array();
                            foreach ($values as $sku) {
                                $prod = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
                                $hrNo = $prod->getHrDecisionNo();
                                $hrDate = '';
                                if($prod->getHrDecisionDate() != ''){
                                    $hrDate = Mage::getModel('core/date')->date('d/m/Y',$prod->getHrDecisionDate());
                                }

                                $txt = $sku;
                                if($hrNo != ''){
                                    $txt .= ' (HR No: '.$hrNo;
                                }
                                if($hrDate != ''){
                                    $txt .= ', HR Date: '.$hrDate.')';
                                }
                                $string[] = $txt;
                            }

                            

                            $err .= 'Trainee: '.$name.' is currently attended in course(s): '.implode(",", $string).'\n';
                        }

                        //Mage::getSingleton('adminhtml/session')->addError($str);
                        //Mage::getSingleton('adminhtml/session')->setImporttraineeData($data);
                        //$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), 'popup'=>1, 'product_id'=>$productId));
                        //return;

                    }

                }


                $traineeProduct = Mage::getResourceSingleton('bs_trainee/trainee_product')
                    ->saveProductRelation($product, $traineeIds);


                /*$importtrainee = $this->_initImporttrainee();
                $importtrainee->addData($data);
                $importtrainee->save();*/
                $add = '';
                if($err){
                    $err = 'alert(\''.$err.'\'); ';
                }
                $url = $this->getUrl('*/catalog_product/edit', array('id'=>$productId, 'back'=>'edit/tab/product_info_tabs_trainees'));
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>'.$err.'window.opener.window.location.href = \''.$url.'\';window.close(); </script>';//trainee_gridJsObject.doFilter()
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_importtrainee')->__('Import Trainee was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                $this->_redirect('*/catalog_product/edit', array('id'=>$productId, 'back'=>'edit/tab/product_info_tabs_trainees'));
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setImporttraineeData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_importtrainee')->__('There was a problem saving the import trainee.')
                );
                Mage::getSingleton('adminhtml/session')->setImporttraineeData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_importtrainee')->__('Unable to find import trainee to save.')
        );
        $this->_redirect('*/*/');
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
                $importtrainee = Mage::getModel('bs_importtrainee/importtrainee');
                $importtrainee->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_importtrainee')->__('Import Trainee was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_importtrainee')->__('There was an error deleting import trainee.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_importtrainee')->__('Could not find import trainee to delete.')
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
                Mage::helper('bs_importtrainee')->__('Please select import trainees to delete.')
            );
        } else {
            try {
                foreach ($importtraineeIds as $importtraineeId) {
                    $importtrainee = Mage::getModel('bs_importtrainee/importtrainee');
                    $importtrainee->setId($importtraineeId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_importtrainee')->__('Total of %d import trainees were successfully deleted.', count($importtraineeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_importtrainee')->__('There was an error deleting import trainees.')
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
                Mage::helper('bs_importtrainee')->__('Please select import trainees.')
            );
        } else {
            try {
                foreach ($importtraineeIds as $importtraineeId) {
                $importtrainee = Mage::getSingleton('bs_importtrainee/importtrainee')->load($importtraineeId)
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
                    Mage::helper('bs_importtrainee')->__('There was an error updating import trainees.')
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
        $content    = $this->getLayout()->createBlock('bs_importtrainee/adminhtml_importtrainee_grid')
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
        $content    = $this->getLayout()->createBlock('bs_importtrainee/adminhtml_importtrainee_grid')
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
        $content    = $this->getLayout()->createBlock('bs_importtrainee/adminhtml_importtrainee_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/importtrainee');
    }
}
