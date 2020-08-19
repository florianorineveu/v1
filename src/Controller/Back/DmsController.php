<?php

namespace App\Controller\Back;

use App\Entity\DmsFolder;
use App\Form\DmsFolderType;
use App\Services\DmsHelper;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dms", name="back_dms_")
 */
class DmsController extends AbstractController
{
    /**
     * @Route("/{parentDmsFolderId}", name="index", methods={"GET"})
     */
    public function index(
        EntityManagerInterface $em,
        DmsHelper $dmsHelper,
        KernelInterface $kernel,
        Filesystem $filesystem,
        $parentDmsFolderId = 1
    ): Response {
        $dmsFolderRepository = $em->getRepository(DmsFolder::class);
        $currentFolder       = $dmsFolderRepository->find($parentDmsFolderId);

        if (!$currentFolder && $parentDmsFolderId == 1) {
            $currentFolder = new DmsFolder();
            $currentFolder->setName('root');

            $filesystem->mkdir($kernel->getProjectDir() . '/public/uploads/', 0755);

            if ($filesystem->exists($kernel->getProjectDir() . '/public/uploads/')) {
                $em->persist($currentFolder);
                $em->flush();
            }
        }

        $dmsFolders = $dmsFolderRepository->findBy([
            'parentDmsFolder' => $currentFolder,
        ]);

        return $this->render('back/dms/index.html.twig', [
            'current_folder' => $currentFolder,
            'dms_folders' => $dmsFolders,
            'full_path'   => $dmsHelper->getFullPath($currentFolder),
        ]);
    }

    /**
     * @Route("/new/{parentDmsFolderId}", name="new", methods={"GET","POST"})
     */
    public function new(
        EntityManagerInterface $em,
        DmsHelper $dmsHelper,
        Filesystem $filesystem,
        Request $request,
        $parentDmsFolderId
    ): Response {
        $parentDmsFolder = $em->getRepository(DmsFolder::class)->find($parentDmsFolderId);

        if (!$parentDmsFolder) {
            throw $this->createNotFoundException();
        }

        $dmsFolder = new DmsFolder();
        $form = $this->createForm(DmsFolderType::class, $dmsFolder);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parentDmsFolder->addDmsFolder($dmsFolder);
            $fullPath = $dmsHelper->getFullPath($dmsFolder, true);

            $filesystem->mkdir($fullPath);

            if ($filesystem->exists($fullPath)) {
                $em->persist($dmsFolder);
                $em->flush();

                return $this->redirectToRoute('back_dms_index', [
                    'parentDmsFolderId' => $dmsFolder->getId(),
                ]);
            }
        }

        return $this->render('back/dms/new.html.twig', [
            'project' => $dmsFolder,
            'form' => $form->createView(),
        ]);
    }
}
