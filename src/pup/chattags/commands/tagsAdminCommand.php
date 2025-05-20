<?php
namespace pup\chattags\commands;

use dktapps\pmforms\{MenuForm, MenuOption};
use pocketmine\command\{Command, CommandSender};
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TF;
use pup\chattags\{Main, TagManager};
use pup\chattags\session\Session;
use pup\chattags\utils\PlaceholderApi;

class tagsAdminCommand extends Command implements PluginOwned {
    use PlaceholderApi;
    use PluginOwnedTrait;

    public function __construct(Main $plugin) {
        parent::__construct("tags", "Manage ChatTags (Admin)", "/tags <add|remove|give> [args]", ["managetags"]);
        $this->setPermission("chattags.command.admin");
        $this->owningPlugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) return $this->msg($sender, TF::RED."Command in-game only!");
        if (!$this->testPermission($sender)) return false;
        if (empty($args)) return $this->msg($sender, TF::RED."Usage: /tags <add|remove|give> [args]");

        $session = Main::getInstance()->getSessionManager()->getSession($sender->getXuid());
        if (!$session instanceof Session) return $this->msg($sender, TF::RED."Invalid session!");

        switch (strtolower($args[0])) {
            case "add":
                if (count($args) < 2) return $this->msg($sender, TF::RED."Usage: /tags add <name>");
                $tag = implode(" ", array_slice($args, 1));
                if (TagManager::addTag($tag)) {
                    $session->updateData($tag);
                    return $this->msg($sender, TF::GREEN."Tag added!");
                }
                return $this->msg($sender, TF::RED."Tag exists/invalid!");

            case "remove":
                if (count($args) < 2) return $this->msg($sender, TF::RED."Usage: /tags remove <name>");
                $search = implode(" ", array_slice($args, 1));
                foreach (TagManager::getTags() as $tag) {
                    if (strtolower($this->revert($tag)) === strtolower($search)) {
                        TagManager::removeTag($tag)
                            ? $this->msg($sender, TF::GREEN."Tag removed!")
                            : $this->msg($sender, TF::RED."Failed to remove!");
                        return true;
                    }
                }
                return $this->msg($sender, TF::RED."Tag not found!");

            case "give":
                if (count($args) < 3) return $this->msg($sender, TF::RED."Usage: /tags give <player> <tag|all>");
                $target = Server::getInstance()->getPlayerExact($args[1]);
                if (!$target) return $this->msg($sender, TF::RED."Player not found!");

                $targetSession = Main::getInstance()->getSessionManager()->getSession($target->getXuid());
                if (!$targetSession) return $this->msg($sender, TF::RED."Invalid target session!");

                if (strtolower($args[2]) === "all") {
                    foreach (TagManager::getTags() as $tag) {
                        $targetSession->updateData($tag);
                    }
                    $this->msg($sender, TF::GREEN."All tags given to ".$target->getName());
                    $this->msg($target, TF::GREEN."You received all tags!");
                    return true;
                }

                $search = implode(" ", array_slice($args, 2));
                foreach (TagManager::getTags() as $tag) {
                    if (strtolower($this->revert($tag)) === strtolower($search)) {
                        $targetSession->updateData($tag);
                        $this->msg($sender, TF::GREEN."Given ".$this->getFormattedString($tag)." to ".$target->getName());
                        $this->msg($target, TF::GREEN."You received: ".$this->getFormattedString($tag));
                        return true;
                    }
                }
                return $this->msg($sender, TF::RED."Tag not found!");

            default:
                return $this->msg($sender, TF::RED."Unknown subcommand!");
        }
    }

    private function msg(CommandSender $sender, string $message): bool {
        $sender->sendMessage($message);
        return true;
    }
}