<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Other\'s Course Document product model
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Model_Otherdoc_Product extends Mage_Core_Model_Abstract
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
        $this->_init('bs_otherdoc/otherdoc_product');
    }

    /**
     * Save data for other\'s course document-product relation
     * @access public
     * @param  BS_Otherdoc_Model_Otherdoc $otherdoc
     * @return BS_Otherdoc_Model_Otherdoc_Product
     * @author Bui Phong
     */
    public function saveOtherdocRelation($otherdoc)
    {
        $data = $otherdoc->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveOtherdocRelation($otherdoc, $data);
        }
        return $this;
    }

    /**
     * get products for other\'s course document
     *
     * @access public
     * @param BS_Otherdoc_Model_Otherdoc $otherdoc
     * @return BS_Otherdoc_Model_Resource_Otherdoc_Product_Collection
     * @author Bui Phong
     */
    public function getProductCollection($otherdoc)
    {
        $collection = Mage::getResourceModel('bs_otherdoc/otherdoc_product_collection')
            ->addOtherdocFilter($otherdoc);
        return $collection;
    }
}
