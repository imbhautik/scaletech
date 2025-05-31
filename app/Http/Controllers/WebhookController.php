<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\GitHubCommit;
use App\Models\StripeTransaction;
use App\Models\CustomPayload;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Incoming webhook', ['payload' => $request->all()]);

        if (!$request->isJson()) {
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        $source = $request->header('X-Webhook-Source') ?? $request->input('source');

        try {
            switch ($source) {
                case 'github':
                    return $this->handleGitHub($request);
                case 'stripe':
                    return $this->handleStripe($request);
                default:
                    return $this->handleCustom($request);
            }
        } catch (\Exception $e) {
            Log::error('Webhook handling failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal error'], 500);
        }
    }

    private function handleGitHub(Request $request)
    {
        $commits = $request->input('commits', []);

        foreach ($commits as $commit) {
            GitHubCommit::create([
                'commit_id' => $commit['id'],
                'message' => $commit['message'],
                'author' => $commit['author']['name'] ?? 'unknown',
            ]);
        }

        return response()->json(['status' => 'GitHub webhook processed'], 200);
    }

    private function handleStripe(Request $request)
    {
        $data = $request->input('data.object', []);

        StripeTransaction::create([
            'amount' => $data['amount'] ?? 0,
            'currency' => $data['currency'] ?? 'unknown',
            'status' => $data['status'] ?? 'unknown',
        ]);

        return response()->json(['status' => 'Stripe webhook processed'], 200);
    }

    private function handleCustom(Request $request)
    {
        CustomPayload::create([
            'payload' => $request->all(),
        ]);

        return response()->json(['status' => 'Custom webhook processed'], 200);
    }

}
