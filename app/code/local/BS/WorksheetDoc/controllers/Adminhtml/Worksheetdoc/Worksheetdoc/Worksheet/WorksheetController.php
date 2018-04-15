<?php
/**
 * BS_WorksheetDoc extension
 * 
 * @category       BS
 * @package        BS_WorksheetDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Worksheet Document - worksheet controller
 * @category    BS
 * @package     BS_WorksheetDoc
 * @author      Bui Phong
 */
require_once ("BS/Worksheet/controllers/Adminhtml/Worksheet/WorksheetController.php");
class BS_WorksheetDoc_Adminhtml_Worksheetdoc_Worksheetdoc_Worksheet_WorksheetController extends BS_Worksheet_Adminhtml_Worksheet_WorksheetController
{
    /**
     * construct
     *
     * @access protected
     * @return void
     * @author Bui Phong
     */
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('BS_WorksheetDoc');
    }

    /**
     * worksheet docs in the bs_worksheet page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function worksheetdocsAction()
    {
        $this->_initWorksheet();
        $this->loadLayout();
        $this->getLayout()->getBlock('worksheet.edit.tab.worksheetdoc')
            ->setWorksheetWorksheetdocs($this->getRequest()->getPost('worksheet_worksheetdocs', null));
        $this->renderLayout();
    }

    /**
     * worksheet docs grid in the bs_worksheet page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function worksheetdocsGridAction()
    {
        $this->_initWorksheet();
        $this->loadLayout();
        $this->getLayout()->getBlock('worksheet.edit.tab.worksheetdoc')
            ->setWorksheetWorksheetdocs($this->getRequest()->getPost('worksheet_worksheetdocs', null));
        $this->renderLayout();
    }
}
