<?php
/**
 * BS_Trainee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BS
 * @package        BS_Trainee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Trainee - product controller
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class BS_Trainee_Adminhtml_Trainee_Trainee_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
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
        $this->setUsedModuleName('BS_Trainee');
    }

    /**
     * trainees in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function traineesAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.trainee')
            ->setProductTrainees($this->getRequest()->getPost('product_trainees', null));
        $this->renderLayout();
    }

    /**
     * trainees grid in the catalog page
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function traineesGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.trainee')
            ->setProductTrainees($this->getRequest()->getPost('product_trainees', null));
        $this->renderLayout();
    }
}
