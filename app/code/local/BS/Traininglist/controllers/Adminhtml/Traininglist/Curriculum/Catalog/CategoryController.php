<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum - category controller
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/CategoryController.php");
class BS_Traininglist_Adminhtml_Traininglist_Curriculum_Catalog_CategoryController extends Mage_Adminhtml_Catalog_CategoryController
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
        $this->setUsedModuleName('BS_Traininglist');
    }

    /**
     * curriculums grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function curriculumsgridAction()
    {
        $this->_initCategory();
        $this->loadLayout();
        $this->getLayout()->getBlock('category.edit.tab.curriculum')
            ->setCategoryCurriculums($this->getRequest()->getPost('category_curriculums', null));
        $this->renderLayout();
    }
}
