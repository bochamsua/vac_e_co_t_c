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
 * Trainee product model
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Model_Trainee_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Bui Phong
     */
    protected function _construct()
    {
        $this->_init('bs_trainee/trainee_product');
    }

    /**
     * Save data for trainee-product relation
     * @access public
     * @param  BS_Trainee_Model_Trainee $trainee
     * @return BS_Trainee_Model_Trainee_Product
     * @author Bui Phong
     */
    public function saveTraineeRelation($trainee)
    {
        $data = $trainee->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveTraineeRelation($trainee, $data);
        }
        return $this;
    }

    /**
     * get products for trainee
     *
     * @access public
     * @param BS_Trainee_Model_Trainee $trainee
     * @return BS_Trainee_Model_Resource_Trainee_Product_Collection
     * @author Bui Phong
     */
    public function getProductCollection($trainee)
    {
        $collection = Mage::getResourceModel('bs_trainee/trainee_product_collection')
            ->addTraineeFilter($trainee);
        return $collection;
    }
}
