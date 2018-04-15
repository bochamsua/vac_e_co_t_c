<?php
/**
 * BS_InstructorApproval extension
 * 
 * @category       BS
 * @package        BS_InstructorApproval
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor Approval admin controller
 *
 * @category    BS
 * @package     BS_InstructorApproval
 * @author Bui Phong
 */
class BS_InstructorApproval_Adminhtml_Instructorapproval_IapprovalController extends BS_InstructorApproval_Controller_Adminhtml_InstructorApproval
{
    /**
     * init the instructor approval
     *
     * @access protected
     * @return BS_InstructorApproval_Model_Iapproval
     */
    protected function _initIapproval()
    {
        $iapprovalId  = (int) $this->getRequest()->getParam('id');
        $iapproval    = Mage::getModel('bs_instructorapproval/iapproval');
        if ($iapprovalId) {
            $iapproval->load($iapprovalId);
        }
        Mage::register('current_iapproval', $iapproval);
        return $iapproval;
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
        $this->_title(Mage::helper('bs_instructorapproval')->__('Instructor Approval'))
             ->_title(Mage::helper('bs_instructorapproval')->__('Instructor Approval'));
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
     * edit instructor approval - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $iapprovalId    = $this->getRequest()->getParam('id');
        $iapproval      = $this->_initIapproval();
        if ($iapprovalId && !$iapproval->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_instructorapproval')->__('This instructor approval no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getIapprovalData(true);
        if (!empty($data)) {
            $iapproval->setData($data);
        }
        Mage::register('iapproval_data', $iapproval);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_instructorapproval')->__('Instructor Approval'))
             ->_title(Mage::helper('bs_instructorapproval')->__('Instructor Approval'));
        if ($iapproval->getId()) {
            $this->_title($iapproval->getIapprovalTitle());
        } else {
            $this->_title(Mage::helper('bs_instructorapproval')->__('Add instructor approval'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new instructor approval action
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
     * save instructor approval - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('iapproval')) {
            try {

                $saveonly = $this->getRequest()->getParam('save',false);
                $finalDir = Mage::getBaseDir('media').DS.'files'.DS;

                $finalUrl = Mage::getBaseUrl('media').'/files/';



                $data = $this->_filterDates($data, array('iapproval_date'));
                $iapproval = $this->_initIapproval();
                $iapproval->addData($data);
                $iapproval->save();

                $compress = $data['compress'];

                if(!$saveonly){

                    $files = $this->generateApproval($data, $compress);
                }

                if(count($files)){
                    $zip = Mage::helper('bs_traininglist/docx')->zipFiles($files, 'instructor-approvals');
                    if($zip){
                        $this->_getSession()->addSuccess(
                            Mage::helper('bs_instructorapproval')->__('Generated files have been zipped. Click <a href="%s" target="_blank">%s</a> to download.', $zip, 'HERE')
                        );
                    }
                }


                $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructorapproval')->__('Instructor Approval was successfully saved. %s', $add)
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                $this->_redirect('*/*/edit', array('id' => $iapproval->getId()));
                return;

                //$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                //return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setIapprovalData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorapproval')->__('There was a problem saving the instructor approval.')
                );
                Mage::getSingleton('adminhtml/session')->setIapprovalData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_instructorapproval')->__('Unable to find instructor approval to save.')
        );
        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
        //$this->_redirect('*/*/');
    }


    public function generateApproval($data, $compress = false){
        $files = array();

        $template = Mage::helper('bs_formtemplate')->getFormtemplate('2067');
        $template8019 = Mage::helper('bs_formtemplate')->getFormtemplate('8019');
        $template2068 = Mage::helper('bs_formtemplate')->getFormtemplate('2068');
        $template2069 = Mage::helper('bs_formtemplate')->getFormtemplate('2069');

        if($data['pna'] != ''){
            $template = Mage::helper('bs_formtemplate')->getFormtemplate('2067-TC');
        }

        $vaecoIds = explode("\r\n", $data['vaeco_ids']);
        $type = $data['type'];

        $inclKeyword = $data['include_keyword'];
        if($inclKeyword != ''){
            $inclKeyword = explode(",", $inclKeyword);
        }


        $exclKeyword = $data['exclude_keyword'];
        if($exclKeyword != ''){
            $exclKeyword = explode(",", $exclKeyword);
        }

        $i=1;


        $result = array();
        foreach ($vaecoIds as $item) {


            $item = explode("--", $item);

            if(count($item) > 0){
                $id = trim($item[0]);
                $course = trim($item[1]);
                $organization = trim($item[2]);
                $year = trim($item[3]);
                $cert = trim($item[4]);

                if (strlen($id) == 5) {
                    $id = "VAE" . $id;
                } elseif (strlen($id) == 4) {
                    $id = "VAE0" . $id;
                }
                $id = strtoupper($id);


                if(count($item) > 1){
                    $result[$id][] = array(
                        'course'    => $course,
                        'organization'  => $organization,
                        'year'  => $year,
                        'cert'  => $cert
                    );
                }else {
                    $result[$id][] = 1;
                }





            }

        }

        if($data['search_info']){
            foreach ($result as $key => $value ) {
                $more = Mage::helper('bs_instructorapproval')->getRelatedTraining($key, $inclKeyword, $exclKeyword);
                if($more){
                    foreach ($more as $m) {

                        $year = new DateTime($m['start']);
                        $year = $year->format("Y");

                        $result[$key][] = array(
                            'course'    => $m['course'],
                            'organization'  => $m['country'],
                            'year'  => $year,
                            'cert'  => $m['cert']
                        );
                    }

                }

            }
        }



        $working = explode("\r\n", $data['related_working']);
        $relatedWorking = array();
        if(count($working)){
            foreach ($working as $item) {
                $item = explode("--", $item);

                if(count($item) > 1){
                    $id = trim($item[0]);
                    $location = trim($item[1]);
                    $job = trim($item[2]);
                    $time = trim($item[3]);

                    if (strlen($id) == 5) {
                        $id = "VAE" . $id;
                    } elseif (strlen($id) == 4) {
                        $id = "VAE0" . $id;
                    }
                    $id = strtoupper($id);

                    $relatedWorking[$id][] = array(
                        'location'    => $location,
                        'job'  => $job,
                        'time'  => $time,
                    );
                }

            }

        }

        if(count($result)){
            $j=1;
            foreach ($result as $key => $item) {

                $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $key)->getFirstItem();
                if($customer->getId()){
                    $cus = Mage::getModel('customer/customer')->load($customer->getId());
                    $name = $cus->getName();
                    $phone = $cus->getPhone();
                    $division = $cus->getDivision();
                    $departmentId = $cus->getGroupId();
                    $dob = $cus->getDob();
                    $dob = Mage::getModel('core/date')->date("d/m/Y", $dob);


                    $function = '';
                    $staffFunc = $cus->getStaffFunction();
                    if($staffFunc == 3){
                        $function = ' - (Avionic)';

                    }elseif($staffFunc == 4){
                        $function = ' - (Mechanic)';
                    }


                    $group = Mage::getModel('customer/group')->load($departmentId);
                    $dept = $group->getCustomerGroupNameVi();

                    $compliance = $data['iapproval_compliance'];
                    $others = '';
                    $checkbox = array();
                    if(in_array(1, $compliance)){
                        $checkbox['mtoe'] = 1;
                    }
                    if(in_array(2, $compliance)){
                        $checkbox['amotp'] = 1;
                    }
                    if(in_array(3, $compliance)){
                        $checkbox['rstp'] = 1;
                    }
                    if(in_array(4, $compliance)){
                        $checkbox['others'] = 1;
                        $others = $data['iapproval_compliance_other'];
                    }
                    if($type){
                        $checkbox['supplement'] = 1;
                    }else {
                        $checkbox['initial'] = 1;
                    }

                    $date = $data['iapproval_date'];
                    $date = Mage::getModel('core/date')->date("d/m/Y", $date);


                    $approvedFunction = $data['iapproval_function'];
                    if($function != '' && $data['include_function'] == 1){
                        $approvedFunction = $approvedFunction.$function;
                    }

                    $templateData = array(
                        'name'  => $name,
                        'id'    => $key,
                        'dob'   => $dob,
                        'division'  => $division,
                        'dept'  => $dept,
                        'phone' => $phone,
                        'title'  => $data['iapproval_title'],
                        'pna'       => $data['pna'],
                        'function'  => $approvedFunction,
                        'function_8019'=>$data['iapproval_function'],
                        'ref'       => $key,
                        'others'    => $others,
                        'date'  => $date
                    );


                    $related = array();
                    if(count($item)){
                        foreach ($item as $re) {
                            if(is_array($re)){
                                $related[] = array(
                                    'course' => $re['course'],
                                    'organization' => $re['organization'],
                                    'year' => $re['year'],
                                    'cert' => $re['cert']
                                );
                            }

                        }
                    }else {
                        $templateData['course'] = '';
                        $templateData['organization'] = '';
                        $templateData['year'] = '';
                        $templateData['cert'] = '';

                    }

                    $works = array();

                    if(count($relatedWorking)){
                        if(isset($relatedWorking[$key])){
                            $items = $relatedWorking[$key];
                            foreach ($items as $re) {
                                $works[] = array(
                                    'location' => $re['location'],
                                    'job' => $re['job'],
                                    'time' => $re['time'],
                                );
                            }

                        }else {
                            $templateData['location'] = '';
                            $templateData['job'] = '';
                            $templateData['time'] = '';
                        }
                    }else {
                        $templateData['location'] = '';
                        $templateData['job'] = '';
                        $templateData['time'] = '';
                    }




                    $tableData = array($related, $works);

                    $templateData['subject'] = $data['evaluation_subject'];


                    try {
                        $res = Mage::helper('bs_traininglist/docx')->generateDocx(Mage::helper('bs_traininglist')->convertToUnsign($name).'_'.$key.'_2067_'.$data['iapproval_function'], $template, $templateData, $tableData, $checkbox);

                        if($compress){
                            $files[] = $res['url'];
                        }else {
                            $this->_getSession()->addSuccess(
                                Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                            );
                        }



                        if($data['pna'] != ''){
                            $res1 = Mage::helper('bs_traininglist/docx')->generateDocx(Mage::helper('bs_traininglist')->convertToUnsign($name).'_'.$key.'_8019_'.$data['iapproval_function'], $template8019, $templateData, null, $checkbox);

                            if($compress){
                                $files[] = $res1['url'];
                            }else {
                                $this->_getSession()->addSuccess(
                                    Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res1['url'], $res1['name'])
                                );
                            }


                        }

                        if($data['generate_evaluation']){

                            $res2 = Mage::helper('bs_traininglist/docx')->generateDocx(Mage::helper('bs_traininglist')->convertToUnsign($name).'_'.$key.'_2068_'.$data['iapproval_function'], $template2068, $templateData, null, $checkbox);

                            if($compress){
                                $files[] = $res2['url'];
                            }else {
                                $this->_getSession()->addSuccess(
                                    Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res2['url'], $res2['name'])
                                );
                            }

                            $res3 = Mage::helper('bs_traininglist/docx')->generateDocx(Mage::helper('bs_traininglist')->convertToUnsign($name).'_'.$key.'_2069_'.$data['iapproval_function'], $template2069, $templateData, null, $checkbox);

                            if($compress){
                                $files[] = $res3['url'];
                            }else {
                                $this->_getSession()->addSuccess(
                                    Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res3['url'], $res3['name'])
                                );
                            }


                        }



                    } catch (Exception $e) {
                        $this->_getSession()->addError($e->getMessage());
                    }



                }

                $j++;
            }

        }

