<?php

namespace pup\chattags\commands;

use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwnedTrait;
use pocketmine\utils\TextFormat as TF;
use pup\chattags\Main;
use pup\chattags\session\Session;
use pup\chattags\TagManager;
use pup\chattags\utils\PlaceholderApi;

class tagsMenuCommand extends Command
{
    use PlaceholderApi;
    use PluginOwnedTrait;

    public function __construct(){
        parent::__construct("chattags", "View ChatTags", "/chattags", ["mytags"]);
        $this->owningPlugin = Main::getInstance();
        $this->setPermission("chattags.command.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if(!$sender instanceof Player){
            $sender->sendMessage(TF::RED . "Command can only be ran in game!");
            return false;
        }

        if(!$this->testPermission($sender)){
            return false;
        }

        $session = Main::getInstance()->getSessionManager()->getSession($sender->getXuid());
        if (!$session instanceof Session) return $this->msg($sender, TF::RED."Invalid session!");

        $form = $this->createForm($session);
        $sender->sendForm($form);
        return true;
    }

    private function createForm(Session $session): MenuForm {
        $options = [];
        foreach (TagManager::getTags() as $tag) {
            $status = $tag === $session->getActiveTag()
                ? TF::GOLD."Active"
                : (in_array($tag, $session->getData()["tags"], true) ? TF::GREEN."Unlocked" : TF::RED."Locked");
            $options[] = new MenuOption($this->getFormattedString($tag)."\n(".TF::RESET.$status.TF::RESET.")");
        }

        return new MenuForm(
            "ChatTags",
            "Select a tag",
            $options,
            function(Player $player, int $selected) use ($session): void {
                $tags = TagManager::getTags();
                if (!isset($tags[$selected])) return;

                $tag = $tags[$selected];
                if (!in_array($tag, $session->getData()["tags"], true)) {
                    $this->msg($player, TF::RED."You don't have this tag!");
                    return;
                }

                if ($tag === $session->getActiveTag()) {
                    $session->setActiveTag("");
                    $this->msg($player, TF::GREEN."Tag deselected!");
                } else {
                    $session->setActiveTag($tag);
                    $this->msg($player, TF::GREEN."Selected: ".$this->getFormattedString($tag));
                }
                $player->sendForm($this->createForm($session));
            }
        );
    }

    private function msg(CommandSender $sender, string $message): bool {
        $sender->sendMessage($message);
        return true;
    }
}