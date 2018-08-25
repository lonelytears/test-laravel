<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Recruit;

class RecruitNotify extends Mailable
{
    use Queueable, SerializesModels;

    public $recruit;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Recruit $recruit)
    {
        $this->recruit = $recruit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('notify@openapi.link', 'Pulin Notify')
                    ->view('emails/recruit/notify', [
                        'recruit' => $this->recruit
                    ])->subject('朴邻: 有一位求职者投递了简历');
    }
}
