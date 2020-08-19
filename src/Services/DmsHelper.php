<?php

namespace App\Services;

use App\Entity\DmsFolder;
use App\Repository\DmsFolderRepository;

class DmsHelper
{
    private $dmsRootDir;
    private $dmsFolderRepository;

    public function __construct(
        DmsFolderRepository $dmsFolderRepository,
        $projectDir
    ) {
        $this->dmsFolderRepository = $dmsFolderRepository;
        $this->dmsRootDir          = $projectDir . '/public/uploads';
    }

    public function getFullPath(DmsFolder $dmsFolder, $withRootDir = false)
    {
        $path = $this->getParentPath($dmsFolder);

        return ($withRootDir ? $this->dmsRootDir : '') . $path;
    }

    private function getParentPath(DmsFolder $dmsFolder, $path = '')
    {
        $path = '/' . ($dmsFolder->getId() !== 1 ? $dmsFolder->getName() : '') . $path;

        if ($dmsFolder->getParentDmsFolder() && $dmsFolder->getParentDmsFolder()->getId() !== 1) {
            return $this->getParentPath($dmsFolder->getParentDmsFolder(), $path);
        }

        return $path;
    }
}
