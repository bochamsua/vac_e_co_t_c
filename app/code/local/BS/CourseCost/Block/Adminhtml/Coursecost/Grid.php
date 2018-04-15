<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Course Cost admin grid block
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Block_Adminhtml_Coursecost_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('coursecostGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Coursecost_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_coursecost/coursecost')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Coursecost_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_coursecost')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'costgroup_id',
            array(
                'header'    => Mage::helper('bs_coursecost')->__('Manage Cost Group'),
                'index'     => 'costgroup_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('bs_coursecost/costgroup_collection')
                    ->toOptionHash(),
                'renderer'  => 'bs_coursecost/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getCostgroupId'
                ),
                'base_link' => 'adminhtml/coursecost_costgroup/edit'
            )
        );
        $this->addColumn(
            'costitem_id',
            array(
                'header'    => Mage::helper('bs_coursecost')->__('Manage Group Items'),
                'index'     => 'costitem_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('bs_coursecost/costitem_collection')
                    ->toOptionHash(),
                'renderer'  => 'bs_coursecost/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getCostitemId'
                ),
                'base_link' => 'adminhtml/coursecost_costitem/edit'
            )
        );
        $this->addColumn(
            'qty',
            array(
                'header'    => Mage::helper('bs_coursecost')->__('Qty'),
                'align'     => 'left',
                'index'     => 'qty',
            )
        );
        

        $this->addColumn(
            'course_id',
            array(
                'header' => Mage::helper('bs_coursecost')->__('Course'),
                'index'  => 'course_id',
                'type'=> 'number',

            )
        );

        $this->addColumn(
            'coursecost_cost',
            array(
                'header' => Mage::helper('bs_coursecost')->__('Total Cost'),
                'index'  => 'coursecost_cost',
                'type'=> 'number',

            )
        );
        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_coursecost')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_coursecost')->__('Enabled'),
                    '0' => Mage::helper('bs_coursecost')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_coursecost')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_coursecost')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_coursecost')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_coursecost')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_coursecost')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Coursecost_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('coursecost');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_coursecost/coursecost/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_coursecost/coursecost/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_coursecost')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_coursecost')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_coursecost')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_coursecost')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_coursecost')->__('Enabled'),
                                '0' => Mage::helper('bs_coursecost')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $values = Mage::getResourceModel('bs_coursecost/costgroup_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'costgroup_id',
            array(
                'label'      => Mage::helper('bs_coursecost')->__('Change Manage Cost Group'),
                'url'        => $this->getUrl('*/*/massCostgroupId', array('_current'=>true)),
                'additional' => array(
                    'flag_costgroup_id' => array(
                        'name'   => 'flag_costgroup_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_coursecost')->__('Manage Cost Group'),
                        'values' => $values
                    )
                )
            )
        );
        $values = Mage::getResourceModel('bs_coursecost/costitem_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'costitem_id',
            array(
                'label'      => Mage::helper('bs_coursecost')->__('Change Manage Group Items'),
                'url'        => $this->getUrl('*/*/massCostitemId', array('_current'=>true)),
                'additional' => array(
                    'flag_costitem_id' => array(
                        'name'   => 'flag_costitem_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_coursecost')->__('Manage Group Items'),
                        'values' => $values
                    )
                )
            )
        );
        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_CourseCost_Model_Coursecost
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Coursecost_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
