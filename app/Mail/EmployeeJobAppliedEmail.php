<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeJobAppliedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $EmployeeMailData;

    public function __construct($EmployeeMailData)
    {
        $this->EmployeeMailData = $EmployeeMailData;
    }

    public function build()
    {
        return $this->subject('Job Application Received')
                    ->view('email.employee_job_applied_email')
                    ->with('employeeMailData', $this->EmployeeMailData);
    }
}
