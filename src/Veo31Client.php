<?php

declare(strict_types=1);

namespace RunApi\Veo31;

use RunApi\Core\BaseClient;
use RunApi\Core\ClientOptions;
use RunApi\Veo31\Resources\ExtendVideo;
use RunApi\Veo31\Resources\TextToVideo;
use RunApi\Veo31\Resources\UpscaleVideo;

/**
 * Provides Veo 3.1 video generation, extension, and upscaling operations.
 *
 * Exposes typed model resources plus the universal files and account resources.
 */
final class Veo31Client extends BaseClient
{
    /**
     * Generates video from text, an image starting frame, or reference images.
     */
    public readonly TextToVideo $textToVideo;
    /**
     * Appends footage to a previously generated video.
     */
    public readonly ExtendVideo $extendVideo;
    /**
     * Increases video resolution to 1080p or 4K.
     */
    public readonly UpscaleVideo $upscaleVideo;

    /**
     * Create a Veo 3.1 client with optional API key, base URL, and transport overrides.
     */
    public function __construct(ClientOptions $options = new ClientOptions())
    {
        parent::__construct($options);
        $this->textToVideo = TextToVideo::fromHttp($this->http);
        $this->extendVideo = ExtendVideo::fromHttp($this->http);
        $this->upscaleVideo = UpscaleVideo::fromHttp($this->http);
    }
}
