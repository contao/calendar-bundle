<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * Copyright (c) 2005-2018 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\CalendarBundle\Tests\ContaoManager;

use Contao\CalendarBundle\ContaoCalendarBundle;
use Contao\CalendarBundle\ContaoManager\Plugin;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use PHPUnit\Framework\TestCase;

class PluginTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $plugin = new Plugin();

        $this->assertInstanceOf('Contao\CalendarBundle\ContaoManager\Plugin', $plugin);
    }

    public function testReturnsTheBundles(): void
    {
        $parser = $this->createMock(ParserInterface::class);

        /** @var BundleConfig $config */
        $config = (new Plugin())->getBundles($parser)[0];

        $this->assertInstanceOf('Contao\ManagerPlugin\Bundle\Config\BundleConfig', $config);
        $this->assertSame(ContaoCalendarBundle::class, $config->getName());
        $this->assertSame([ContaoCoreBundle::class], $config->getLoadAfter());
        $this->assertSame(['calendar'], $config->getReplace());
    }
}
