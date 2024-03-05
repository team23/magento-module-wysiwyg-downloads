<?php
declare(strict_types=1);

namespace Team23\WysiwygDownloads\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;

class Config
{
    private const CONFIG_PATH_FILE_EXTENSIONS = 'cms/wysiwyg/file_extensions';
    private const CONFIG_PATH_MEDIA_GALLERY = 'system/media_gallery/enabled';

    /**
     * @var array|string[]
     */
    private array $defaultFileExtensions = [
        'svg',
        'pdf',
        'doc',
        'docx',
        'docm',
        'zip'
    ];

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param SerializerInterface $serializer
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * Retrieve extra allowed file extensions
     *
     * @return array|string[]
     */
    public function getExtraAllowedExtensions(): array
    {
        $configValue = $this->scopeConfig->getValue(self::CONFIG_PATH_FILE_EXTENSIONS);
        if (empty($configValue)) {
            return $this->defaultFileExtensions;
        }

        $fileExtensions = [];
        if ($settings = $this->serializer->unserialize($configValue)) {
            foreach ($settings as $extension) {
                $fileExtensions[] = $extension->extension;
            }
        }
        return array_merge($fileExtensions, $this->defaultFileExtensions);
    }

    /**
     * Check if media gallery is enabled
     *
     * @return bool
     */
    public function isMediaGalleryEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_MEDIA_GALLERY);
    }
}
