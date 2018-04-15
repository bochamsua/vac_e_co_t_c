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
 * Worksheet - curriculum controller
 * @category    BS
 * @package     BS_Worksheet
 * @author      Bui Phong
 */
require_once ("BS/Traininglist/controllers/Adminhtml/Traininglist/CurriculumController.php");
class BS_Worksheet_Adminhtml_Worksheet_Worksheet_Traininglist_CurriculumController extends BS_Traininglist_Adminhtml_Traininglist_CurriculumController
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
        $this->setUsedModuleName('BS_Worksheet');
    }

    /**
     * worksheets in the bs_traininglist page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function worksheetsAction()
    {
        $this->_initCurriculum();
        $this->loadLayout();
        $this->getLayout()->getBlock('curriculum.edit.tab.worksheet')
            ->setCurriculumWorksheets($this->getRequest()->getPost('curriculum_worksheets', null));
        $this->renderLayout();
    }

    /**
     * worksheets grid in the bs_traininglist page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function worksheetsGridAction()
    {
        $this->_initCurriculum();
        $this->loadLayout();
        $this->getLayout()->getBlock('curriculum.edit.tab.worksheet')
            ->setCurriculumWorksheets($this->getRequest()->getPost('curriculum_worksheets', null));
        $this->renderLayout();
    }
}
