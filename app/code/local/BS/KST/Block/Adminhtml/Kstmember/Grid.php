<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Member admin grid block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstmember_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('kstmemberGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstmember_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_kst/kstmember')
            ->getCollection();

        $collection->getSelect()->joinLeft(array('g'=>'bs_kst_kstgroup'),'kstgroup_id = g.entity_id','course_id');

        $collection->getSelect()->joinLeft(array('pro'=>'catalog_product_entity'),'g.course_id = pro.entity_id','sku');

        $collection->addFilterToMap('entity_id','main_table.entity_id');

        

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstmember_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_kst')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $tableGroup = $resource->getTableName('bs_kst/kstgroup');


        $courses = Mage::getResourceModel('catalog/product_collection')->addAttributeToFilter('entity_id', array('in'=>$readConnection->fetchCol("SELECT DISTINCT course_id FROM {$tableGroup}")));

        
        $courses = $courses->toSkuOptionHash();


        $this->addColumn(
            'course_id',
            array(
                'header'    => Mage::helper('bs_kst')->__('Course'),
                'index'     => 'course_id',
                'type'      => 'options',
                'options'   => $courses,
                'filter_index'  => 'g.course_id',
            )
        );

        $this->addColumn(
            'kstgroup_id',
            array(
                'header'    => Mage::helper('bs_kst')->__('Group'),
                'index'     => 'kstgroup_id',
                'type'      => 'options',

                'options'   => Mage::getResourceModel('bs_kst/kstgroup_collection')
                    ->toOptionHash(),
                'renderer'  => 'bs_kst/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getKstgroupId'
                ),
                'base_link' => 'adminhtml/kst_kstgroup/edit'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('bs_kst')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );
        

        $this->addColumn(
            'vaeco_id',
            array(
                'header' => Mage::helper('bs_kst')->__('Vaeco ID'),
                'index'  => 'vaeco_id',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'username',
            array(
                'header' => Mage::helper('bs_kst')->__('Username'),
                'index'  => 'username',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'is_leader',
            array(
                'header' => Mage::helper('bs_kst')->__('Leader'),
                'index'  => 'is_leader',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('bs_kst')->__('Yes'),
                    '0' => Mage::helper('bs_kst')->__('No'),
                )

            )
        );


        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_kst')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_kst')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_kst')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_kst')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_kst')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstmember_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('kstmember');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_kst/kstmember/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_kst/kstmember/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_kst')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_kst')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_kst')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_kst')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_kst')->__('Enabled'),
                                '0' => Mage::helper('bs_kst')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'is_leader',
            array(
                'label'      => Mage::helper('bs_kst')->__('Change Leader'),
                'url'        => $this->getUrl('*/*/massIsLeader', array('_current'=>true)),
                'additional' => array(
                    'flag_is_leader' => array(
                        'name'   => 'flag_is_leader',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_kst')->__('Leader'),
                        'values' => array(
                                '1' => Mage::helper('bs_kst')->__('Yes'),
                                '0' => Mage::helper('bs_kst')->__('No'),
                            )

                    )
                )
            )
        );
        $values = Mage::getResourceModel('bs_kst/kstgroup_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'kstgroup_id',
            array(
                'label'      => Mage::helper('bs_kst')->__('Change Group'),
                'url'        => $this->getUrl('*/*/massKstgroupId', array('_current'=>true)),
                'additional' => array(
                    'flag_kstgroup_id' => array(
                        'name'   => 'flag_kstgroup_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_kst')->__('Group'),
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
     * @param BS_KST_Model_Kstmember
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
     * @return BS_KST_Block_Adminhtml_Kstmember_Grid
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
                    if($('kstmemberGrid_filter_course_id') != undefined){
                        $('kstmemberGrid_filter_course_id').observe('change', function(){
                            kstmemberGridJsObject.doFilter();
                        });
                    }

                    if($('kstmemberGrid_filter_kstgroup_id') != undefined){
                        $('kstmemberGrid_filter_kstgroup_id').observe('change', function(){
                            kstmemberGridJsObject.doFilter();
                        });
                    }

                   




                </script>";
        return parent::_afterToHtml($html); // TODO: Change the autogenerated stub
    }
}
