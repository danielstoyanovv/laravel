<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Flight;
use App\Mail\SendEmail;
use Mail;

class ProcessFlight implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Indicate if the job should be marked as failed on timeout.
     *
     * @var bool
     */
    public $failOnTimeout = true;

    /**
     * flight model
     * 
     * @var Flight
     */
    protected $flight;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Flight $flight
    ) {
        $this->flight = $flight;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SendEmail();
        Mail::to('daniel.stoyanovv@gmail.com')->send($email);
    }
}
