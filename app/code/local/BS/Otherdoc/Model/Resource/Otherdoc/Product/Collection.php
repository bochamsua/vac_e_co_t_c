<?php
/**
 * BS_Otherdoc extension
 * 
 * @category       BS
 * @package        BS_Otherdoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Other\'s Course Document - product relation resource model collection
 *
 * @category    BS
 * @package     BS_Otherdoc
 * @author Bui Phong
 */
class BS_Otherdoc_Model_Resource_Otherdoc_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return BS_Otherdoc_Model_Resource_Otherdoc_Product_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_otherdoc/otherdoc_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add other\'s course document filter
     *
     * @access public
     * @param BS_Otherdoc_Model_Otherdoc | int $otherdoc
     * @return BS_Otherdoc_Model_Resource_Otherdoc_Product_Collection
     * @author Bui Phong
     */
    public function addOtherdocFilter($otherdoc)
    {
        if ($otherdoc instanceof BS_Otherdoc_Model_Otherdoc) {
            $otherdoc = $otherdoc->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.otherdoc_id = ?', $otherdoc);
        return $this;
    }
}
