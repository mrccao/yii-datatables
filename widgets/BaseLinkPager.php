<?php

class BaseLinkPager extends CLinkPager
{

    /**
     * @var string attributes for the pager container tag.
     */
    public $containerTag = null;

    /**
     * @var array HTML attributes for the pager container tag.
     */
    public $containerHtmlOptions = array();

    /**
     * @var boolean whether to display the first and last items.
     */
    public $displayFirstAndLast = true;

    /**
     * @var integer maximum number of page buttons that can be displayed. Defaults to 10.
     */
    public $maxButtonCount = 5;

    public function init()
    {
        if ($this->nextPageLabel === null)
            $this->nextPageLabel = Yii::t('yii', '&gt;');
        if ($this->prevPageLabel === null)
            $this->prevPageLabel = Yii::t('yii', '&lt;');
        if ($this->firstPageLabel === null)
            $this->firstPageLabel = Yii::t('yii', '&lt;&lt;');
        if ($this->lastPageLabel === null)
            $this->lastPageLabel = Yii::t('yii', '&gt;&gt;');

        if (!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $this->getId();
        if (!isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'dataTables_paginate';
        $this->htmlOptions['style'] = $this->htmlOptions['style'] ? $this->htmlOptions['style'] . '; display: flex; list-style: none;' : 'display: flex; list-style: none;';
    }

    public function run()
    {
        $this->registerClientScript();
        $buttons = $this->createPageButtons();
        if (empty($buttons))
            return;
        if ($this->containerTag)
            echo CHtml::openTag($this->containerTag, $this->containerHtmlOptions);
        echo $this->header;
        echo CHtml::tag('ul', $this->htmlOptions, implode("\n", $buttons));
        echo $this->footer;
        if ($this->containerTag)
            echo CHtml::closeTag($this->containerTag);
    }

    /**
     * Creates a page button.
     * You may override this method to customize the page buttons.
     * @param string $label the text label for the button
     * @param integer $page the page number
     * @param string $class the CSS class for the page button.
     * @param boolean $hidden whether this page button is visible
     * @param boolean $selected whether this page button is selected
     * @return string the generated button
     */
    protected function createPageButton($label, $page, $class, $hidden, $selected)
    {
        if ($hidden || $selected) {
            $class .= ' paginate_button ' . ($hidden ? 'disabled' : 'current');
        } else {
            $class = 'paginate_button';
        }

        return CHtml::tag('li', array('class' => $class), CHtml::link($label, $this->createPageUrl($page)));
    }

    /**
     *### .createPageButtons()
     *
     * Creates the page buttons.
     * @return array a list of page buttons (in HTML code).
     */
    protected function createPageButtons()
    {

        if (($pageCount = $this->getPageCount()) <= 1) {
            return array();
        }

        list ($beginPage, $endPage) = $this->getPageRange();

        $currentPage = $this->getCurrentPage(false); // currentPage is calculated in getPageRange()

        $buttons = array();

        // first page
        if ($this->displayFirstAndLast) {
            $buttons[] = $this->createPageButton($this->firstPageLabel, 0, 'first', $currentPage <= 0, false);
        }

        // prev page
        if (($page = $currentPage - 1) < 0) {
            $page = 0;
        }

        $buttons[] = $this->createPageButton($this->prevPageLabel, $page, 'previous', $currentPage <= 0, false);

        // internal pages
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->createPageButton($i + 1, $i, '', false, $i == $currentPage);
        }

        // next page
        if (($page = $currentPage + 1) >= $pageCount - 1) {
            $page = $pageCount - 1;
        }

        $buttons[] = $this->createPageButton(
            $this->nextPageLabel,
            $page,
            'next',
            $currentPage >= ($pageCount - 1),
            false
        );

        // last page
        if ($this->displayFirstAndLast) {
            $buttons[] = $this->createPageButton(
                $this->lastPageLabel,
                $pageCount - 1,
                'last',
                $currentPage >= ($pageCount - 1),
                false
            );
        }

        return $buttons;
    }

}