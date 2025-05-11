<?php


namespace pup\chattags\session;


use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pup\chattags\Main;

class SessionListener implements Listener
{
    public function onJoin(PlayerJoinEvent $event): void{
        Main::getInstance()->getSessionManager()->createSession($event->getPlayer());
    }

    public function onLeave(PlayerQuitEvent $event): void{
        Main::getInstance()->getSessionManager()->closeSession($event->getPlayer()->getXuid());
    }
}