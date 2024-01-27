<?php
declare(strict_types=1);

namespace Skernl\Framework;

use Skernl\Contract\{
    ApplicationInterface,
    ContainerInterface,
};
use Composer\Autoload\ClassLoader;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionException;
use Skernl\Config\Config;
use Skernl\Di\Definition\DefinitionSource;
use Skernl\Di\Definition\SourceManager;
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
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->initSource();
        $this->initConfig();
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    private function initSource(): void
    {
        $loaders = ClassLoader::getRegisteredLoaders();
        /**
         * @var ClassLoader $classLoader
         */
        $classLoader = reset($loaders);
        $sourceManager = new SourceManager(
            array_keys(
                $classLoader->getClassMap()
            )
        );
        /** @noinspection PhpUnhandledExceptionInspection */
        $sourceManager->getSource(DefinitionSource::class)->newInstance();
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
}