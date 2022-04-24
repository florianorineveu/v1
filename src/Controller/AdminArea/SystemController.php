<?php

namespace App\Controller\AdminArea;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/system-audit', name: 'admin_system_audit_')]
class SystemController extends AbstractController
{
    private const LOG_PATTERN = '/\[(?P<date>.*)\] (?P<channel>\w+).(?P<level>\w+): (?P<message>(.*)) (?P<details>[{].*[}]|\[.*\]) /';
    //'/\[(?P<date>.*)\] (?P<channel>\w+).(?P<level>\w+): (?P<message>[^\[\{].*[\]\}])/'
    //'/\[(?P<date>.*)\] (?P<channel>\w+).(?P<level>\w+): (?P<message>.*) (?P<details>[\{(].*?[)\}])?/',
    //'/\[(?P<date>.*)\] (?P<channel>\w+).(?P<level>\w+): (?P<message>.*) (?P<details>(?:[{].*?[}]))/',

    #[Route('/log', name: 'log', methods: ['GET', 'POST'])]
    public function systemLog(KernelInterface $kernel, Filesystem $filesystem, Request $request): Response {
        $logs       = [];
        $finder     = new Finder();
        $file       = $request->request->get('selected_file') ?? $kernel->getEnvironment();
        $displayAll = $request->request->get('display_all');

        $availableLogFiles = [];

        foreach ($finder->in($kernel->getLogDir()) as $item) {
            if ('log' === $item->getExtension()) {
                $availableLogFiles[$item->getFilenameWithoutExtension()] = $item->getFilename();
            }
        }

        if ($filesystem->exists($kernel->getLogDir()) && $filesystem->exists($wantedFile = $kernel->getLogDir() . '/' . $file . '.log')) {
            $logContent = file_get_contents($wantedFile);

            $explodedLog = explode(PHP_EOL, $logContent);

            foreach ($explodedLog as $log) {
                if (!$log) {
                    continue;
                }

                preg_match(self::LOG_PATTERN, $log, $data);

                if (!isset($data['date']) || (!$displayAll && in_array($data['level'], ['DEBUG', 'INFO']))) {
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
        } else {
            $this->addFlash('error', 'Une erreur s\'est produite lors de la consultation des logs.');
        }

        return $this->render('admin_area/system_audit/log.html.twig', [
            'logs'                => $logs,
            'available_log_files' => $availableLogFiles,
            'current_file'        => $file,
        ]);
    }
}