<?php
declare(strict_types=1);

namespace Skernl\Framework;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Skernl\Config\Config;
use Skernl\Framework\Contract\ApplicationInterface;
use SplFileInfo;

/**
 * @Application
 * @\Skernl\Framework\Application
 */
class Application implements ApplicationInterface
{
    public function __construct()
    {
        $this->init();
    }

    private function init(): void
    {
        $this->initConfig();
    }

    /**
     * @return void
     */
    private function initConfig(): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                BASE_PATH . '/config', FilesystemIterator::SKIP_DOTS
            )
        );
        $configArray = [];
        /**
         * @var SplFileInfo $splFileInfo
         */
        foreach ($iterator as $splFileInfo) {
            $name = $splFileInfo->getBasename('.php');
            var_dump($name);
            if ('php' === $splFileInfo->getExtension() && !in_array($name, [
                    'container',
                    'routes',
                ])
            ) {
                $configArray [$splFileInfo->getBasename('.php')] = require_once $splFileInfo->getPathname();
            }
        }
        new Config($configArray);
    }

    private function initServer(array $serverConfig)
    {
    }

    public function run(): void
    {
    }
}