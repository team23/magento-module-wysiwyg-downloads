<?php
declare(strict_types=1);

namespace Team23\WysiwygDownloads\Plugin\MediaGalleryUi\Ui\Component\Control\UploadAssets;

use Magento\MediaGalleryUi\Ui\Component\Control\UploadAssets;

/**
 * @noinspection PhpUnused
 */
class ChangeButton
{
    /**
     * Override button label
     *
     * @param UploadAssets $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUnused
     */
    public function afterGetButtonData(UploadAssets $subject, array $result): array
    {
        if (isset($result['label']) && $result['label']->getText() === 'Upload Image') {
            $result['label'] = __('Upload Image / File');
        }
        return $result;
    }
}
