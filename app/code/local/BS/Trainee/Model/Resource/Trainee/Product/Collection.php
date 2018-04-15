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
 * Trainee - product relation resource model collection
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Model_Resource_Trainee_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
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
     * @return BS_Trainee_Model_Resource_Trainee_Product_Collection
     * @author Bui Phong
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('bs_trainee/trainee_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add trainee filter
     *
     * @access public
     * @param BS_Trainee_Model_Trainee | int $trainee
     * @return BS_Trainee_Model_Resource_Trainee_Product_Collection
     * @author Bui Phong
     */
    public function addTraineeFilter($trainee)
    {
        if ($trainee instanceof BS_Trainee_Model_Trainee) {
            $trainee = $trainee->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.trainee_id = ?', $trainee);
        return $this;
    }
}
