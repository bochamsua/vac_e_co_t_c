<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam - product controller
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
require_once ("BS/Docwise/controllers/Adminhtml/Docwise/DocwisementController.php");
class BS_Docwise_Adminhtml_Docwise_Exam_DocwisementController extends BS_Docwise_Adminhtml_Docwise_DocwisementController
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
        $this->setUsedModuleName('BS_Docwise');
    }

    /**
     * exams in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function examsAction()
    {
        $this->_initDocwisement();
        $this->loadLayout();
        $this->getLayout()->getBlock('docwisement.edit.tab.exam')
            ->setDocwisementExams($this->getRequest()->getPost('docwisement_exams', null));
        $this->renderLayout();
    }

    /**
     * exams grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function examsGridAction()
    {
        $this->_initDocwisement();
        $this->loadLayout();
        $this->getLayout()->getBlock('docwisement.edit.tab.exam')
            ->setDocwisementExams($this->getRequest()->getPost('docwisement_exams', null));
        $this->renderLayout();
    }
}
