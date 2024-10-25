<?php

/**
 * @package ThemePlate
 * @since   0.1.0
 */

namespace PBWebDev\CardanoPress\TapTools;

use CardanoPress\Foundation\AbstractTemplates;

class Templates extends AbstractTemplates
{
    public const OVERRIDES_PREFIX = 'cardanopress/taptools/';

    protected function initialize(): void
    {
    }

    protected function getLoaderFile(): string
    {
        return '';
    }
}
