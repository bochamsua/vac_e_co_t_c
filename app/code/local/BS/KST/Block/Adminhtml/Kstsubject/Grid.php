<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject admin grid block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstsubject_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('kstsubjectGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstsubject_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_kst/kstsubject')
            ->getCollection();
        $collection->getSelect()->joinLeft(array('cu'=>'bs_traininglist_curriculum_varchar'),'curriculum_id = cu.entity_id AND attribute_id = 225','cu.value as c_code');

        $collection->addFilterToMap('entity_id','main_table.entity_id');
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstsubject_Grid
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

        $currs = Mage::getResourceModel('bs_traininglist/curriculum_collection')->addAttributeToFilter('c_code',array(array('like'=>'%KST%'), array('like'=>'%CRS%')));

        $currs = $currs->toOptionCodeHash();

        $this->addColumn(
            'curriculum_id',
            array(
                'header'    => Mage::helper('bs_kst')->__('Curriculum'),
                'index'     => 'curriculum_id',
                'type'      => 'options',
                'options'   => $currs,
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
            'position',
            array(
                'header' => Mage::helper('bs_kst')->__('Position'),
                'index'  => 'position',
                'type'=> 'number',

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
     * @return BS_KST_Block_Adminhtml_Kstsubject_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('kstsubject');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_kst/kstsubject/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_kst/kstsubject/delete");

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
//            $this->getMassactionBlock()->addItem(
//                'status',
//                array(
//                    'label'      => Mage::helper('bs_kst')->__('Change status'),
//                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
//                    'additional' => array(
//                        'status' => array(
//                            'name'   => 'status',
//                            'type'   => 'select',
//                            'class'  => 'required-entry',
//                            'label'  => Mage::helper('bs_kst')->__('Status'),
//                            'values' => array(
//                                '1' => Mage::helper('bs_kst')->__('Enabled'),
//                                '0' => Mage::helper('bs_kst')->__('Disabled'),
//                            )
//                        )
//                    )
//                )
//            );




        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_KST_Model_Kstsubject
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return false;//$this->getUrl('*/*/edit', array('id' => $row->getId()));
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
     * @return BS_KST_Block_Adminhtml_Kstsubject_Grid
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
                    if($('kstsubjectGrid_filter_curriculum_id') != undefined){
                        $('kstsubjectGrid_filter_curriculum_id').observe('change', function(){
                            kstsubjectGridJsObject.doFilter();
                        });
                    }



                </script>";
        return parent::_afterToHtml($html);
    }
}
