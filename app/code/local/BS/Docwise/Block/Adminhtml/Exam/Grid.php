<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam admin grid block
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Exam_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('examGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Exam_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_docwise/exam')
            ->getCollection();
        
        $this->setCollection($collection);
        parent::_prepareCollection();

        $this->_prepareTotals('number_trainee');
        return $this;
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Exam_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_docwise')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number',
                'totals_label'      => Mage::helper('bs_docwise')->__('Total trainees')
            )
        );
        $this->addColumn(
            'exam_code',
            array(
                'header'    => Mage::helper('bs_docwise')->__('Code'),
                'align'     => 'left',
                'index'     => 'exam_code',
            )
        );
        

        $this->addColumn(
            'exam_date',
            array(
                'header' => Mage::helper('bs_docwise')->__('Date'),
                'index'  => 'exam_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'exam_type',
            array(
                'header' => Mage::helper('bs_docwise')->__('Exam Type'),
                'index'  => 'exam_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_docwise')->convertOptions(
                    Mage::getModel('bs_docwise/exam_attribute_source_examtype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'cert_type',
            array(
                'header' => Mage::helper('bs_docwise')->__('Certificate Type'),
                'index'  => 'cert_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_docwise')->convertOptions(
                    Mage::getModel('bs_docwise/exam_attribute_source_certtype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'number_trainee',
            array(
                'header'    => Mage::helper('bs_docwise')->__('Number Trainee'),
                'index'     => 'number_trainee',
            )
        );
        $this->addColumn(
            'exam_request_dept',
            array(
                'header' => Mage::helper('bs_docwise')->__('Requested Department'),
                'index'  => 'exam_request_dept',
                'type'  => 'text',
                'renderer'  => 'bs_docwise/adminhtml_helper_column_renderer_dept',

            )
        );

        $this->addColumn(
            'note',
            array(
                'header'    => Mage::helper('bs_docwise')->__('Note'),
                'align'     => 'left',
                'index'     => 'note',
            )
        );

        /*$this->addColumn(
            'easa',
            array(
                'header'  => Mage::helper('bs_docwise')->__('EASA?'),
                'index'   => 'easa',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_docwise')->__('Yes'),
                    '0' => Mage::helper('bs_docwise')->__('No'),
                )
            )
        );*/

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
     * @return BS_Docwise_Block_Adminhtml_Exam_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('exam');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_exam/bs_docwise/exam/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_exam/bs_docwise/exam/delete");

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



        $this->getMassactionBlock()->addItem(
            'gen_four',
            array(
                'label'      => Mage::helper('bs_docwise')->__('Generate 8004 - Report'),
                'url'        => $this->getUrl('*/*/massGenerateFour', array('_current'=>true)),

            )
        );

        $this->getMassactionBlock()->addItem(
            'gen_six',
            array(
                'label'      => Mage::helper('bs_docwise')->__('Generate 8006'),
                'url'        => $this->getUrl('*/*/massGenerateSix', array('_current'=>true)),

            )
        );

        $this->getMassactionBlock()->addItem(
            'gen_nine',
            array(
                'label'      => Mage::helper('bs_docwise')->__('Generate 8009'),
                'url'        => $this->getUrl('*/*/massGenerateNine', array('_current'=>true)),

            )
        );

        $this->getMassactionBlock()->addItem(
            'gen_eleven',
            array(
                'label'      => Mage::helper('bs_docwise')->__('Generate 8011'),
                'url'        => $this->getUrl('*/*/massGenerateEleven', array('_current'=>true)),

            )
        );


        $this->getMassactionBlock()->addItem(
            'exam_type',
            array(
                'label'      => Mage::helper('bs_docwise')->__('Change Exam Type'),
                'url'        => $this->getUrl('*/*/massExamType', array('_current'=>true)),
                'additional' => array(
                    'flag_exam_type' => array(
                        'name'   => 'flag_exam_type',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_docwise')->__('Exam Type'),
                        'values' => Mage::getModel('bs_docwise/exam_attribute_source_examtype')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'cert_type',
            array(
                'label'      => Mage::helper('bs_docwise')->__('Change Certificate Type'),
                'url'        => $this->getUrl('*/*/massCertType', array('_current'=>true)),
                'additional' => array(
                    'flag_cert_type' => array(
                        'name'   => 'flag_cert_type',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_docwise')->__('Certificate Type'),
                        'values' => Mage::getModel('bs_docwise/exam_attribute_source_certtype')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'exam_request_dept',
            array(
                'label'      => Mage::helper('bs_docwise')->__('Change Requested Department'),
                'url'        => $this->getUrl('*/*/massExamRequestDept', array('_current'=>true)),
                'additional' => array(
                    'flag_exam_request_dept' => array(
                        'name'   => 'flag_exam_request_dept',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_docwise')->__('Requested Department'),
                        'values' => Mage::getModel('bs_docwise/exam_attribute_source_examrequestdept')
                            ->getAllOptions(true),

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
     * @param BS_Docwise_Model_Exam
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
     * @return BS_Docwise_Block_Adminhtml_Exam_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _prepareTotals($columns = 'number_trainee'){
        $columns=explode(',',$columns);
        if(!$columns){
            return;
        }
        $this->_countTotals = true;
        $totals = new Varien_Object();
        $fields = array();
        foreach($columns as $column){
            $fields[$column]    = 0;
        }

        foreach ($this->getCollection() as $item) {
            foreach($fields as $field=>$value){
                $fields[$field]+=$item->getData($field);
            }
        }

        $totals->setData($fields);
        $this->setTotals($totals);
        return;
    }
}
