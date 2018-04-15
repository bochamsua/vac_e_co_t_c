<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Folder - product relation resource model collection
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Resource_Filefolder_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
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
     * @return BS_Logistics_Model_Resource_Filefolder_Product_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_logistics/filefolder_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add file folder filter
     *
     * @access public
     * @param BS_Logistics_Model_Filefolder | int $filefolder
     * @return BS_Logistics_Model_Resource_Filefolder_Product_Collection
     * @author Bui Phong
     */
    public function addFilefolderFilter($filefolder)
    {
        if ($filefolder instanceof BS_Logistics_Model_Filefolder) {
            $filefolder = $filefolder->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.filefolder_id = ?', $filefolder);
        return $this;
    }
}
