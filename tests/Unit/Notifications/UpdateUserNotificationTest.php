<?php


namespace Tests\Unit\Notifications;


use App\Notifications\UpdateUserNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Tests\TestCase;

class UpdateUserNotificationTest extends TestCase
{
    /**
     * @test
     * @testdox
     */
    public function caseOne()
    {
        $this->assertInstanceOf(Notification::class, new UpdateUserNotification([], []));
    }

    /**
     * @test
     * @testdox
     */
    public function caseTwo()
    {
        $this->assertIsArray((new UpdateUserNotification([], []))->via("test"));
    }

    /**
     * @test
     * @testdox
     */
    public function caseThree()
    {
        $this->assertInstanceOf(MailMessage::class, (new UpdateUserNotification([], []))->toMail("test"));
    }
}
