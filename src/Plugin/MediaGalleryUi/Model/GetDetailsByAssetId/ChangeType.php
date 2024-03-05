<?php
declare(strict_types=1);

namespace Team23\WysiwygDownloads\Plugin\MediaGalleryUi\Model\GetDetailsByAssetId;

use Magento\MediaGalleryUi\Model\GetDetailsByAssetId;

class ChangeType
{
    /**
     * Change type if necessary
     *
     * @param GetDetailsByAssetId $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterExecute(GetDetailsByAssetId $subject, array $result): array
    {
        foreach ($result as &$item) {
            if ($this->isNonImage($item)) {
                $details = [];

                foreach ($item['details'] as $detail) {
                    if (!isset($detail['title']) || !($detail['title'] instanceof \Magento\Framework\Phrase)) {
                        continue;
                    }

                    if (in_array($detail['title']->getText(), ['Width','Height',])) {
                        continue;
                    }

                    if ($detail['title']->getText() === 'Type') {
                        $detail['value'] = __('PDF');
                    }
                    $details[] = $detail;
                }
                $item['details'] = $details;
            }
        }
        return $result;
    }

    /**
     * Check if item content type is non image
     *
     * @param array $item
     * @return bool
     */
    private function isNonImage(array $item): bool
    {
        return (isset($item['content_type']) && strtolower($item['content_type']) === 'application/pdf');
    }
}
