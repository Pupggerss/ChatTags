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
        Main::getInstance()->getDataManager()->getTags($xuid, function($tagsJson) use ($xuid) {
            if(is_string($tagsJson)) {
                $decodedData = json_decode($tagsJson, true);
            } else{
                $decodedData = $tagsJson;
            }

            $this->sessions[$xuid] = new Session($xuid, $decodedData['tags'] ?? []);
            $this->sessions[$xuid]->setActiveTag($decodedData['activeTag'] ?? '');

            if (empty($decodedData['tags']) && empty($decodedData['activeTag'])) {
                Main::getInstance()->getDataManager()->addPlayer($xuid, [
                    'tags' => [],
                    'activeTag' => ''
                ]);
            }
        });
    }

    public function closeSession(string $xuid): void
    {
        if (isset($this->sessions[$xuid])) {
            $session = $this->sessions[$xuid];
            Main::getInstance()->getDataManager()->addPlayer($xuid, [
                'tags' => $session->getData()['tags'],
                'activeTag' => $session->getActiveTag()
            ]);
            unset($this->sessions[$xuid]);
        }
    }

    public function getSession(string $xuid): ?Session
    {
        return $this->sessions[$xuid] ?? null;
    }
}