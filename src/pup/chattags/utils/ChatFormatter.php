<?php


namespace pup\chattags\utils;


use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pup\chattags\Main;
use pup\chattags\session\Session;

class ChatFormatter implements \pocketmine\player\chat\ChatFormatter
{
    use PlaceholderApi;

    public function format(string $username, string $message)
    : Translatable|string
    {
        $player = Main::getInstance()->getServer()->getPlayerExact($username);
        if (!$player instanceof Player) {
            return $message;
        }

        $session = Main::getInstance()->getSessionManager()->getSession($player->getXuid());
        if (!$session instanceof Session) {
            return $message;
        }

        $playerName = $player->getDisplayName();

        if ($session->getActiveTag() !== '') {
            $formattedTag = $this->getFormattedString($session->getActiveTag());
            return sprintf("%s %s§r: %s",
                $formattedTag,
                $playerName,
                $message
            );
        }

        return sprintf("%s§r: %s", $playerName, $message);
    }
}