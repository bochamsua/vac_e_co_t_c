<?php
/**
 * BS_Tasktraining extension
 * 
 * @category       BS
 * @package        BS_Tasktraining
 * @copyright      Copyright (c) 2015
 */
/**
 * Instructor - category controller
 * @category    BS
 * @package     BS_Tasktraining
 * @author Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/CategoryController.php");
class BS_Tasktraining_Adminhtml_Tasktraining_Taskinstructor_Catalog_CategoryController extends Mage_Adminhtml_Catalog_CategoryController
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
        $this->setUsedModuleName('BS_Tasktraining');
    }

    /**
     * taskinstructors grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function taskinstructorsgridAction()
    {
        $this->_initCategory();
        $this->loadLayout();
        $this->getLayout()->getBlock('category.edit.tab.taskinstructor')
            ->setCategoryTaskinstructors($this->getRequest()->getPost('category_taskinstructors', null));
        $this->renderLayout();
    }
}
