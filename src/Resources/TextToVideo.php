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
 * Generates video from a text prompt. Optionally set first_frame_image_url to animate from a starting image, or use input_mode "reference" with reference_image_urls for style/subject-guided generation.
 */
readonly class TextToVideo extends TypedConfiguredResource
{
    /**
     * Submits a text-to-video generation task and returns immediately with the task id.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   duration_seconds?: int,
     *   first_frame_image_url?: string,
     *   input_mode?: string,
     *   last_frame_image_url?: string,
     *   reference_image_urls?: list<string>
     * } $params
     */
    public function create(array $params, ?RequestOptions $options = null): TaskCreateResponse
    {
        return parent::create($params, $options);
    }

    /**
     * Retrieves the current status and result of a text-to-video task.
     */
    public function get(string $id, ?RequestOptions $options = null): VideoTaskResponse
    {
        $response = parent::get($id, $options);

        /** @var VideoTaskResponse $response */
        return $response;
    }

    /**
     * Submits a text-to-video task and polls until it completes or fails.
     *
     * @param array{
     *   model: string,
     *   prompt: string,
     *   aspect_ratio?: string,
     *   callback_url?: string,
     *   duration_seconds?: int,
     *   first_frame_image_url?: string,
     *   input_mode?: string,
     *   last_frame_image_url?: string,
     *   reference_image_urls?: list<string>
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
            '/api/v1/veo_3_1/text_to_video',
            'veo-3-1/text-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
            Types::TEXT_TO_VIDEO_MODELS,
            'text-to-video',
            VideoTaskResponse::class,
            CompletedVideoTaskResponse::class,
        );
    }
}
