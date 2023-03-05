<?php

namespace App\Jobs;

use App\Mail\NotifyStatusEndpoint;
use App\Models\EndPoint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerifyEndpoint implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach (EndPoint::query()->with('site')->where('next_check', '<=', now())->lazy() as $endpoint) {
            try {
                $previousStatusLatestLog = $endpoint->latestLog->id === null ? null : $endpoint->latestLog->is_success;
                info('test');
                info($previousStatusLatestLog);
                $response = Http::get($endpoint->fullUrl());
                $data = [
                    'status_code' => $response->status(),
                    'checked_at' => now(),
                    'response_body' => $response->failed() ? Str::limit($response->body(), 1000) : null
                ];
                $endpoint->logs()->create($data);

            } catch (\Throwable $ex) {
                report($ex);
            }
            if ($previousStatusLatestLog !== null && $endpoint->fresh()->latestLog->is_success !== $previousStatusLatestLog) {
                foreach ($endpoint->site->email_notification_list as $email) {
                    Mail::to($email)->send(new NotifyStatusEndpoint($endpoint));
                }
            }

            $endpoint->update([
                'next_check' => now()->addSecond($endpoint->frequency)
            ]);
        }
    }
}
