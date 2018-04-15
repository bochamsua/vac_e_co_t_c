<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2016
 */
/**
 * Tool admin grid block
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Wtool_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('wtoolGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Wtool_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_logistics/wtool')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Wtool_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_logistics')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'workshop_id',
            array(
                'header'    => Mage::helper('bs_logistics')->__('Workshop'),
                'index'     => 'workshop_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('bs_logistics/workshop_collection')
                    ->toOptionHash(),
                'renderer'  => 'bs_logistics/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getWorkshopId'
                ),
                'base_link' => 'adminhtml/logistics_workshop/edit'
            )
        );

        $this->addColumn(
            'wgroupitem_id',
            array(
                'header'    => Mage::helper('bs_logistics')->__('Equipment'),
                'index'     => 'wgroupitem_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('bs_logistics/wgroupitem_collection')
                    ->toOptionHash(),
                'renderer'  => 'bs_logistics/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getWgroupitemId'
                ),
                'base_link' => 'adminhtml/logistics_wgroupitem/edit'
            )
        );
        $this->addColumn(
            'grouptype_id',
            array(
                'header'    => Mage::helper('bs_logistics')->__('Type'),
                'index'     => 'grouptype_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('bs_logistics/grouptype_collection')
                    ->toOptionHash(),
                'renderer'  => 'bs_logistics/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getGrouptypeId'
                ),
                'base_link' => 'adminhtml/logistics_grouptype/edit'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('bs_logistics')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );


        $this->addColumn(
            'name_vi',
            array(
                'header' => Mage::helper('bs_logistics')->__('Vietnamese'),
                'index'  => 'name_vi',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'code',
            array(
                'header' => Mage::helper('bs_logistics')->__('Code'),
                'index'  => 'code',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'part_number',
            array(
                'header' => Mage::helper('bs_logistics')->__('P/N, Type'),
                'index'  => 'part_number',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'qty',
            array(
                'header' => Mage::helper('bs_logistics')->__('Qty'),
                'index'  => 'qty',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'tool_status',
            array(
                'header' => Mage::helper('bs_logistics')->__('Tool Status'),
                'index'  => 'tool_status',
                'type'=> 'text',

            )
        );


        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_logistics')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_logistics')->__('Enabled'),
                    '0' => Mage::helper('bs_logistics')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_logistics')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_logistics')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_logistics')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_logistics')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_logistics')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Wtool_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('wtool');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/workshop/wtool/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/workshop/wtool/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_logistics')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_logistics')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_logistics')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_logistics')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_logistics')->__('Enabled'),
                                '0' => Mage::helper('bs_logistics')->__('Disabled'),
                            )
                        )
                    )
                )
            );



            $values = Mage::getResourceModel('bs_logistics/workshop_collection')->toOptionHash();
            $values = array_reverse($values, true);
            $values[''] = '';
            $values = array_reverse($values, true);
            $this->getMassactionBlock()->addItem(
                'workshop_id',
                array(
                    'label'      => Mage::helper('bs_logistics')->__('Change Workshop'),
                    'url'        => $this->getUrl('*/*/massWorkshopId', array('_current'=>true)),
                    'additional' => array(
                        'flag_workshop_id' => array(
                            'name'   => 'flag_workshop_id',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_logistics')->__('Workshop'),
                            'values' => $values
                        )
                    )
                )
            );


            $values = Mage::getResourceModel('bs_logistics/wgroupitem_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'wgroupitem_id',
            array(
                'label'      => Mage::helper('bs_logistics')->__('Change Group Item'),
                'url'        => $this->getUrl('*/*/massWgroupitemId', array('_current'=>true)),
                'additional' => array(
                    'flag_wgroupitem_id' => array(
                        'name'   => 'flag_wgroupitem_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_logistics')->__('Group Item'),
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
     * @param BS_Logistics_Model_Wtool
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
     * @return BS_Logistics_Block_Adminhtml_Wtool_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _afterToHtml($html)
    {
        $html .= "<script>
                    if($('wtoolGrid_filter_workshop_id') != undefined){
                        $('wtoolGrid_filter_workshop_id').observe('change', function(){
                            wtoolGridJsObject.doFilter();
                        });
                    }

                    if($('wtoolGrid_filter_wgroupitem_id') != undefined){
                        $('wtoolGrid_filter_wgroupitem_id').observe('change', function(){
                            wtoolGridJsObject.doFilter();
                        });
                    }
                    if($('wtoolGrid_filter_grouptype_id') != undefined){
                        $('wtoolGrid_filter_grouptype_id').observe('change', function(){
                            wtoolGridJsObject.doFilter();
                        });
                    }
                    if($('wtoolGrid_filter_grouptype_id') != undefined){
                        $('wtoolGrid_filter_grouptype_id').observe('change', function(){
                            wtoolGridJsObject.doFilter();
                        });
                    }
                   

                    




                </script>";
        return parent::_afterToHtml($html); // TODO: Change the autogenerated stub
    }
}
