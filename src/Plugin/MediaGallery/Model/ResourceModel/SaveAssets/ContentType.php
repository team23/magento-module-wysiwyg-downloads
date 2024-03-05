<?php
declare(strict_types=1);

namespace Team23\WysiwygDownloads\Plugin\MediaGallery\Model\ResourceModel\SaveAssets;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\MediaGallery\Model\ResourceModel\SaveAssets;
use Magento\MediaGalleryApi\Api\Data\AssetInterface;
use Magento\MediaGalleryApi\Api\Data\AssetInterfaceFactory;
use Team23\WysiwygDownloads\Model\Config;

/**
 * @noinspection PhpUnused
 */
class ContentType
{
    /**
     * @param Filesystem $filesystem
     * @param File $file
     * @param AssetInterfaceFactory $assetFactory
     * @param Config $config
     */
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly File $file,
        private readonly AssetInterfaceFactory $assetFactory,
        private readonly Config $config
    ) {
    }

    /**
     * Check content type
     *
     * @param SaveAssets $subject
     * @param array $assets
     * @return array|null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUnused
     */
    public function beforeExecute(SaveAssets $subject, array $assets): ?array
    {
        if (empty($assets)) {
            return null;
        }

        $result = [];
        /** @var AssetInterface $asset */
        foreach ($assets as $asset) {
            $result[] = $this->isImage($asset->getPath()) ? $asset : $this->createAsset($asset);
        }
        return [$result];
    }

    /**
     * Check if source file has image file extension
     *
     * @param string $source
     * @return bool
     */
    private function isImage(string $source): bool
    {
        $pathInfo = $this->file->getPathInfo($this->getFilename($source));
        $extension = $pathInfo['extension'] ?? null;
        return $extension === null || !in_array(strtolower($extension), $this->config->getExtraAllowedExtensions());
    }

    /**
     * Create asset
     *
     * @param AssetInterface $asset
     * @return AssetInterface
     */
    private function createAsset(AssetInterface $asset): AssetInterface
    {
        return $this->assetFactory->create([
            'id' => $asset->getId(),
            'path' => $asset->getPath(),
            'title' => $asset->getTitle(),
            'description' => $asset->getDescription(),
            'source' => $asset->getSource(),
            'hash' => $asset->getHash(),
            'contentType' => $this->getContentType($asset->getPath()),
            'width' => 0,
            'height' => 0,
            'size' => $asset->getSize(),
        ]);
    }

    /**
     * Retrieve content type
     *
     * @param string $source
     * @return string
     */
    private function getContentType(string $source): string
    {
        return mime_content_type($this->getFilename($source));
    }

    /**
     * Retrieve file name
     *
     * @param string $source
     * @return string
     */
    private function getFilename(string $source): string
    {
        return $this->filesystem
                ->getDirectoryRead(DirectoryList::MEDIA)
                ->getAbsolutePath() . $source;
    }
}
