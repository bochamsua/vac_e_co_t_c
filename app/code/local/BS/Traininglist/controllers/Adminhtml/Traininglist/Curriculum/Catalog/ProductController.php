<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Training Curriculum - product controller
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class BS_Traininglist_Adminhtml_Traininglist_Curriculum_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
     * training curriculum in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function curriculumsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.curriculum')
            ->setProductCurriculums($this->getRequest()->getPost('product_curriculums', null));
        $this->renderLayout();
    }

    /**
     * training curriculum grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function curriculumsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.curriculum')
            ->setProductCurriculums($this->getRequest()->getPost('product_curriculums', null));
        $this->renderLayout();
    }
}
