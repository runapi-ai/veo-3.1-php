<?php

declare(strict_types=1);

namespace RunApi\Veo31\Resources;

use RunApi\Core\Http\HttpClient;
use RunApi\Core\Models\TaskCreateResponse;
use RunApi\Core\RequestOptions;
use RunApi\Core\Resources\TypedConfiguredResource;
use RunApi\Veo31\Models\CompletedVideoTaskResponse;
use RunApi\Veo31\Models\VideoTaskResponse;
use RunApi\Veo31\Types;

/**
 * Increases the resolution of a previously generated video to 1080p or 4K. Requires the source_task_id of a completed TextToVideo or ExtendVideo task.
 */
readonly class UpscaleVideo extends TypedConfiguredResource
{
    /**
     * Submits a video upscale task and returns immediately with the task id.
     *
     * @param array{
     *   callback_url?: string,
     *   output_resolution?: string,
     *   prompt?: string
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Retrieves the current status and result of a video upscale task.
     */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Submits a video upscale task and polls until it completes or fails.
     *
     * @param array{
     *   callback_url?: string,
     *   output_resolution?: string,
     *   prompt?: string
     * } $params
     */
    public function run(array $params, ?RequestOptions $options = null): CompletedVideoTaskResponse
    {
        $response = parent::run($params, $options);

        /** @var CompletedVideoTaskResponse $response */
        return $response;
    }

    /**
     * Create the resource using the shared RunAPI HTTP transport.
     */
    public static function fromHttp(HttpClient $http): self
    {
        return new self(
            $http,
            '/api/v1/veo_3_1/upscale_video',
            'veo-3-1/upscale-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            Types::UPSCALE_VIDEO_MODELS,
            'upscale-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}
