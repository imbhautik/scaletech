<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\GithubCommit;

class WebhookHandlerTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * Test handling a GitHub webhook with commits.
     *
     * @return void
     */
    public function test_github_webhook_stores_commits()
    {
        $payload = [
            "source" => "github",
            "commits" => [
                [
                    "id" => "8897",
                    "message" => "first commit",
                    "author" => [ "name" => "Bhautik A." ]
                ],
                [
                    "id" => "08081997",
                    "message" => "Added README file",
                    "author" => [ "name" => "Bhautik Amipara" ]
                ]
            ]
        ];

        $response = $this->postJson('/api/webhook', $payload, [
            'X-Webhook-Source' => 'github',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('github_commits', [
            'commit_id' => '8897',
            'message' => 'first commit',
            'author' => 'Bhautik A.',
        ]);

        $this->assertDatabaseHas('github_commits', [
            'commit_id' => '08081997',
            'message' => 'Added README file',
            'author' => 'Bhautik Amipara',
        ]);

        $response->assertJson([
            'status' => 'GitHub webhook processed',
        ]);
    }
}
