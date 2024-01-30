<?php
declare(strict_types=1);

namespace Skernl\Framework;

use Skernl\Contract\ApplicationInterface;
use Skernl\Di\Annotation\Mount;

/**
 * @ConfigProvider
 * @\Skernl\Framework\ConfigProvider
 */
#[Mount]
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            ApplicationInterface::class => Application::class,
        ];
    }
}