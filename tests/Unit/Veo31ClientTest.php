<?php

declare(strict_types=1);

namespace RunApi\Veo31\Tests\Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RunApi\Core\ClientOptions;
use RunApi\Core\Errors\ValidationException;
use RunApi\Core\Tests\Fixtures\QueueHttpClient;
use RunApi\Veo31\Models\CompletedVideoTaskResponse;
use RunApi\Veo31\Resources\ExtendVideo;
use RunApi\Veo31\Resources\TextToVideo;
use RunApi\Veo31\Resources\UpscaleVideo;
use RunApi\Veo31\Veo31Client;

final class Veo31ClientTest extends TestCase
{
    public function testExposesTypedResources(): void
    {
        $client = new Veo31Client(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        self::assertInstanceOf(TextToVideo::class, $client->textToVideo);
        self::assertInstanceOf(ExtendVideo::class, $client->extendVideo);
        self::assertInstanceOf(UpscaleVideo::class, $client->upscaleVideo);
    }

    public function testCreatePostsCompactedBodyToCorrectPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
        ]);
        $client = new Veo31Client(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $task = $client->textToVideo->create([
            'model' => 'veo-3.1',
            'aspect_ratio' => '16:9',
            'duration_seconds' => 4,
            'first_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
            'input_mode' => 'text',
            'last_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
            'prompt' => 'A product render',
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
            'callback_url' => '',
            'seed' => null,
        ]);

        $body = json_decode((string) $transport->requests[0]->getBody(), true, flags: JSON_THROW_ON_ERROR);

        self::assertSame('task_1', $task->id);
        self::assertSame('/api/v1/veo_3_1/text_to_video', $transport->requests[0]->getUri()->getPath());
        self::assertSame('veo-3.1', $body['model']);
        self::assertArrayNotHasKey('callback_url', $body);
        self::assertArrayNotHasKey('seed', $body);
    }

    public function testRunReturnsTypedCompletedResponseAndPreservesUnknownFields(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed","videos":[{"url":"https://file.runapi.ai/result"}],"extra_field":"kept"}'),
        ]);
        $client = new Veo31Client(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $result = $client->textToVideo->run([
            'model' => 'veo-3.1',
            'aspect_ratio' => '16:9',
            'duration_seconds' => 4,
            'first_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
            'input_mode' => 'text',
            'last_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
            'prompt' => 'A product render',
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
        ]);

        self::assertInstanceOf(CompletedVideoTaskResponse::class, $result);
        self::assertSame('https://file.runapi.ai/result', $result->videos[0]->url);
        self::assertSame('kept', $result->toArray()['extra_field']);
        self::assertSame('/api/v1/veo_3_1/text_to_video/task_1', $transport->requests[1]->getUri()->getPath());
    }

    public function testCompletedResponseRequiresResultFiles(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_1"}'),
            new Response(200, [], '{"id":"task_1","status":"completed"}'),
        ]);
        $client = new Veo31Client(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('videos is required');

        $client->textToVideo->run([
            'model' => 'veo-3.1',
            'aspect_ratio' => '16:9',
            'duration_seconds' => 4,
            'first_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
            'input_mode' => 'text',
            'last_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
            'prompt' => 'A product render',
            'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
        ]);
    }

    public function testRejectsInvalidContractEnum(): void
    {
        $client = new Veo31Client(new ClientOptions(apiKey: 'k', httpClient: new QueueHttpClient([]), maxRetries: 0));

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('input_mode must be one of the allowed values');

        $client->textToVideo->create([
        'model' => 'veo-3.1',
        'aspect_ratio' => '16:9',
        'duration_seconds' => 4,
        'first_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
        'last_frame_image_url' => 'https://cdn.runapi.ai/public/samples/image.jpg',
        'prompt' => 'A product render',
        'reference_image_urls' => ['https://cdn.runapi.ai/public/samples/image.jpg'],
        'input_mode' => 'not-valid',
        ]);
    }

    public function testSecondaryResourceUsesItsOwnPath(): void
    {
        $transport = new QueueHttpClient([
            new Response(200, [], '{"id":"task_2"}'),
        ]);
        $client = new Veo31Client(new ClientOptions(apiKey: 'k', httpClient: $transport, maxRetries: 0));

        $client->extendVideo->create([
            'prompt' => 'A product render',
            'source_task_id' => 'task_source',
        ]);

        self::assertSame('/api/v1/veo_3_1/extend_video', $transport->requests[0]->getUri()->getPath());
    }
}
