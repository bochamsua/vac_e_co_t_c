<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Trainee admin grid block
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Trainee_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('traineeGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        //$this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Trainee_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_docwise/trainee')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Trainee_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_docwise')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'trainee_name',
            array(
                'header'    => Mage::helper('bs_docwise')->__('Trainee Name'),
                'align'     => 'left',
                'index'     => 'trainee_name',
            )
        );
        

        $this->addColumn(
            'vaeco_id',
            array(
                'header' => Mage::helper('bs_docwise')->__('VAECO ID'),
                'index'  => 'vaeco_id',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'dob',
            array(
                'header' => Mage::helper('bs_docwise')->__('Date of Birth'),
                'index'  => 'dob',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'dept',
            array(
                'header' => Mage::helper('bs_docwise')->__('Deparment'),
                'index'  => 'dept',
                'type'=> 'text',

            )
        );
        /*$this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_docwise')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_docwise')->__('Enabled'),
                    '0' => Mage::helper('bs_docwise')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_docwise')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_docwise')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_docwise')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_docwise')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_docwise')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Trainee_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('trainee');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_exam/bs_docwise/trainee/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_exam/bs_docwise/trainee/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_docwise')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_docwise')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_docwise')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_docwise')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_docwise')->__('Enabled'),
                                '0' => Mage::helper('bs_docwise')->__('Disabled'),
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
     * @param BS_Docwise_Model_Trainee
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
     * @return BS_Docwise_Block_Adminhtml_Trainee_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
