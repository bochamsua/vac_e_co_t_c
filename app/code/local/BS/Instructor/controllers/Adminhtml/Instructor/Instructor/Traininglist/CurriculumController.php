<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor - product controller
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
require_once ("BS/Traininglist/controllers/Adminhtml/Traininglist/CurriculumController.php");
class BS_Instructor_Adminhtml_Instructor_Instructor_Traininglist_CurriculumController extends BS_Traininglist_Adminhtml_Traininglist_CurriculumController
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
        $this->setUsedModuleName('BS_Instructor');
    }

    /**
     * instructors in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function instructorsAction()
    {
        $this->_initCurriculum();
        $this->loadLayout();
        $this->getLayout()->getBlock('curriculum.edit.tab.instructor')
            ->setCurriculumInstructors($this->getRequest()->getPost('curriculum_instructors', null));
        $this->renderLayout();
    }

    /**
     * instructors grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function instructorsGridAction()
    {
        $this->_initCurriculum();
        $this->loadLayout();
        $this->getLayout()->getBlock('curriculum.edit.tab.instructor')
            ->setCurriculumInstructors($this->getRequest()->getPost('curriculum_instructors', null));
        $this->renderLayout();
    }
}
