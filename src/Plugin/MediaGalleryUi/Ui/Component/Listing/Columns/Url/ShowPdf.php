<?php
declare(strict_types=1);

namespace Team23\WysiwygDownloads\Plugin\MediaGalleryUi\Ui\Component\Listing\Columns\Url;

use Magento\Framework\UrlInterface;
use Magento\MediaGalleryUi\Ui\Component\Listing\Columns\Url;
use Magento\Store\Model\StoreManagerInterface;
use Team23\WysiwygDownloads\Model\Config;

/**
 * @noinspection PhpUnused
 */
class ShowPdf
{
    /**
     * @param StoreManagerInterface $storeManager
     * @param Config $config
     */
    public function __construct(
        private readonly StoreManagerInterface $storeManager,
        private readonly Config $config
    ) {
    }

    /**
     * Allow PDF in media gallery
     *
     * @param Url $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUnused
     */
    public function afterPrepareDataSource(Url $subject, array $result): array
    {
        if (!$this->config->isMediaGalleryEnabled() || !isset($result['data']['items'])) {
            return $result;
        }

        foreach ($result['data']['items'] as &$item) {
            if (isset($item['content_type']) && str_contains($item['content_type'], 'PDF')) {
                $item['thumbnail_url'] = $this->getPdfIcon();
            }
        }
        return $result;
    }

    /**
     * Retrieve PDF icon URL
     *
     * @return string
     */
    private function getPdfIcon(): string
    {
        try {
            return $this->storeManager
                    ->getStore()
                    ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'pdf-icon.png';
        } catch (\Exception) {
            return $this->storeManager
                    ->getDefaultStoreView()
                    ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'pdf-icon.png';
        }
    }
}
