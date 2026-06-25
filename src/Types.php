<?php

declare(strict_types=1);

namespace RunApi\Veo31;

/**
 * Constants for model slugs supported by the Veo 3.1 PHP SDK.
 */
final class Types
{
    /** @var list<string> */
    public const TEXT_TO_VIDEO_MODELS = ['veo-3.1', 'veo-3.1-fast'];

    /** @var list<string> */
    public const EXTEND_VIDEO_MODELS = [];

    /** @var list<string> */
    public const UPSCALE_VIDEO_MODELS = [];

    private function __construct()
    {
    }
}
