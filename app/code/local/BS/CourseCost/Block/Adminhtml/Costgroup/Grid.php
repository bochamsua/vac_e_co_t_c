<?php
/**
 * BS_CourseCost extension
 * 
 * @category       BS
 * @package        BS_CourseCost
 * @copyright      Copyright (c) 2016
 */
/**
 * Manage Cost Group admin grid block
 *
 * @category    BS
 * @package     BS_CourseCost
 * @author Bui Phong
 */
class BS_CourseCost_Block_Adminhtml_Costgroup_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('costgroupGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Costgroup_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_coursecost/costgroup')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_CourseCost_Block_Adminhtml_Costgroup_Grid
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
            'group_name',
            array(
                'header'    => Mage::helper('bs_coursecost')->__('Name'),
                'align'     => 'left',
                'index'     => 'group_name',
            )
        );
        

        $this->addColumn(
            'group_code',
            array(
                'header' => Mage::helper('bs_coursecost')->__('Code'),
                'index'  => 'group_code',
                'type'=> 'text',

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
     * @return BS_CourseCost_Block_Adminhtml_Costgroup_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('costgroup');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_coursecost/costgroup/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("catalog/bs_coursecost/costgroup/delete");

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




        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_CourseCost_Model_Costgroup
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
     * @return BS_CourseCost_Block_Adminhtml_Costgroup_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
