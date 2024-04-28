<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $resumePath;
    public function __construct($mailData,$resumePath)
    {
        $this->mailData = $mailData;
        $this->resumePath=$resumePath;
    }

    public function build()
    {
        return $this->subject('New Job Application')
                    ->view('email.job_notification_email')
                    ->with('mailData', $this->mailData)
                    ->attach($this->resumePath);;
    }
}
