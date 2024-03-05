<?php
declare(strict_types=1);

namespace Team23\WysiwygDownloads\Plugin\Cms\Model\Wysiwyg\Images\Storage;

use Magento\Cms\Model\Wysiwyg\Images\Storage;
use Magento\Framework\App\Area;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\View\Asset\Repository;
use Team23\WysiwygDownloads\Model\Config;

/**
 * @noinspection PhpUnused
 */
class AllowExtensions
{
    /**
     * @var string|null
     */
    private ?string $type = null;

    /**
     * @param Filesystem $filesystem
     * @param File $file
     * @param Repository $assetRepository
     * @param Config $config
     */
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly File $file,
        private readonly Repository $assetRepository,
        private readonly Config $config
    ) {
    }

    /**
     * Prepare allowed extensions
     *
     * @param Storage $subject
     * @param string|null $type
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUnused
     */
    public function beforeGetAllowedExtensions(Storage $subject, ?string $type): void
    {
        $this->type = $type;
    }

    /**
     * Allow extra file extensions
     *
     * @param Storage $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUnused
     */
    public function afterGetAllowedExtensions(Storage $subject, array $result): array
    {
        return array_merge($result, $this->config->getExtraAllowedExtensions());
    }

    /**
     * Skip resizing of extra file extensions
     *
     * @param Storage $subject
     * @param string $source
     * @param bool $keepRatio
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUnused
     */
    public function beforeResizeFile(Storage $subject, string $source, bool $keepRatio = true): array
    {
        if ($this->isNonImage($source) && ($mediaPath = $this->getFileThumbnail())) {
            $source = $mediaPath;
        }
        return [$source, $keepRatio];
    }

    /**
     * Only resize if file is an image
     *
     * @param Storage $subject
     * @param \Closure $proceed
     * @param string $source
     * @param bool $keepRatio
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @noinspection PhpUnused
     */
    public function aroundResizeFile(Storage $subject, \Closure $proceed, string $source, bool $keepRatio = true): mixed
    {
        if ($this->isNonImage($source)) {
            return $source;
        }
        return $proceed($source, $keepRatio);
    }

    /**
     * Check if source file has non image file extension
     *
     * @param string $source
     * @return bool
     */
    private function isNonImage(string $source): bool
    {
        $pathInfo = $this->file->getPathInfo($source);
        $extension = $pathInfo['extension'] ?? null;
        return $extension && in_array(strtolower($extension), $this->config->getExtraAllowedExtensions());
    }

    /**
     * Retrieve thumbnail for extra file extensions
     *
     * @return string|null
     */
    private function getFileThumbnail(): ?string
    {
        try {
            $asset = $this->assetRepository->createAsset(
                'Team23_WysiwygDownloads::images/pdf-icon.png',
                ['area', Area::AREA_ADMINHTML,]
            );
        } catch (\Exception) {
            return null;
        }

        $mediaPath = $this->filesystem
            ->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath() . 'pdf-icon.png';

        if (!$this->file->fileExists($mediaPath)) {
            $this->file->cp($asset->getSourceFile(), $mediaPath);
        }
        return $mediaPath;
    }
}
