<?php
/**
 * BS_CourseDoc extension
 * 
 * @category       BS
 * @package        BS_CourseDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Document - product relation model
 *
 * @category    BS
 * @package     BS_CourseDoc
 * @author      Bui Phong
 */
class BS_CourseDoc_Model_Resource_Coursedoc_Product extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     *
     * @access protected
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Bui Phong
     */
    protected function  _construct()
    {
        $this->_init('bs_coursedoc/coursedoc_product', 'rel_id');
    }
    /**
     * Save course doc - product relations
     *
     * @access public
     * @param BS_CourseDoc_Model_Coursedoc $coursedoc
     * @param array $data
     * @return BS_CourseDoc_Model_Resource_Coursedoc_Product
     * @author Bui Phong
     */
    public function saveCoursedocRelation($coursedoc, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('coursedoc_id=?', $coursedoc->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'coursedoc_id' => $coursedoc->getId(),
                    'product_id'    => $productId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  product - course doc relations
     *
     * @access public
     * @param Mage_Catalog_Model_Product $prooduct
     * @param array $data
     * @return BS_CourseDoc_Model_Resource_Coursedoc_Product
     * @@author Bui Phong
     */
    public function saveProductRelation($product, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('product_id=?', $product->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $coursedocId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'coursedoc_id' => $coursedocId,
                    'product_id'    => $product->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
