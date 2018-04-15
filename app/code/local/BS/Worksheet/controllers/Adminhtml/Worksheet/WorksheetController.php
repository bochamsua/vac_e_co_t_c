<?php
/**
 * BS_Worksheet extension
 * 
 * 
 * @category       BS
 * @package        BS_Worksheet
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet admin controller
 *
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
class BS_Worksheet_Adminhtml_Worksheet_WorksheetController extends BS_Worksheet_Controller_Adminhtml_Worksheet
{
    /**
     * init the worksheet
     *
     * @access protected
     * @return BS_Worksheet_Model_Worksheet
     */
    protected function _initWorksheet()
    {
        $worksheetId  = (int) $this->getRequest()->getParam('id');
        $worksheet    = Mage::getModel('bs_worksheet/worksheet');
        if ($worksheetId) {
            $worksheet->load($worksheetId);
        }
        Mage::register('current_worksheet', $worksheet);
        return $worksheet;
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
        $this->_title(Mage::helper('bs_worksheet')->__('Training Worksheet'))
             ->_title(Mage::helper('bs_worksheet')->__('Worksheets'));
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
     * edit worksheet - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function editAction()
    {
        $worksheetId    = $this->getRequest()->getParam('id');
        $worksheet      = $this->_initWorksheet();
        if ($worksheetId && !$worksheet->getId()) {
            $this->_getSession()->addError(
                Mage::helper('bs_worksheet')->__('This worksheet no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getWorksheetData(true);
        if (!empty($data)) {
            $worksheet->setData($data);
        }
        Mage::register('worksheet_data', $worksheet);
        $this->loadLayout();
        $this->_title(Mage::helper('bs_worksheet')->__('Training Worksheet'))
             ->_title(Mage::helper('bs_worksheet')->__('Worksheets'));
        if ($worksheet->getId()) {
            $this->_title($worksheet->getWsName());
        } else {
            $this->_title(Mage::helper('bs_worksheet')->__('Add worksheet'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new worksheet action
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
     * save worksheet - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('worksheet')) {
            try {
                $data = $this->_filterDates($data, array('ws_approved_date'));
                $worksheet = $this->_initWorksheet();
                $worksheet->addData($data);
                $wsFileName = $this->_uploadAndGetName(
                    'ws_file',
                    Mage::helper('bs_worksheet/worksheet')->getFileBaseDir(),
                    $data
                );
                $worksheet->setData('ws_file', $wsFileName);
                $wsPdfName = $this->_uploadAndGetName(
                    'ws_pdf',
                    Mage::helper('bs_worksheet/worksheet')->getFileBaseDir(),
                    $data
                );
                $worksheet->setData('ws_pdf', $wsPdfName);


                $curriculums = $this->getRequest()->getPost('curriculums', -1);
                if ($curriculums != -1) {
                    $worksheet->setCurriculumsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($curriculums));
                }
                $worksheet->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_worksheet')->__('Worksheet was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $worksheet->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['ws_file']['value'])) {
                    $data['ws_file'] = $data['ws_file']['value'];
                }

                if (isset($data['ws_pdf']['value'])) {
                    $data['ws_pdf'] = $data['ws_pdf']['value'];
                }

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWorksheetData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['ws_file']['value'])) {
                    $data['ws_file'] = $data['ws_file']['value'];
                }
                if (isset($data['ws_pdf']['value'])) {
                    $data['ws_pdf'] = $data['ws_pdf']['value'];
                }

                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheet')->__('There was a problem saving the worksheet.')
                );
                Mage::getSingleton('adminhtml/session')->setWorksheetData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_worksheet')->__('Unable to find worksheet to save.')
        );
        $this->_redirect('*/*/');
    }

    public function generateSixteenAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {

                $ws = Mage::getModel('bs_worksheet/worksheet')->load($this->getRequest()->getParam('id'));

                $this->generateSixteen($ws);

                $this->_redirect('*/*/edit', array('id'=>$this->getRequest()->getParam('id')));
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheet')->__('There was an error generating worksheet.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_worksheet')->__('Could not find worksheet to generate.')
        );
        $this->_redirect('*/*/');
    }

    public function massGenerateSixteenAction()
    {
        $worksheetIds = $this->getRequest()->getParam('worksheet');
        if (!is_array($worksheetIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_worksheet')->__('Please select worksheets to generate.')
            );
        } else {
            try {
                foreach ($worksheetIds as $worksheetId) {
                    $worksheet = Mage::getModel('bs_worksheet/worksheet')->load($worksheetId);
                    $this->generateSixteen($worksheet);
                }

            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheet')->__('There was an error generating worksheets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function generateSixteen($ws){
        $template8016 = Mage::helper('bs_formtemplate')->getFormtemplate('8016');
        $footer8016 = Mage::helper('bs_formtemplate')->getFormtemplate('8016_footer');

        $wsName = $ws->getWsName();
        $wsCode = $ws->getWsCode();
        $wsPage = (int)$ws->getWsPage();

        $preparedBy = $ws->getPreparedBy();


        $wsFile = $ws->getWsFile();


        $rev = Mage::getModel('bs_worksheet/worksheet_attribute_source_wsrevision')->getOptionText($ws->getWsRevision());
        if ($rev == '') {
            $rev = '00';
        }


        if ($wsFile) {
            $wsFile = Mage::helper('bs_worksheet/worksheet')->getFileBaseDir() . $wsFile;

            $worksheetInfo = array(
                'title' => $wsName,
                'code' => $wsCode,
                'rev' => $rev,
                'prepared_by'   => $preparedBy

            );

            $wscontent = array(array('src' => $wsFile));

            $res = Mage::helper('bs_traininglist/docx')->generateDocx($wsCode . '_8016_TRAINING WORKSHEET', $template8016, $worksheetInfo, null, null, null, $wscontent, $footer8016);

            $this->_getSession()->addSuccess(
                Mage::helper('bs_worksheet')->__('Click <a href="%s">%s</a>.', $res['url'], $res['name'])
            );
        }
    }

    /**
     * delete worksheet - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $worksheet = Mage::getModel('bs_worksheet/worksheet');
                $worksheet->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_worksheet')->__('Worksheet was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheet')->__('There was an error deleting worksheet.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('bs_worksheet')->__('Could not find worksheet to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete worksheet - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massDeleteAction()
    {
        $worksheetIds = $this->getRequest()->getParam('worksheet');
        if (!is_array($worksheetIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_worksheet')->__('Please select worksheets to delete.')
            );
        } else {
            try {
                foreach ($worksheetIds as $worksheetId) {
                    $worksheet = Mage::getModel('bs_worksheet/worksheet');
                    $worksheet->setId($worksheetId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('bs_worksheet')->__('Total of %d worksheets were successfully deleted.', count($worksheetIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheet')->__('There was an error deleting worksheets.')
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
        $worksheetIds = $this->getRequest()->getParam('worksheet');
        if (!is_array($worksheetIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_worksheet')->__('Please select worksheets.')
            );
        } else {
            try {
                foreach ($worksheetIds as $worksheetId) {
                $worksheet = Mage::getSingleton('bs_worksheet/worksheet')->load($worksheetId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d worksheets were successfully updated.', count($worksheetIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheet')->__('There was an error updating worksheets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Revision change - action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function massWsRevisionAction()
    {
        $worksheetIds = $this->getRequest()->getParam('worksheet');
        if (!is_array($worksheetIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('bs_worksheet')->__('Please select worksheets.')
            );
        } else {
            try {
                foreach ($worksheetIds as $worksheetId) {
                $worksheet = Mage::getSingleton('bs_worksheet/worksheet')->load($worksheetId)
                    ->setWsRevision($this->getRequest()->getParam('flag_ws_revision'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d worksheets were successfully updated.', count($worksheetIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('bs_worksheet')->__('There was an error updating worksheets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get grid of curriculums action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function curriculumsAction()
    {
        $this->_initWorksheet();
        $this->loadLayout();
        $this->getLayout()->getBlock('worksheet.edit.tab.curriculum')
            ->setWorksheetCurriculums($this->getRequest()->getPost('worksheet_curriculums', null));
        $this->renderLayout();
    }

    /**
     * get grid of curriculums action
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function curriculumsgridAction()
    {
        $this->_initWorksheet();
        $this->loadLayout();
        $this->getLayout()->getBlock('worksheet.edit.tab.curriculum')
            ->setWorksheetCurriculums($this->getRequest()->getPost('worksheet_curriculums', null));
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
        $fileName   = 'worksheet.csv';
        $content    = $this->getLayout()->createBlock('bs_worksheet/adminhtml_worksheet_grid')
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
        $fileName   = 'worksheet.xls';
        $content    = $this->getLayout()->createBlock('bs_worksheet/adminhtml_worksheet_grid')
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
        $fileName   = 'worksheet.xml';
        $content    = $this->getLayout()->createBlock('bs_worksheet/adminhtml_worksheet_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('bs_traininglist/worksheet');
    }
}
