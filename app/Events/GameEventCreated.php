<?php

namespace App\Events;

use App\Models\GameEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameEventCreated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public GameEvent $event;

    public function __construct(GameEvent $event)
    {
        $this->event = $event->load(['team', 'game']);
    }

    public function broadcastOn(): Channel
    {
        return new Channel('game.' . $this->event->game_id);
    }

    public function broadcastAs(): string
    {
        return 'GameEventCreated';
    }
}