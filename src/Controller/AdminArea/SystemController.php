<?php

namespace App\Controller\AdminArea;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

#[Route('/system-audit', name: 'admin_system_audit_')]
class SystemController extends AbstractController
{
    private const LOG_PATTERN = '/\[(?P<date>.*)\] (?P<channel>\w+).(?P<level>\w+): (?P<message>(.*)) (?P<details>[{].*[}]|\[.*\]) /';
    //'/\[(?P<date>.*)\] (?P<channel>\w+).(?P<level>\w+): (?P<message>[^\[\{].*[\]\}])/'
    //'/\[(?P<date>.*)\] (?P<channel>\w+).(?P<level>\w+): (?P<message>.*) (?P<details>[\{(].*?[)\}])?/',
    //'/\[(?P<date>.*)\] (?P<channel>\w+).(?P<level>\w+): (?P<message>.*) (?P<details>(?:[{].*?[}]))/',

    #[Route('/informations', name: 'informations')]
    public function informations(KernelInterface $kernel)
    {
        return $this->render('admin_area/system_audit/informations.html.twig', [
            'kernel'           => $kernel,
            'php_version'      => phpversion(),
            'default_timezone' => date_default_timezone_get(),
        ]);
    }

    #[Route('/phpinfo', name: 'phpinfo')]
    public function phpinfo(RouterInterface $router, Request $request)
    {
        $referer = $request->headers->get('referer');

        if ($referer) {
            $refererPathinfo = Request::create($referer)->getPathInfo();
            $routeInfos      = $router->match($refererPathinfo);
            $refererRoute    = $routeInfos['_route'];

            if ('admin_system_audit_informations' === $refererRoute) {
                ob_start();
                phpinfo();
                $phpinfo = ob_get_contents();
                ob_get_clean();

                return new Response($phpinfo);
            }
        }

        throw $this->createAccessDeniedException();
    }

    #[Route('/log', name: 'log', methods: ['GET', 'POST'])]
    public function systemLog(KernelInterface $kernel, Filesystem $filesystem, Request $request): Response {
        $logs       = [];
        $finder     = (new Finder())->in($kernel->getLogDir());
        $file       = $request->request->get('selected_file');
        $displayAll = $request->request->get('display_all');

        if (!$file) {
            if ($filesystem->exists($kernel->getLogDir() . '/' . $kernel->getEnvironment() . '.log')) {
                $file = $kernel->getEnvironment();
            } else {
                foreach ($finder as $item) {
                    if ('log' === $item->getExtension()) {
                        $file = $item->getFilenameWithoutExtension();
                        continue;
                    }
                }
            }
        }

        $availableLogFiles = [];

        foreach ($finder->in($kernel->getLogDir()) as $item) {
            if ('log' === $item->getExtension()) {
                $availableLogFiles[$item->getFilenameWithoutExtension()] = $item->getFilename();
            }
        }

        if ($file && $filesystem->exists($wantedFile = $kernel->getLogDir() . '/' . $file . '.log')) {
            $logContent     = file_get_contents($wantedFile);
            $explodedLog    = explode(PHP_EOL, $logContent);
            $logNotReadable = false;

            foreach ($explodedLog as $log) {
                if (!$log) {
                    continue;
                }

                preg_match(self::LOG_PATTERN, $log, $data);

                if (!isset($data['date'])) {
                    $logNotReadable = true;
                    continue;
                }

                if (!$displayAll && in_array($data['level'], ['DEBUG', 'INFO'])) {
                    continue;
                }

                $parsedLog = [
                    'date_time' => new \DateTime($data['date']),
                    'level'     => $data['level'],
                    'channel'   => $data['channel'],
                    'message'   => $data['message'],
                    'details'   => null,
                ];

                if (array_key_exists('details', $data) && $data['details']) {
                    $parsedLog['details'] = $data['details'];
                }

                $logs[] = $parsedLog;
            }

            if ($logNotReadable) {
                $this->addFlash('error', 'L\'ensemble des logs n\'a pu être restitué.');
            }
        } else {
            $this->addFlash('error', 'Une erreur s\'est produite lors de la consultation des logs.');
        }

        return $this->render('admin_area/system_audit/log.html.twig', [
            'logs'                => $logs,
            'available_log_files' => $availableLogFiles,
            'current_file'        => $file,
        ]);
    }

    #[Route('/delete-log', name: 'delete_log', methods: ['POST'])]
    public function removeLog(KernelInterface $kernel, Filesystem $filesystem, Request $request): RedirectResponse {
        if ($file = $request->request->get('selected_file')) {
            $fileName = $kernel->getLogDir() . '/' . $file . '.log';

            if ($filesystem->exists($fileName)) {
                $filesystem->remove($fileName);

                $this->addFlash('success', 'Le fichier ' . $file . '.log a bien été effacé.');
            }
        } else {
            $this->addFlash('error', 'Une erreur s\'est produite, veuillez réessayer.');
        }

        return $this->redirectToRoute('admin_system_audit_log');
    }
}