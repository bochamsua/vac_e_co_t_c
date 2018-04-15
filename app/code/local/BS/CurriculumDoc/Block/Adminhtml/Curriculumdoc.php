<?php
/**
 * BS_CurriculumDoc extension
 * 
 * 
 * @category       BS
 * @package        BS_CurriculumDoc
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Curriculum Document admin block
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Block_Adminhtml_Curriculumdoc extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_curriculumdoc';
        $this->_blockGroup         = 'bs_curriculumdoc';
        parent::__construct();
        $this->_headerText         = Mage::helper('bs_curriculumdoc')->__('Curriculum Document');
        $this->_updateButton('add', 'label', Mage::helper('bs_curriculumdoc')->__('Add Curriculum Document'));


        $isAllowed = Mage::getSingleton('admin/session')->isAllowed("bs_material/curriculumdoc/new");
        if(!$isAllowed){
                $this->_removeButton('add');
        }

    }
}
