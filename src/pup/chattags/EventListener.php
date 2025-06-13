<?php


namespace pup\chattags;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pup\chattags\utils\ChatFormatter;

class EventListener implements Listener
{
    public function onPlayerChat(PlayerChatEvent $event)
    : void
    {
        $formatter = new ChatFormatter();
        $event->setFormatter($formatter);
    }
}