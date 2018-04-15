<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Administrative Document - product relation model
 *
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Model_Resource_Administrativedocument_Product extends Mage_Core_Model_Resource_Db_Abstract
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
        $this->_init('bs_administrativedoc/administrativedocument_product', 'rel_id');
    }
    /**
     * Save administrative document - product relations
     *
     * @access public
     * @param BS_AdministrativeDoc_Model_Administrativedocument $administrativedocument
     * @param array $data
     * @return BS_AdministrativeDoc_Model_Resource_Administrativedocument_Product
     * @author Bui Phong
     */
    public function saveAdministrativedocumentRelation($administrativedocument, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('administrativedocument_id=?', $administrativedocument->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'administrativedocument_id' => $administrativedocument->getId(),
                    'product_id'    => $productId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  product - administrative document relations
     *
     * @access public
     * @param Mage_Catalog_Model_Product $prooduct
     * @param array $data
     * @return BS_AdministrativeDoc_Model_Resource_Administrativedocument_Product
     * @@author Bui Phong
     */
    public function saveProductRelation($product, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('product_id=?', $product->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $administrativedocumentId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'administrativedocument_id' => $administrativedocumentId,
                    'product_id'    => $product->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
