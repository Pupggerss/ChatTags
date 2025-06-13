<?php

namespace pup\chattags\commands\subCommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TF;
use pup\chattags\Main;
use pup\chattags\TagManager;
use pup\chattags\utils\PlaceholderApi;

class giveCommand extends BaseSubCommand
{
    use PlaceholderApi;

    public function __construct(private Main $main)
    {
        parent::__construct($this->main, "give");
        $this->setPermission("chattags.admin.give");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args)
    : void
    {
        $target = Server::getInstance()->getPlayerExact($args["who"]);
        if (!$target instanceof Player) {
            $sender->sendMessage(TF::RED . "Player Not Found!");
            return;
        }

        $targetSession = Main::getInstance()->getSessionManager()->getSession($target->getXuid());
        if (!$targetSession) {
            $sender->sendMessage(TF::RED . "Player Session Not Found!");
            return;
        }

        if (isset($args["tag"])) {
            $search = implode(" ", array_slice($args, 1));
            $found = false;
            foreach (TagManager::getTags() as $tag) {
                if (strtolower($this->revert($tag)) === strtolower($search)) {
                    $targetSession->updateData($tag);
                    $sender->sendMessage(TF::GREEN . "Given " . $this->getFormattedString($tag) . " to " . $target->getName());
                    $target->sendMessage(TF::GREEN . "You received: " . $this->getFormattedString($tag));
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $sender->sendMessage(TF::RED . "Tag not found!");
            }
        } else {
            foreach (TagManager::getTags() as $tag) {
                $targetSession->updateData($tag);
            }
            $sender->sendMessage(TF::GREEN . "All tags given to " . $target->getName());
            $target->sendMessage(TF::GREEN . "You received all tags!");
        }
    }

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare()
    : void
    {
        $this->registerArgument(0, new RawStringArgument("who", false));
        $this->registerArgument(1, new RawStringArgument("tag", true));
    }
}