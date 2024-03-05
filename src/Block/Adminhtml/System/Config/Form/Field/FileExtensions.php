<?php
declare(strict_types=1);

namespace Team23\WysiwygDownloads\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * @noinspection PhpUnused
 */
class FileExtensions extends AbstractFieldArray
{
    /**
     * Prepare field for rendering
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'extension',
            ['label' => __('Allowed File Extensions'),]
        );
        $this->_addAfter = false;
    }
}
