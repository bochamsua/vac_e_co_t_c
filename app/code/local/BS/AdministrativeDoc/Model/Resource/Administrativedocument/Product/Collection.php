<?php
/**
 * BS_AdministrativeDoc extension
 * 
 * @category       BS
 * @package        BS_AdministrativeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Administrative Document - product relation resource model collection
 *
 * @category    BS
 * @package     BS_AdministrativeDoc
 * @author Bui Phong
 */
class BS_AdministrativeDoc_Model_Resource_Administrativedocument_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
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
     * @return BS_AdministrativeDoc_Model_Resource_Administrativedocument_Product_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_administrativedoc/administrativedocument_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add administrative document filter
     *
     * @access public
     * @param BS_AdministrativeDoc_Model_Administrativedocument | int $administrativedocument
     * @return BS_AdministrativeDoc_Model_Resource_Administrativedocument_Product_Collection
     * @author Bui Phong
     */
    public function addAdministrativedocumentFilter($administrativedocument)
    {
        if ($administrativedocument instanceof BS_AdministrativeDoc_Model_Administrativedocument) {
            $administrativedocument = $administrativedocument->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.administrativedocument_id = ?', $administrativedocument);
        return $this;
    }
}