        if($compress){
            return $files;
        }
    }

    public function massGenerateAction()
    {

        $iapprovalIds = $this->getRequest()->getParam('iapproval');
        if (!is_array($iapprovalIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructorapproval')->__('Please select instructor approval to delete.')
            );
        } else {
            try {
                $compress = $this->getRequest()->getParam('compress');
                $files = array();

                foreach ($iapprovalIds as $iapprovalId) {
                    $iapproval = Mage::getModel('bs_instructorapproval/iapproval')->load($iapprovalId);


                    if($compress){
                        $files = array_merge($files,$this->generateApproval($iapproval->getData(), $compress));
                    }else {
                        $this->generateApproval($iapproval->getData());
                    }

                }

                if($compress){
                    $zip = Mage::helper('bs_traininglist/docx')->zipFiles($files, 'instructor_approvals_'.Mage::getModel('core/date')->date("d.m.Y.H.i.s", time()));
                    if($zip){
                        $this->_getSession()->addSuccess(
                            Mage::helper('bs_instructorapproval')->__('Generated files have been zipped. Click <a target="_blank" href="%s">%s</a> to download.', $zip, 'HERE')
                        );
                    }
                }


            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorapproval')->__('There was an error generating instructor approvals.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');

    }

    /**
     * delete instructor approval - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $iapproval = Mage::getModel('bs_instructorapproval/iapproval');
                $iapproval->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructorapproval')->__('Instructor Approval was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorapproval')->__('There was an error deleting instructor approval.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_instructorapproval')->__('Could not find instructor approval to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete instructor approval - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $iapprovalIds = $this->getRequest()->getParam('iapproval');
        if (!is_array($iapprovalIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructorapproval')->__('Please select instructor approval to delete.')
            );
        } else {
            try {
                foreach ($iapprovalIds as $iapprovalId) {
                    $iapproval = Mage::getModel('bs_instructorapproval/iapproval');
                    $iapproval->setId($iapprovalId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_instructorapproval')->__('Total of %d instructor approval were successfully deleted.', count($iapprovalIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorapproval')->__('There was an error deleting instructor approval.')
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
        $iapprovalIds = $this->getRequest()->getParam('iapproval');
        if (!is_array($iapprovalIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_instructorapproval')->__('Please select instructor approval.')
            );
        } else {
            try {
                foreach ($iapprovalIds as $iapprovalId) {
                $iapproval = Mage::getSingleton('bs_instructorapproval/iapproval')->load($iapprovalId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d instructor approval were successfully updated.', count($iapprovalIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_instructorapproval')->__('There was an error updating instructor approval.')
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
        $fileName   = 'iapproval.csv';
        $content    = $this->getLayout()->createBlock('bs_instructorapproval/adminhtml_iapproval_grid')
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
        $fileName   = 'iapproval.xls';
        $content    = $this->getLayout()->createBlock('bs_instructorapproval/adminhtml_iapproval_grid')
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
        $fileName   = 'iapproval.xml';
        $content    = $this->getLayout()->createBlock('bs_instructorapproval/adminhtml_iapproval_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/bs_instructor/iapproval');
    }
}
