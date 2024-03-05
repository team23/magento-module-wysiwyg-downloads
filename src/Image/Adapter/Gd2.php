<?php
declare(strict_types=1);

namespace Team23\WysiwygDownloads\Image\Adapter;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Psr\Log\LoggerInterface;
use Team23\WysiwygDownloads\Model\Config;

class Gd2 extends \Magento\Framework\Image\Adapter\Gd2
{
    /**
     * @param Filesystem $filesystem
     * @param LoggerInterface $logger
     * @param File $file
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Filesystem $filesystem,
        LoggerInterface $logger,
        private readonly File $file,
        private readonly Config $config,
        array $data = []
    ) {
        parent::__construct($filesystem, $logger, $data);
    }

    /**
     * Only open images for processing
     *
     * @param string $filename
     * @return void
     * @throws FileSystemException
     */
    public function open($filename): void
    {
        if ($this->isImage($filename)) {
            parent::open($filename);
        }
    }

    /**
     * Only save images
     *
     * @param null|string $destination
     * @param null|string $newName
     * @return void
     * @throws \Exception
     */
    public function save($destination = null, $newName = null): void
    {
        $fileName = $this->_prepareDestination($destination, $newName);
        if ($this->isImage($fileName)) {
            parent::save($destination, $newName);
        }
    }

    /**
     * Check if source file has image file extension
     *
     * @param string $source
     * @return bool
     */
    private function isImage(string $source): bool
    {
        $pathInfo = $this->file->getPathInfo($source);
        $extension = $pathInfo['extension'] ?? null;
        return $extension === null || !in_array(strtolower($extension), $this->config->getExtraAllowedExtensions());
    }
}
