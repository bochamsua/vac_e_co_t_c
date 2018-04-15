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
 * Trainee resource model
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Model_Resource_Trainee extends Mage_Catalog_Model_Resource_Abstract
{
    protected $_traineeProductTable = null;


    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('bs_trainee_trainee')
            ->setConnection(
                $resource->getConnection('trainee_read'),
                $resource->getConnection('trainee_write')
            );
        $this->_traineeProductTable = $this->getTable('bs_trainee/trainee_product');

    }

    /**
     * wrapper for main table getter
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getMainTable()
    {
        return $this->getEntityTable();
    }
}
