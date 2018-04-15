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
 * Trainee admin edit tabs
 *
 * @category    BS
 * @package     BS_Trainee
 * @author      Bui Phong
 */
class BS_Trainee_Block_Adminhtml_Trainee_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('trainee_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('bs_trainee')->__('Trainee Information'));
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return BS_Trainee_Block_Adminhtml_Trainee_Edit_Tabs
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        $trainee = $this->getTrainee();
        $entity = Mage::getModel('eav/entity_type')
            ->load('bs_trainee_trainee', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'info',
            array(
                'label'   => Mage::helper('bs_trainee')->__('Trainee Information'),
                'content' => $this->getLayout()->createBlock(
                    'bs_trainee/adminhtml_trainee_edit_tab_attributes'
                )
                ->setAttributes($attributes)
                ->toHtml(),
            )
        );
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('bs_trainee')->__('Attended Courses'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );

        $this->addTab(
            'traineedocs',
            array(
                'label' => Mage::helper('bs_traineedoc')->__('Trainee Document'),
                'url'   => Mage::helper('adminhtml')->getUrl(
                    'adminhtml/traineedoc_traineedoc_trainee_trainee/traineedocs',
                    array('_current' => true)
                ),
                'class' => 'ajax',
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve trainee entity
     *
     * @access public
     * @return BS_Trainee_Model_Trainee
     * @author Bui Phong
     */
    public function getTrainee()
    {
        return Mage::registry('current_trainee');
    }
}
