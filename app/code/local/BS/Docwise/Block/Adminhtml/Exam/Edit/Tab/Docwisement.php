<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam - product relation edit block
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Block_Adminhtml_Exam_Edit_Tab_Docwisement extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access protected
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('docwisement_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        if ($this->getExam()->getId()) {
            $this->setDefaultFilter(array('in_docwisements'=>1));
        }
    }

    protected function _prepareLayout(){
        $this->setChild('add_doc_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('New Doc'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/docwise_docwisement/new', array('_current'=>false, 'exam_id'=>$this->getExam()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        parent::_prepareLayout();
    }

    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_doc_button');


            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }

    /**
     * prepare the product collection
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Exam_Edit_Tab_Product
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_docwise/docwisement_collection');

        if ($this->getExam()->getId()) {
            $constraint = 'related.exam_id='.$this->getExam()->getId();
        } else {
            $constraint = 'related.exam_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('bs_docwise/exam_docwisement')),
            'related.docwisement_id = main_table.entity_id AND '.$constraint,
            array('position')
        );


        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Exam_Edit_Tab_Product
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return BS_Docwise_Block_Adminhtml_Exam_Edit_Tab_Product
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
            'in_docwisements',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_docwisements',
                'values'=> $this->_getSelectedDocwisements(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );

        $this->addColumn(
            'doc_name',
            array(
                'header'    => Mage::helper('bs_docwise')->__('Document Name'),
                'align'     => 'left',
                'index'     => 'doc_name',
            )
        );


        $this->addColumn(
            'doc_type',
            array(
                'header' => Mage::helper('bs_docwise')->__('Document Type'),
                'index'  => 'doc_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_docwise')->convertOptions(
                    Mage::getModel('bs_docwise/docwisement_attribute_source_doctype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'doc_date',
            array(
                'header' => Mage::helper('bs_docwise')->__('Date'),
                'index'  => 'doc_date',
                'type'=> 'date',

            )
        );

        $this->addColumn(
            'download',
            array(
                'header'  =>  Mage::helper('bs_docwise')->__('View/Download'),
                'type'      =>'text',
                'renderer' => 'bs_docwise/adminhtml_helper_column_renderer_download',

                'filter'    => false,
                'sortable'  => false,
            )
        );

        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('catalog')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
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
                        'url'     => array('base'=> '*/docwise_docwisement/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
    }

    /**
     * Retrieve selected products
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    protected function _getSelectedDocwisements()
    {
        $docwisements = $this->getExamDocwisements();
        if (!is_array($docwisements)) {
            $docwisements = array_keys($this->getSelectedDocwisements());
        }
        return $docwisements;
    }

    /**
     * Retrieve selected products
     *
     * @access protected
     * @return array
     * @author Bui Phong
     */
    public function getSelectedDocwisements()
    {
        $docwisements = array();
        $selected = Mage::registry('current_exam')->getSelectedDocwisements();
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $docwisement) {
            $docwisements[$docwisement->getId()] = array('position' => $docwisement->getPosition());
        }
        return $docwisements;
    }

    /**
     * get row url
     *
     * @access public
     * @param BS_Docwise_Model_Product
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/docwisementsGrid',
            array(
                'id' => $this->getExam()->getId()
            )
        );
    }

    /**
     * get the current exam
     *
     * @access public
     * @return BS_Docwise_Model_Exam
     * @author Bui Phong
     */
    public function getExam()
    {
        return Mage::registry('current_exam');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return BS_Docwise_Block_Adminhtml_Exam_Edit_Tab_Product
     * @author Bui Phong
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_docwisements') {
            $docwisementIds = $this->_getSelectedDocwisements();
            if (empty($docwisementIds)) {
                $docwisementIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $docwisementIds));
            } else {
                if ($docwisementIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $docwisementIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
