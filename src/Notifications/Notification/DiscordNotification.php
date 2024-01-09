<?php

namespace App\Notifications\Notification;

use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\ChatNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class DiscordNotification extends Notification implements ChatNotificationInterface
{

    public function asChatMessage(RecipientInterface $recipient, string $transport = null): ?ChatMessage
    {
        return ChatMessage::fromNotification($this);
    }
}