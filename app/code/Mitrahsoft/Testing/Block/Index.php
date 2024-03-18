<?php
namespace Mitrahsoft\Testing\Block;
class Index extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }
    public function displayContent()
    {
        return __('Hello World!');
    }

    public function myFunction()
    {
        return __('Hello World 123!');
    }
}