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

use Contao\CalendarEventsModel;
use Contao\CalendarFeedModel;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\StringUtil;

class InsertTagsListener
{
    private const SUPPORTED_TAGS = [
        'event',
        'event_open',
        'event_url',
        'event_title',
        'event_teaser',
    ];

    /**
     * @var ContaoFramework
     */
    private $framework;

    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    /**
     * @return string|false
     */
    public function onReplaceInsertTags(string $tag, bool $useCache, $cacheValue, array $flags)
    {
        $elements = explode('::', $tag);
        $key = strtolower($elements[0]);

        if ('calendar_feed' === $key) {
            return $this->replaceCalendarFeedInsertTag($elements[1]);
        }

        if (\in_array($key, self::SUPPORTED_TAGS, true)) {
            return $this->replaceEventInsertTag($key, $elements[1], $flags);
        }

        return false;
    }

    private function replaceCalendarFeedInsertTag(string $feedId): string
    {
        $this->framework->initialize();

        /** @var CalendarFeedModel $adapter */
        $adapter = $this->framework->getAdapter(CalendarFeedModel::class);

        if (null === ($feed = $adapter->findByPk($feedId))) {
            return '';
        }

        return sprintf('%sshare/%s.xml', $feed->feedBase, $feed->alias);
    }

    private function replaceEventInsertTag(string $insertTag, string $idOrAlias, array $flags): string
    {
        $this->framework->initialize();

        /** @var CalendarEventsModel $adapter */
        $adapter = $this->framework->getAdapter(CalendarEventsModel::class);

        if (null === ($model = $adapter->findByIdOrAlias($idOrAlias))) {
            return '';
        }

        switch ($insertTag) {
            case 'event':
                return sprintf(
                    '<a href="%s" title="%s">%s</a>',
                    \in_array('absolute', $flags, true) ? $model->getAbsoluteUrl() : $model->getFrontendUrl(),
                    StringUtil::specialchars($model->title),
                    $model->title
                );

            case 'event_open':
                return sprintf(
                    '<a href="%s" title="%s">',
                    \in_array('absolute', $flags, true) ? $model->getAbsoluteUrl() : $model->getFrontendUrl(),
                    StringUtil::specialchars($model->title)
                );

            case 'event_url':
                return \in_array('absolute', $flags, true) ? $model->getAbsoluteUrl() : $model->getFrontendUrl();

            case 'event_title':
                return StringUtil::specialchars($model->title);

            case 'event_teaser':
                return StringUtil::toHtml5($model->teaser);
        }

        return '';
    }
}
