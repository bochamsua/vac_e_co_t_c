<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject tab on curriculum edit form
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Subject extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Bui Phong
     */

    public function __construct()
    {
        parent::__construct();
        $this->setId('subject_grid');
        $this->setDefaultSort('subject_order');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);

    }

    protected function _prepareLayout(){
        $this->setChild('import_subject_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Import Subjects'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/subjectcopy_subjectcopy/new', array('_current'=>false, 'c_to'=>$this->getCurriculum()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        $this->setChild('import_familiar_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Import Familiar Subjects'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/subjectcopy_subjectcopy/new', array('_current'=>false, 'c_to'=>$this->getCurriculum()->getId(),'popup'=>true, 'familiar'=>1)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        $this->setChild('add_subject_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('New Subject'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/subject_subject/new', array('_current'=>false, 'curriculum_id'=>$this->getCurriculum()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        $this->setChild('clear_subject_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Clear Subjects'),
                    'onclick'   => 'deleteConfirm(\'*** THIS ACTION MUST BE TAKEN VERY CAREFULLY!!! All subjects WILL BE REMOVED from curriculum! Are you sure?\',\''.$this->getUrl('*/subject_subject/clear', array('_current'=>false, 'curriculum_id'=>$this->getCurriculum()->getId(),'popup'=>true)).'\')'
                ))
        );

        //window.open(url, '','width=1000,height=700,resizable=1,scrollbars=1');
        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){

            $html.= $this->getChildHtml('add_subject_button');
            $html.= $this->getChildHtml('import_familiar_button');
            $html.= $this->getChildHtml('import_subject_button');
            $html.= $this->getChildHtml('clear_subject_button');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }

    /**
     * prepare the subject collection
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Subject
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_subject/subject_collection')->addFieldToFilter('curriculum_id', $this->getCurriculum()->getId());
        $collection->setOrder('subject_order', 'ASC');//->setOrder('entity_id', 'ASC')
        $this->setCollection($collection);
        parent::_prepareCollection();

        $this->_prepareTotals('subject_hour');
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Subject
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
     * @return BS_Subject_Block_Adminhtml_Traininglist_Curriculum_Edit_Tab_Subject
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_subject')->__('ID'),
                'index'  => 'entity_id',
                'type'=> 'number',
                'totals_label'      => Mage::helper('bs_subject')->__('Total hours')

            )
        );

        $this->addColumn(
            'subject_shortcode',
            array(
                'header' => Mage::helper('bs_subject')->__('Shortcode'),
                'index'  => 'subject_shortcode',
                'editable'       => true,
                'type'          => 'input',
                'renderer'      => 'bs_subject/adminhtml_helper_column_renderer_shortcode',
                'filter'    => false,
                //'sortable'  => false,
                'totals_label'      => ''



            )
        );

        $this->addColumn(
            'subject_name',
            array(
                'header' => Mage::helper('bs_subject')->__('Subject Name'),
                'align'  => 'left',
                'index'  => 'subject_name',
                'renderer' => 'bs_subject/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/subject_subject/edit',
            )
        );

        $this->addColumn(
            'subject_level',
            array(
                'header' => Mage::helper('bs_subject')->__('Level'),
                'index'  => 'subject_level',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'subject_hour',
            array(
                'header' => Mage::helper('bs_subject')->__('Hour'),
                'index'  => 'subject_hour',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'subject_order',
            array(
                'header'         => Mage::helper('bs_subject')->__('Position'),
                'name'           => 'subject_order',
                'editable'       => true,
                'type'          => 'input',
                'renderer'      => 'bs_subject/adminhtml_helper_column_renderer_input',
                //'filter'    => false,
                //'sortable'  => false,
                'totals_label'      => ''
            )
        );

        $this->addColumn(
            'require_exam',
            array(
                'header'  => Mage::helper('bs_subject')->__('Require exam?'),
                'index'   => 'require_exam',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_subject')->__('Yes'),
                    '0' => Mage::helper('bs_subject')->__('No'),
                )
            )
        );

        $this->addColumn(
            'subject_note',
            array(
                'header' => Mage::helper('bs_subject')->__('Remark'),
                'index'  => 'subject_note',
                //'renderer' => 'bs_subject/adminhtml_helper_column_renderer_subject',


            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_subject')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_subject')->__('Edit'),
                        'url'     => array('base' => '*/subject_subject/edit', 'params' => array('popup'=> 1)),
                        'field'   => 'id',
                        'onclick'   => 'window.open(this.href,\'\',\'width=1000,height=700,resizable=1,scrollbars=1\'); return false;'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
                'totals_label'      => ''
            )
        );

        return parent::_prepareColumns();
    }


    /**
     * get row url
     *
     * @access public
     * @param BS_Subject_Model_Subject
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($item)
    {
        return '#';//$this->getUrl('*/register_schedule/new',array('curriculum_id'=>$this->getCurriculum()->getId(),'subject_id'=>$item->getId()));
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
            '*/*/subjectsGrid',
            array(
                'id'=>$this->getCurriculum()->getId()
            )
        );
    }

    /**
     * get the current curriculum
     *
     * @access public
     * @return BS_Traininglist_Model_Curriculum
     * @author Bui Phong
     */
    public function getCurriculum()
    {
        return Mage::registry('current_curriculum');
    }

    protected function _prepareTotals($columns = 'subject_hour'){
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

    protected function _afterToHtml($html)
    {

        $html1 = "<script>
                varienGrid = Class.create(varienGrid, {
                   initGrid: function (\$super) {
                       \$super(); // Calling parent method functionality
                       var table = $(this.containerId+this.tableSufix);
                       this.sortedContainer = table.down('tbody');
                       Sortable.create(this.sortedContainer.identify(), {
                            tag: 'TR',
                            dropOnEmpty:true,
                            containment: [this.sortedContainer.identify()],
                            constraint: false,
                            onChange: this.updateSort.bind(this)
                       });
                   },
                   updateSort: function ()
                   {
                       var rows = this.sortedContainer.childElements(); // Getting all rows
                       for (var i = 0, l = rows.length; i < l; i++) {
                           // Check if input is available
                           if (rows[i].down('input[type=\"text\"]')) {
                               rows[i].down('input[type=\"text\"]').value = i + 1;


                               // Updating is changed flag for element
                               rows[i].down('input[type=\"text\"]').setHasChanges({});
                           }
                       }



                   }
                });

                </script>";
        return parent::_afterToHtml($html);
    }

}
