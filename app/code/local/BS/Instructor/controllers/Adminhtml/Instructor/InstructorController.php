<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor admin controller
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Adminhtml_Instructor_InstructorController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author Bui Phong
     */
    protected function _construct()
    {
        $this->setUsedModuleName('BS_Instructor');
    }

    /**
     * init the instructor
     *
     * @access protected 
     * @return BS_Instructor_Model_Instructor
     * @author Bui Phong
     */
    protected function _initInstructor()
    {
        $this->_title($this->__('Instructor'))
             ->_title($this->__('Manage Instructors'));

        $instructorId  = (int) $this->getRequest()->getParam('id');
        $instructor    = Mage::getModel('bs_instructor/instructor')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($instructorId) {
            $instructor->load($instructorId);
        }
        Mage::register('current_instructor', $instructor);
        return $instructor;
    }

    /**
     * default action for instructor controller
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function indexAction()
    {
        $this->_title($this->__('Instructor'))
             ->_title($this->__('Manage Instructors'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new instructor action
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
     * edit instructor action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $instructorId  = (int) $this->getRequest()->getParam('id');
        $instructor    = $this->_initInstructor();
        if ($instructorId && !$instructor->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_instructor')->__('This instructor no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getInstructorData(true)) {
            $instructor->setData($data);
        }
        $this->_title($instructor->getIname());
        Mage::dispatchEvent(
            'bs_instructor_instructor_edit_action',
            array('instructor' => $instructor)
        );
        $this->loadLayout();
        if ($instructor->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('bs_instructor')->__('Default Values'))
                    ->setWebsiteIds($instructor->getWebsiteIds())
                    ->setSwitchUrl(
                        $this->getUrl(
                            '*/*/*',
                            array(
                                '_current'=>true,
                                'active_tab'=>null,
                                'tab' => null,
                                'store'=>null
                            )
                        )
                    );
            }
        } else {
            $this->getLayout()->getBlock('left')->unsetChild('store_switcher');
        }
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * save instructor action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $instructorId   = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {

            $instructor     = $this->_initInstructor();

            $vaecoId = $data['instructor']['ivaecoid'];
            $check = $this->checkInstructor($vaecoId);
            $pass = true;

            if(!$instructor->getId() && $check){
                $pass = false;
            }

            if($pass){

                $instructorData = $this->getRequest()->getPost('instructor', array());
                $instructor->addData($instructorData);
                $instructor->setAttributeSetId($instructor->getDefaultAttributeSetId());
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $instructor->setProductsData(
                        Mage::helper('adminhtml/js')->decodeGridSerializedInput($products)
                    );
                }

                $curriculums = $this->getRequest()->getPost('curriculums', -1);
                if ($curriculums != -1) {
                    $instructor->setCurriculumsData(
                        Mage::helper('adminhtml/js')->decodeGridSerializedInput($curriculums)
                    );
                }

                $categories = $this->getRequest()->getPost('category_ids', -1);
                if ($categories != -1) {
                    $categories = explode(',', $categories);
                    $categories = array_unique($categories);
                    $instructor->setCategoriesData($categories);
                }
                if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                    foreach ($useDefaults as $attributeCode) {
                        $instructor->setData($attributeCode, false);
                    }
                }
                try {
                    $instructor->save();
                    $instructorId = $instructor->getId();
                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_instructor')->__('Instructor was saved')
                    );
                } catch (Mage_Core_Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError($e->getMessage())
                        ->setInstructorData($instructorData);
                    $redirectBack = true;
                } catch (Exception $e) {
                    Mage::logException($e);
                    $this->_getSession()->addError(
                        Mage::helper('bs_instructor')->__('Error saving instructor')
                    )
                        ->setInstructorData($instructorData);
                    $redirectBack = true;
                }
            }else {
                $this->_getSession()->addError(
                    Mage::helper('bs_instructor')->__('The instructor already existed!')
                );
                $this->_redirect('*/*/', array('store'=>$storeId));
            }

        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => $instructorId,
                    '_current'=>true
                )
            );
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }

    public function checkInstructor($vaecoId){
        $instructor = Mage::getModel('bs_instructor/instructor')->getCollection()->addAttributeToFilter('ivaecoid', $vaecoId)->getFirstItem();
        if($instructor->getId()){
            return true;
        }
        return false;
    }

    public function generateFortytwoAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $instructor = Mage::getModel('bs_instructor/instructor')->load($id);
            try {

                $this->generateFortytwo($instructor);

            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store')))
        );
    }
    public function massGenerateFortytwoAction()
    {
        $instructorIds = $this->getRequest()->getParam('instructor');
        if (!is_array($instructorIds)) {
            $this->_getSession()->addError($this->__('Please select instructors.'));
        } else {
            try {
                foreach ($instructorIds as $instructorId) {
                    $instructor = Mage::getSingleton('bs_instructor/instructor')->load($instructorId);
                    $this->generateFortytwo($instructor);

                }

            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    public function generateFortytwo($instructor){
        $template = Mage::helper('bs_formtemplate')->getFormtemplate('8042');
        $name = $instructor->getIname();
        $vaecoId = $instructor->getIvaecoid();
        $phone = $instructor->getIphone();
        $location = $instructor->getIfilefolder();
        $dob = '';
        $function = '';
        $dept = '';
        $today = Mage::getModel('core/date')->date("d/m/Y", time());
        $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();
        if($customer->getId()) {
            $cus = Mage::getModel('customer/customer')->load($customer->getId());
            $departmentId = $cus->getGroupId();
            $dob = $cus->getDob();
            $dob = Mage::getModel('core/date')->date("d/m/Y", $dob);


            $function = '';
            $staffFunc = $cus->getStaffFunction();
            if($staffFunc == 3){
                $function = 'Avionic';

            }elseif($staffFunc == 4){
                $function = 'Mechanic';
            }


            $group = Mage::getModel('customer/group')->load($departmentId);
            $dept = $group->getCustomerGroupCode();

        }


        $templateData = array(
            'name'  => $name,
            'id'    => $vaecoId,
            'dob'   => $dob,
            'dept'  => $dept,
            'phone' => $phone,
            'function'  => $function,
            'location'  => $location,
            'updated'  => $today,
            'instructor_no' => ''

        );

        $approvals = array();
        $others = array();

        //Get approved info
        $info = Mage::getModel('bs_instructorinfo/info')->getCollection()->addFieldToFilter('instructor_id', $instructor->getId());
        if($info->count()){
            $i=1;
            foreach ($info as $item) {
                $compliance = Mage::getSingleton('bs_instructorinfo/info_attribute_source_compliancewith')->getOptionText($item->getComplianceWith());
                $approvedCourse = $item->getApprovedCourse();
                $approvedFunction = $item->getApprovedFunction();
                $doc = $item->getApprovedDoc();
                $approvedDate = '';
                $expireDate = '';
                if($item->getApprovedDate() != ''){
                    $approvedDate = Mage::getModel('core/date')->date("d/m/Y", $item->getApprovedDate());
                }

                if($item->getExpireDate() != ''){
                    $expireDate = Mage::getModel('core/date')->date("d/m/Y", $item->getExpireDate());
                }


                //$compliance$	$course$	$app_func$	$app_doc$	$app_date$	$ex_date$
                $approvals[] = array(
                    'no'    => $i,
                    'compliance' => $compliance,
                    'course'    => $approvedCourse,
                    'app_func'  => $approvedFunction,
                    'app_doc'   => $doc,
                    'app_date'  => $approvedDate,
                    'ex_date'   => $expireDate

                );

                $i++;
            }

        }

        $otherinfo = Mage::getModel('bs_instructorinfo/otherinfo')->getCollection()->addFieldToFilter('instructor_id', $instructor->getId());

        if($otherinfo->count()){
            $j=1;
            //$doc_title$	$country$	$start_date$	$end_date$	$cert$	$remark$
            foreach ($otherinfo as $item) {
                $startDate = '';
                if($item->getStartDate() != ''){
                    $startDate = Mage::getModel('core/date')->date("d/m/Y", $item->getStartDate());
                }
                $endDate = '';
                if($item->getEndDate() != ''){
                    $endDate = Mage::getModel('core/date')->date("d/m/Y", $item->getEndDate());
                }
                $others[] = array(
                    'n3'   => $j,
                    'doc_title' => $item->getTitle(),
                    'country'   => $item->getCountry(),
                    'start_date'    => $startDate,
                    'end_date'  => $endDate,
                    'cert'  => $item->getCertInfo(),
                    'remark'    => ''

                );

                $j++;
            }

        }

        $tableData = array($approvals, $others);


        try {
            $res = Mage::helper('bs_traininglist/docx')->generateDocx(Mage::helper('bs_traininglist')->convertToUnsign($name).'_8042', $template, $templateData, $tableData);


            $this->_getSession()->addSuccess(
                Mage::helper('bs_instructor')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );


        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }


    }
    /**
     * delete instructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $instructor = Mage::getModel('bs_instructor/instructor')->load($id);
            try {
                $instructor->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_instructor')->__('The instructors has been deleted.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store')))
        );
    }

    /**
     * mass delete instructors
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $instructorIds = $this->getRequest()->getParam('instructor');
        if (!is_array($instructorIds)) {
            $this->_getSession()->addError($this->__('Please select instructors.'));
        } else {
            try {
                foreach ($instructorIds as $instructorId) {
                    $instructor = Mage::getSingleton('bs_instructor/instructor')->load($instructorId);
                    Mage::dispatchEvent(
                        'bs_instructor_controller_instructor_delete',
                        array('instructor' => $instructor)
                    );
                    $instructor->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_instructor')->__('Total of %d record(s) have been deleted.', count($instructorIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massUpdateInfoAction()
    {
        $instructorIds = $this->getRequest()->getParam('instructor');
        if (!is_array($instructorIds)) {
            $this->_getSession()->addError($this->__('Please select instructors.'));
        } else {
            try {
                foreach ($instructorIds as $instructorId) {
                    $instructor = Mage::getSingleton('bs_instructor/instructor')->load($instructorId);

                    $vaecoId = $instructor->getIvaecoid();

                    $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('*')->addAttributeToFilter('vaeco_id', $vaecoId)->getFirstItem();

                    if($customer->getId()){

                        $division = $customer->getDivision();
                        $departmentId = $customer->getGroupId();

                        $group = Mage::getModel('customer/group')->load($departmentId);
                        $department = $group->getCustomerGroupCodeVi();

                        $data = array(
                            'iphone' => $customer->getPhone(),
                            'iusername' => $customer->getUsername(),
                            'iposition' => $customer->getPosition(),
                            'idivision_department' => $division.' - '. $department
                        );


                        $instructor->setIphone($customer->getPhone())
                            ->setIusername($customer->getUsername())
                            ->setIposition($customer->getPosition())
                            ->setIdivisionDepartment($division.' - '. $department)
                            ->setIsMassupdate(true)
                            ->save();
                        //$instructor->addData($data);
                        //$instructor->save();

                    }


                }
                $this->_getSession()->addSuccess(
                    Mage::helper('bs_instructor')->__('Total of %d record(s) have been updated.', count($instructorIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massFilefolderAction()
    {
        $instructorIds = $this->getRequest()->getParam('instructor');
        if (!is_array($instructorIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructor')->__('Please select instructors.')
            );
        } else {
            try {
                $filefolder = $this->getRequest()->getParam('ifilefolder');
                foreach ($instructorIds as $instructorId) {
                    $instructor = Mage::getModel('bs_instructor/instructor')->load($instructorId);
                    $instructor->setData('ifilefolder',$filefolder);
                        //->setIsMassupdate(true)
                    $instructor->save();

                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructors were successfully updated.', count($instructorIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructor')->__('There was an error updating instructors.')
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
        $instructorIds = $this->getRequest()->getParam('instructor');
        if (!is_array($instructorIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructor')->__('Please select instructors.')
            );
        } else {
            try {
                foreach ($instructorIds as $instructorId) {
                $instructor = Mage::getSingleton('bs_instructor/instructor')->load($instructorId)
                    ->setStatus($this->getRequest()->getParam('status'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructors were successfully updated.', count($instructorIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructor')->__('There was an error updating instructors.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
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
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * restrict access
     *
     * @access protected
     * @return bool
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     * @author Bui Phong
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/bs_instructor/instructor');
    }

    /**
     * Export instructors in CSV format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportCsvAction()
    {
        $fileName   = 'instructors.csv';
        $content    = $this->getLayout()->createBlock('bs_instructor/adminhtml_instructor_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export instructors in Excel format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportExcelAction()
    {
        $fileName   = 'instructor.xls';
        $content    = $this->getLayout()->createBlock('bs_instructor/adminhtml_instructor_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export instructors in XML format
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function exportXmlAction()
    {
        $fileName   = 'instructor.xml';
        $content    = $this->getLayout()->createBlock('bs_instructor/adminhtml_instructor_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * wysiwyg editor action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function wysiwygAction()
    {
        $elementId     = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId       = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'bs_instructor/adminhtml_instructor_helper_form_wysiwyg_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id'          => $storeId,
                'store_media_url'   => $storeMediaUrl,
            )
        );
        $this->getResponse()->setBody($content->toHtml());
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
        $this->_initInstructor();
        $this->loadLayout();
        $this->getLayout()->getBlock('instructor.edit.tab.product')
            ->setInstructorProducts($this->getRequest()->getPost('instructor_products', null));
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
        $this->_initInstructor();
        $this->loadLayout();
        $this->getLayout()->getBlock('instructor.edit.tab.product')
            ->setInstructorProducts($this->getRequest()->getPost('instructor_products', null));
        $this->renderLayout();
    }

    public function curriculumsAction()
    {
        $this->_initInstructor();
        $this->loadLayout();
        $this->getLayout()->getBlock('instructor.edit.tab.curriculum')
            ->setInstructorCurriculums($this->getRequest()->getPost('instructor_curriculums', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function curriculumsgridAction()
    {
        $this->_initInstructor();
        $this->loadLayout();
        $this->getLayout()->getBlock('instructor.edit.tab.curriculum')
            ->setInstructorCurriculums($this->getRequest()->getPost('instructor_curriculums', null));
        $this->renderLayout();
    }

    /**
     * get categories action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function categoriesAction()
    {
        $this->_initInstructor();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * get child categories action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function categoriesJsonAction()
    {
        $this->_initInstructor();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('bs_instructor/adminhtml_instructor_edit_tab_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }
}
