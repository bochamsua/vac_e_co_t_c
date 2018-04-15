<?php
/**
 * BS_ImportTrainee extension
 * 
 * @category       BS
 * @package        BS_ImportTrainee
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Trainee admin grid block
 *
 * @category    BS
 * @package     BS_ImportTrainee
 * @author Bui Phong
 */
class BS_ImportTrainee_Block_Adminhtml_Importtrainee_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('importtraineeGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_ImportTrainee_Block_Adminhtml_Importtrainee_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_importtrainee/importtrainee')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_ImportTrainee_Block_Adminhtml_Importtrainee_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_importtrainee')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'course_id',
            array(
                'header'    => Mage::helper('bs_importtrainee')->__('Course'),
                'align'     => 'left',
                'index'     => 'course_id',
            )
        );
        

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_importtrainee')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_importtrainee')->__('Enabled'),
                    '0' => Mage::helper('bs_importtrainee')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_importtrainee')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_importtrainee')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_importtrainee')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_importtrainee')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_importtrainee')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_ImportTrainee_Block_Adminhtml_Importtrainee_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('importtrainee');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/importtrainee/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/importtrainee/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_importtrainee')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_importtrainee')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_importtrainee')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_importtrainee')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_importtrainee')->__('Enabled'),
                                '0' => Mage::helper('bs_importtrainee')->__('Disabled'),
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
     * @param BS_ImportTrainee_Model_Importtrainee
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
     * @return BS_ImportTrainee_Block_Adminhtml_Importtrainee_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
