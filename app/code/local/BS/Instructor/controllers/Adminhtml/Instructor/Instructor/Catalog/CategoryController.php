<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor - category controller
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/CategoryController.php");
class BS_Instructor_Adminhtml_Instructor_Instructor_Catalog_CategoryController extends Mage_Adminhtml_Catalog_CategoryController
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
     * instructors grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function instructorsgridAction()
    {
        $this->_initCategory();
        $this->loadLayout();
        $this->getLayout()->getBlock('category.edit.tab.instructor')
            ->setCategoryInstructors($this->getRequest()->getPost('category_instructors', null));
        $this->renderLayout();
    }
}
