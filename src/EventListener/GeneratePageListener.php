<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\CalendarBundle\EventListener;

use Contao\CalendarFeedModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Environment;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Template;

/**
 * @internal
 */
#[AsHook('generatePage')]
class GeneratePageListener
{
    public function __construct(private readonly ContaoFramework $framework)
    {
    }

    /**
     * Adds the feeds to the page header.
     */
    public function __invoke(PageModel $pageModel, LayoutModel $layoutModel): void
    {
        $calendarfeeds = StringUtil::deserialize($layoutModel->calendarfeeds);

        if (empty($calendarfeeds) || !\is_array($calendarfeeds)) {
            return;
        }

        $this->framework->initialize();

        $adapter = $this->framework->getAdapter(CalendarFeedModel::class);

        if (!$feeds = $adapter->findByIds($calendarfeeds)) {
            return;
        }

        $template = $this->framework->getAdapter(Template::class);
        $environment = $this->framework->getAdapter(Environment::class);

        foreach ($feeds as $feed) {
            $GLOBALS['TL_HEAD'][] = $template->generateFeedTag(
                sprintf('%sshare/%s.xml', $feed->feedBase ?: $environment->get('base'), $feed->alias),
                $feed->format,
                $feed->title,
            );
        }
    }
}
