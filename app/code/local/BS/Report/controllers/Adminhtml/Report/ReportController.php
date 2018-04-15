<?php
/**
 * BS_Report extension
 * 
 * @category       BS
 * @package        BS_Report
 * @copyright      Copyright (c) 2015
 */
/**
 * Individual Report admin controller
 *
 * @category    BS
 * @package     BS_Report
 * @author Bui Phong
 */
class BS_Report_Adminhtml_Report_ReportController extends BS_Report_Controller_Adminhtml_Report
{
    /**
     * init the individual report
     *
     * @access protected
     * @return BS_Report_Model_Report
     */
    protected function _initReport()
    {
        $reportId  = (int) $this->getRequest()->getParam('id');
        $report    = Mage::getModel('bs_report/report');
        if ($reportId) {
            $report->load($reportId);
        }
        Mage::register('current_report', $report);
        return $report;
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
        $this->_title(Mage::helper('bs_report')->__('Report'))
             ->_title(Mage::helper('bs_report')->__('Reports'));

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
        $this->loadLayout();

        $this->renderLayout();
    }

    public function reportAction()
    {
        $requestData = Mage::helper('adminhtml')->prepareFilterString($this->getRequest()->getParam('filter'));
        $requestData = $this->_filterDates($requestData, array('from', 'to'));



        $result = Mage::helper('bs_report')->getReportStatistic($requestData, 'user');


        if(count($result)){

            $templateData = array(
                'duration' => $result['duration']
            );

            $template = Mage::helper('bs_formtemplate')->getFormtemplate('report');
            $contentHtml = array(
                'type' => 'replace',
                'content' => Mage::helper('bs_report')->prepareReport($result['data']),
                'variable' => 'content'
            );


            $res = Mage::helper('bs_traininglist/docx')->generateDocx('EMPLOYEE REPORT USER', $template, $templateData, null, null,null,null,null,null,$contentHtml);

            $this->_getSession()->addSuccess(Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name']));

        }else {
            $this->_getSession()->addNotice(Mage::helper('bs_traininglist')->__('There is no record found!'));
        }




        $this->_redirect('*/*/');
    }

    /**
     * edit individual report - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {


        $reportId    = $this->getRequest()->getParam('id');
        $report      = $this->_initReport();
        if ($reportId && !$report->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_report')->__('This individual report no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }

        if(!$report->getId()){
            if($this->isReportCreated()){
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('You have already done your report for today!')
                );
                $this->_redirect('*/*/');

                return;
            }
        }

        $data = Mage::getSingleton('adminhtml/session')->getReportData(true);
        if (!empty($data)) {
            $report->setData($data);
        }
        Mage::register('report_data', $report);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_report')->__('Report'))
             ->_title(Mage::helper('bs_report')->__('Reports'));
        if ($report->getId()) {
            $this->_title($report->getBrief());
        } else {
            $this->_title(Mage::helper('bs_report')->__('Add individual report'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    public function isReportCreated(){
        $myTimezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $now = new DateTime('now', $myTimezone);
        $hour = intval($now->format('H'));
        if($hour < 12){
            $now->sub(new DateInterval('P1D'));
        }
        $today = $now->format('Y-m-d');
        $start = $today.'00:00:00';
        $end = $today.'23:59:59';

        $reports = Mage::getModel('bs_report/report')->getCollection()
            ->addFieldToFilter('user_id', $currentUser = Mage::getSingleton('admin/session')->getUser()->getId())
            ->addFieldToFilter('created_at', array('from'=>$start,'to'=>$end, 'date' => true))
        ;

        //$sql = $reports->getSelect()->__toString();

        if($reports->count()){
            return true;
        }

        return false;



    }

    /**
     * new individual report action
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
     * save individual report - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('report')) {
            try {

                $data = $this->_filterDates($data, array('expected_date'));

                $rate1 = $data['rate_one'];
                $rate2 = $data['rate_two'];
                $rate3 = $data['rate_three'];

                //check saving type: multiple or single
                $check = reset($data);

                if(!is_array($check)){//single submit, editing case
                    $report = $this->_initReport();
                    $report->addData($data);
                    $report->save();



                }else {
                    if($data['task_ids'] != ''){
                        $taskIds = explode(",", $data['task_ids']);
                        foreach ($taskIds as $taskId) {
                            $task = Mage::getSingleton('bs_report/report')->load($taskId)->setRateOne($rate1)->setRateTwo($rate2)->setRateThree($rate3)->setIsMassupdate(true)->save();
                        }

                        Mage::getSingleton('adminhtml/session')->addSuccess(
                            Mage::helper('bs_report')->__('Total of %d reports were successfully rated.', count($taskIds))
                        );

                        $this->_redirect('*/report_supervisor/');
                        return;

                    }else {

                        $currentUser = Mage::getSingleton('admin/session')->getUser();
                        foreach ($data as $item) {
                            $report = Mage::getModel('bs_report/report');//$this->_initReport();

                            if(intval($item['tctask_id']) != 55){
                                unset($item['supervisor_id']);
                            }


                            if(!$report->getId()){

                                $item['user_id'] = $currentUser->getId();
                                $item['brief'] = 'Report for date '.date("d-m-Y", Mage::getModel('core/date')->timestamp(time()));
                            }

                            if($manage = $this->getRequest()->getParam('backto')){


                                //$data = $report->getData();
                                $item['rate_one'] = $rate1;
                                $item['rate_two'] = $rate2;
                                $item['rate_three'] = $rate3;


                            }

                            //get supervisor Id
                            if(!isset($item['supervisor_id'])){
                                $taskId = $item['tctask_id'];
                                $task = Mage::getModel('bs_report/tctask')->load($taskId);

                                $isSouthern = $currentUser->getSouthern();
                                if($isSouthern){
                                    $supervisorId = $task->getSouthernSupervisorId();
                                }else {
                                    $supervisorId = $task->getSupervisorId();
                                }


                                $item['supervisor_id'] = $supervisorId;
                            }





                            $report->addData($item);
                            $report->save();
                        }

                        $add = '';
                        if($this->getRequest()->getParam('popup')){
                            $add = '<script>window.close()</script>';
                        }
                        Mage::getSingleton('adminhtml/session')->addSuccess(
                            Mage::helper('bs_report')->__('Thank you for your report. Enjoy your time! %s', $add)
                        );
                        Mage::getSingleton('adminhtml/session')->setFormData(false);
                        if ($this->getRequest()->getParam('back')) {
                            $this->_redirect('*/*/edit', array('id' => $report->getId()));
                            return;
                        }
                        if($manage){
                            $this->_redirect('*/report_manage/');
                            return;
                        }
                        $this->_redirect('*/*/');
                        return;

                    }
                }

                
                $this->_redirect('*/*/');
                return;


            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setReportData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was a problem saving the individual report.')
                );
                Mage::getSingleton('adminhtml/session')->setReportData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_report')->__('You haven\'t make report yet!')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete individual report - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $report = Mage::getModel('bs_report/report');
                $report->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_report')->__('Individual Report was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error deleting individual report.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_report')->__('Could not find individual report to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete individual report - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $reportIds = $this->getRequest()->getParam('report');
        if (!is_array($reportIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select reports to delete.')
            );
        } else {
            try {
                foreach ($reportIds as $reportId) {
                    $report = Mage::getModel('bs_report/report');
                    $report->setId($reportId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_report')->__('Total of %d reports were successfully deleted.', count($reportIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error deleting reports.')
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
        $reportIds = $this->getRequest()->getParam('report');
        if (!is_array($reportIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select reports.')
            );
        } else {
            try {
                foreach ($reportIds as $reportId) {
                $report = Mage::getSingleton('bs_report/report')->load($reportId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d reports were successfully updated.', count($reportIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error updating reports.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Complete (%) change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massCompleteAction()
    {
        $reportIds = $this->getRequest()->getParam('report');
        if (!is_array($reportIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select reports.')
            );
        } else {
            try {
                foreach ($reportIds as $reportId) {
                $report = Mage::getSingleton('bs_report/report')->load($reportId)
                    ->setComplete($this->getRequest()->getParam('flag_complete'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d reports were successfully updated.', count($reportIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error updating reports.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Status change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massTaskStatusAction()
    {
        $reportIds = $this->getRequest()->getParam('report');
        if (!is_array($reportIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select reports.')
            );
        } else {
            try {
                foreach ($reportIds as $reportId) {
                    $report = Mage::getSingleton('bs_report/report')->load($reportId)
                        ->setTaskStatus($this->getRequest()->getParam('flag_task_status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d reports were successfully updated.', count($reportIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error updating reports.')
                );
                Mage::logException($e);
            }
        }
        if($manage = $this->getRequest()->getParam('backto')){
            $this->_redirect('*/report_manage/index');
            return;
        }
        $this->_redirect('*/*/index');
    }

    public function massRateOneAction()
    {
        $reportIds = $this->getRequest()->getParam('report');
        if (!is_array($reportIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select reports.')
            );
        } else {
            try {
                foreach ($reportIds as $reportId) {
                    $report = Mage::getSingleton('bs_report/report')->load($reportId)
                        ->setRateOne($this->getRequest()->getParam('flag_rate_one'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d reports were successfully updated.', count($reportIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error updating reports.')
                );
                Mage::logException($e);
            }
        }
        if($manage = $this->getRequest()->getParam('backto')){
            $this->_redirect('*/report_manage/index');
            return;
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Rate Two change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massRateTwoAction()
    {
        $reportIds = $this->getRequest()->getParam('report');
        if (!is_array($reportIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select reports.')
            );
        } else {
            try {
                foreach ($reportIds as $reportId) {
                    $report = Mage::getSingleton('bs_report/report')->load($reportId)
                        ->setRateTwo($this->getRequest()->getParam('flag_rate_two'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d reports were successfully updated.', count($reportIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error updating reports.')
                );
                Mage::logException($e);
            }
        }
        if($manage = $this->getRequest()->getParam('backto')){
            $this->_redirect('*/report_manage/index');
            return;
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Rate Three change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massRateThreeAction()
    {
        $reportIds = $this->getRequest()->getParam('report');
        if (!is_array($reportIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_report')->__('Please select reports.')
            );
        } else {
            try {
                foreach ($reportIds as $reportId) {
                    $report = Mage::getSingleton('bs_report/report')->load($reportId)
                        ->setRateThree($this->getRequest()->getParam('flag_rate_three'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d reports were successfully updated.', count($reportIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_report')->__('There was an error updating reports.')
                );
                Mage::logException($e);
            }
        }
        if($manage = $this->getRequest()->getParam('backto')){
            $this->_redirect('*/report_manage/index');
            return;
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
        $fileName   = 'report.csv';
        $content    = $this->getLayout()->createBlock('bs_report/adminhtml_report_grid')
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
        $fileName   = 'report.xls';
        $content    = $this->getLayout()->createBlock('bs_report/adminhtml_report_grid')
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
        $fileName   = 'report.xml';
        $content    = $this->getLayout()->createBlock('bs_report/adminhtml_report_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_report/report');
    }
}
