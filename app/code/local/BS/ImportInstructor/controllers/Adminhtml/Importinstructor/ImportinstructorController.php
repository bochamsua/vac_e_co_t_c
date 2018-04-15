<?php
/**
 * BS_ImportInstructor extension
 * 
 * @category       BS
 * @package        BS_ImportInstructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Instructor admin controller
 *
 * @category    BS
 * @package     BS_ImportInstructor
 * @author Bui Phong
 */
class BS_ImportInstructor_Adminhtml_Importinstructor_ImportinstructorController extends BS_ImportInstructor_Controller_Adminhtml_ImportInstructor
{
    /**
     * init the import instructor
     *
     * @access protected
     * @return BS_ImportInstructor_Model_Importinstructor
     */
    protected function _initImportinstructor()
    {
        $importinstructorId  = (int) $this->getRequest()->getParam('id');
        $importinstructor    = Mage::getModel('bs_importinstructor/importinstructor');
        if ($importinstructorId) {
            $importinstructor->load($importinstructorId);
        }
        Mage::register('current_importinstructor', $importinstructor);
        return $importinstructor;
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
        $this->_title(Mage::helper('bs_importinstructor')->__('Import Instructor'))
             ->_title(Mage::helper('bs_importinstructor')->__('Import Instructors'));
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
     * edit import instructor - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $importinstructorId    = $this->getRequest()->getParam('id');
        $importinstructor      = $this->_initImportinstructor();
        if ($importinstructorId && !$importinstructor->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_importinstructor')->__('This import instructor no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getImportinstructorData(true);
        if (!empty($data)) {
            $importinstructor->setData($data);
        }
        Mage::register('importinstructor_data', $importinstructor);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_importinstructor')->__('Import Instructor'))
             ->_title(Mage::helper('bs_importinstructor')->__('Import Instructors'));
        if ($importinstructor->getId()) {
            $this->_title($importinstructor->getCurriculumId());
        } else {
            $this->_title(Mage::helper('bs_importinstructor')->__('Add import instructor'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new import instructor action
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
     * save import instructor - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('importinstructor')) {
            try {
                $curriculumId = $data['curriculum_id'];
                $curriculum = Mage::getModel('bs_traininglist/curriculum')->load($curriculumId);
                $clear = $data['clearall'];


                $vaecoIds = explode("\r\n", $data['vaeco_ids']);
                $instructorIds = array();
                $i=1;

                if(!$clear){
                    $currentInstructors = Mage::getModel('bs_instructor/instructor')->getCollection()->addCurriculumFilter($curriculumId);
                    if($currentInstructors->count()){
                        foreach ($currentInstructors as $tn) {
                            $instructorIds[$tn->getId()] = array('position'=>$i);

                            $i++;
                        }

                    }
                }

                foreach ($vaecoIds as $id) {

                    $id = trim($id);
                    if(strlen($id) == 5){
                        $id = "VAE".$id;
                    }elseif (strlen($id) == 4){
                        $id = "VAE0".$id;
                    }
                    $id = strtoupper($id);

                    //check if this id existed in trainee table
                    $instructor = Mage::getModel('bs_instructor/instructor')->getCollection()->addAttributeToFilter('ivaecoid', $id)->getFirstItem();
                    if($instructor->getId()){//if existed, just add to the course
                        $instructorIds[$instructor->getId()] = array('position'=>$i);


                    }else {// we will add to the trainee table then add to the course
                        $instructor = Mage::getModel('bs_instructor/instructor');
                        $instructor->setAttributeSetId($instructor->getDefaultAttributeSetId());
                        //get info
                        $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                        if($customer->getId()){
                            $cus = Mage::getModel('customer/customer')->load($customer->getId());
                            $name = $cus->getName();
                            $phone = $cus->getPhone();
                            $departmentId = $cus->getGroupId();
                            $dob = $cus->getDob();
                            $pob = $cus->getPob();
                            $pos = $cus->getPosition();
                            $div = $cus->getDivision();
                            $username = $cus->getUsername();


                            $group = Mage::getModel('customer/group')->load($departmentId);
                            $dept = $group->getCustomerGroupCodeVi();

                            $dept =  $div.' - '. $dept;

                            $instructor->setIvaecoid($id);
                            $instructor->setIname($name);
                            $instructor->setIusername($username);
                            $instructor->setIdivisionDepartment($dept);
                            $instructor->setIphone($phone);
                            $instructor->setIposition($pos);

                            $instructor->save();

                            $instructorIds[$instructor->getId()] = array('position'=>$i);
                        }

                    }

                    $i++;
                }

                $curriculumInstructor= Mage::getResourceSingleton('bs_instructor/instructor_curriculum')->saveCurriculumRelation($curriculum, $instructorIds);

                $add = '';
                $url = $this->getUrl('*/traininglist_curriculum/edit', array('id'=>$curriculumId));
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.opener.window.location.href = \''.$url.'\';window.close()</script>';//trainee_gridJsObject.doFilter()
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_importinstructor')->__('Import Instructor was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setImportinstructorData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_importinstructor')->__('There was a problem saving the import instructor.')
                );
                Mage::getSingleton('adminhtml/session')->setImportinstructorData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_importinstructor')->__('Unable to find import instructor to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete import instructor - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $importinstructor = Mage::getModel('bs_importinstructor/importinstructor');
                $importinstructor->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_importinstructor')->__('Import Instructor was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_importinstructor')->__('There was an error deleting import instructor.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_importinstructor')->__('Could not find import instructor to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete import instructor - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $importinstructorIds = $this->getRequest()->getParam('importinstructor');
        if (!is_array($importinstructorIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_importinstructor')->__('Please select import instructors to delete.')
            );
        } else {
            try {
                foreach ($importinstructorIds as $importinstructorId) {
                    $importinstructor = Mage::getModel('bs_importinstructor/importinstructor');
                    $importinstructor->setId($importinstructorId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_importinstructor')->__('Total of %d import instructors were successfully deleted.', count($importinstructorIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_importinstructor')->__('There was an error deleting import instructors.')
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
        $importinstructorIds = $this->getRequest()->getParam('importinstructor');
        if (!is_array($importinstructorIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_importinstructor')->__('Please select import instructors.')
            );
        } else {
            try {
                foreach ($importinstructorIds as $importinstructorId) {
                $importinstructor = Mage::getSingleton('bs_importinstructor/importinstructor')->load($importinstructorId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d import instructors were successfully updated.', count($importinstructorIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_importinstructor')->__('There was an error updating import instructors.')
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
        $fileName   = 'importinstructor.csv';
        $content    = $this->getLayout()->createBlock('bs_importinstructor/adminhtml_importinstructor_grid')
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
        $fileName   = 'importinstructor.xls';
        $content    = $this->getLayout()->createBlock('bs_importinstructor/adminhtml_importinstructor_grid')
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
        $fileName   = 'importinstructor.xml';
        $content    = $this->getLayout()->createBlock('bs_importinstructor/adminhtml_importinstructor_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/importinstructor');
    }
}
