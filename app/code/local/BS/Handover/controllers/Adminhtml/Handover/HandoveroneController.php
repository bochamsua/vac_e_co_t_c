<?php
/**
 * BS_Handover extension
 * 
 * @category       BS
 * @package        BS_Handover
 * @copyright      Copyright (c) 2015
 */
/**
 * Minutes of Handover V1 admin controller
 *
 * @category    BS
 * @package     BS_Handover
 * @author Bui Phong
 */
class BS_Handover_Adminhtml_Handover_HandoveroneController extends BS_Handover_Controller_Adminhtml_Handover
{
    /**
     * init the minutes of handover v1
     *
     * @access protected
     * @return BS_Handover_Model_Handoverone
     */
    protected function _initHandoverone()
    {
        $handoveroneId  = (int) $this->getRequest()->getParam('id');
        $handoverone    = Mage::getModel('bs_handover/handoverone');
        if ($handoveroneId) {
            $handoverone->load($handoveroneId);
        }
        Mage::register('current_handoverone', $handoverone);
        return $handoverone;
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
        $this->_title(Mage::helper('bs_handover')->__('Minutes of Handover'))
             ->_title(Mage::helper('bs_handover')->__('Minutes of Handovers V1'));
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
     * edit minutes of handover v1 - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $handoveroneId    = $this->getRequest()->getParam('id');
        $handoverone      = $this->_initHandoverone();
        if ($handoveroneId && !$handoverone->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_handover')->__('This minutes of handover v1 no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getHandoveroneData(true);
        if (!empty($data)) {
            $handoverone->setData($data);
        }
        Mage::register('handoverone_data', $handoverone);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_handover')->__('Minutes of Handover'))
             ->_title(Mage::helper('bs_handover')->__('Minutes of Handovers V1'));
        if ($handoverone->getId()) {
            $this->_title($handoverone->getTitle());
        } else {
            $this->_title(Mage::helper('bs_handover')->__('Add minutes of handover v1'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new minutes of handover v1 action
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
     * save minutes of handover v1 - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('handoverone')) {
            try {
                $data = $this->_filterDates($data, array('send_date'));
                $handoverone = $this->_initHandoverone();
                $handoverone->addData($data);
                $handoverone->save();


                //Generate stuff
                $template = Mage::helper('bs_formtemplate')->getFormtemplate('f11-1');

                $date = $data['send_date'];
                $day = Mage::getModel('core/date')->date("d", $date);
                $month = Mage::getModel('core/date')->date("m", $date);
                $year = Mage::getModel('core/date')->date("Y", $date);
                $currentUser = Mage::getSingleton('admin/session')->getUser();
                $preparedBy = Mage::helper('core')->escapeHtml($currentUser->getFirstname().' '.$currentUser->getLastname());
                $contents = explode("\r\n", $data['content']);
                $tableData = array();
                $total = 0;
                if(count($contents)){

                    foreach ($contents as $item) {

                        $items = explode("---", $item);
                        if(count($items)){
                            $name = trim($items[0]);
                            $qty = 0;
                            $note = '';
                            if(isset($items[1])){
                                $qty = (int)trim($items[1]);
                                $total += $qty;
                            }
                            if(isset($items[2])){
                                $note = trim($items[2]);
                            }

                            $tableData[] = array(
                                    'item' => $name,
                                    'qty'   => $qty.' bộ',
                                    'note'  => $note
                            );
                        }


                    }

                }
                $receiver = $data['receiver'];
                $id = trim($receiver);
                if(strlen($id) == 5){
                    $id = "VAE".$id;
                }elseif (strlen($id) == 4){
                    $id = "VAE0".$id;
                }
                $name = '';
                $id = strtoupper($id);
                $customer = Mage::getModel('customer/customer')->getCollection()->addAttributeToFilter('vaeco_id', $id)->getFirstItem();
                if($customer->getId()) {
                    $cus = Mage::getModel('customer/customer')->load($customer->getId());
                    $name = $cus->getName();


                }

                $tableData = array($tableData);

                $templateData = array(
                    'title'  => $data['title'],
                    'day'    => $day,
                    'month'   => $month,
                    'year'  => $year,
                    'total' => $total.' bộ',
                    'prepared_by'  => $preparedBy,
                    'receiver'  => $name
                );


                try {
                    $res = Mage::helper('bs_traininglist/docx')->generateDocx($date.'F11-V1', $template, $templateData,$tableData);


                    $this->_getSession()->addSuccess(
                        Mage::helper('bs_traininglist')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
                    );




                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }


               /* $add = '';
                if($this->getRequest()->getParam('popup')){
                    $add = '<script>window.close()</script>';
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_handover')->__('Minutes of Handover V1 was successfully saved. %s', $add)
                );*/
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $handoverone->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setHandoveroneData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_handover')->__('There was a problem saving the minutes of handover v1.')
                );
                Mage::getSingleton('adminhtml/session')->setHandoveroneData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_handover')->__('Unable to find minutes of handover v1 to save.')
        );
        $this->_redirect('*/*/');
    }

    public function duplicateAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {


            $handoverone = Mage::getModel('bs_handover/handoverone')->load($id);

            try {

                $nHandover = Mage::getModel('bs_handover/handoverone');
                $data = $handoverone->getData();
                $data['entity_id'] = null;

                $nHandover->setData($data);
                $nHandover->save();

                $newId = $nHandover->getId();

                $this->_getSession()->addSuccess(
                    Mage::helper('bs_handover')->__('The handover has been duplicated.')
                );

                $this->getResponse()->setRedirect(
                    $this->getUrl('*/*/edit', array('id' => $newId))
                );

                return;

            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_handover')->__('Could not find minutes of handover v1 to duplicate.')
        );
        $this->_redirect('*/*/');


    }
    /**
     * delete minutes of handover v1 - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $handoverone = Mage::getModel('bs_handover/handoverone');
                $handoverone->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_handover')->__('Minutes of Handover V1 was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_handover')->__('There was an error deleting minutes of handover v1.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_handover')->__('Could not find minutes of handover v1 to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete minutes of handover v1 - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $handoveroneIds = $this->getRequest()->getParam('handoverone');
        if (!is_array($handoveroneIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_handover')->__('Please select minutes of handovers v1 to delete.')
            );
        } else {
            try {
                foreach ($handoveroneIds as $handoveroneId) {
                    $handoverone = Mage::getModel('bs_handover/handoverone');
                    $handoverone->setId($handoveroneId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_handover')->__('Total of %d minutes of handovers v1 were successfully deleted.', count($handoveroneIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_handover')->__('There was an error deleting minutes of handovers v1.')
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
        $handoveroneIds = $this->getRequest()->getParam('handoverone');
        if (!is_array($handoveroneIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_handover')->__('Please select minutes of handovers v1.')
            );
        } else {
            try {
                foreach ($handoveroneIds as $handoveroneId) {
                $handoverone = Mage::getSingleton('bs_handover/handoverone')->load($handoveroneId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d minutes of handovers v1 were successfully updated.', count($handoveroneIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_handover')->__('There was an error updating minutes of handovers v1.')
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
        $fileName   = 'handoverone.csv';
        $content    = $this->getLayout()->createBlock('bs_handover/adminhtml_handoverone_grid')
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
        $fileName   = 'handoverone.xls';
        $content    = $this->getLayout()->createBlock('bs_handover/adminhtml_handoverone_grid')
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
        $fileName   = 'handoverone.xml';
        $content    = $this->getLayout()->createBlock('bs_handover/adminhtml_handoverone_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_material/bs_handover/handoverone');
    }
}
