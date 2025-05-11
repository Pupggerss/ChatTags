<?php


namespace pup\chattags\session;


use pocketmine\player\Player;
use pup\chattags\Main;

final class SessionManager
{
    private array $sessions = [];

    public function createSession(Player $player): void
    {
        $xuid = $player->getXuid();
        $db = Main::getInstance()->getDataManager();

        $db->getTags($xuid, function (array $rows) use ($xuid, $db) {
            $data = $rows[0] ?? [];
            $tagsData = json_decode($data['tags'] ?? '[]', true);

            $session = new Session($xuid, $tagsData);
            $this->sessions[$xuid] = $session;

            if (empty($data)) {
                $db->addPlayer($xuid, $tagsData);
            }
        });
    }

    public function closeSession(string $xuid): void
    {
        if (isset($this->sessions[$xuid])) {
            Main::getInstance()->getDataManager()->updateTags($xuid, $this->getSession($xuid)->getData());
            unset($this->sessions[$xuid]);
        }
    }

    public function getSession(string $xuid): ?Session{
        return $this->sessions[$xuid] ?? null;
    }
}