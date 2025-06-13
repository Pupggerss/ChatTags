<?php

namespace pup\chattags\commands\subCommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as TF;
use pup\chattags\Main;
use pup\chattags\TagManager;
use pup\chattags\utils\PlaceholderApi;

class removeCommand extends BaseSubCommand
{
    use PlaceholderApi;

    public function __construct(private Main $main)
    {
        parent::__construct($this->main, "remove");
        $this->setPermission("chattags.admin.remove");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args)
    : void
    {
        $search = implode(" ", array_slice($args, 1));
        foreach (TagManager::getTags() as $tag) {
            if (strtolower($this->revert($tag)) === strtolower($search)) {
                TagManager::removeTag($tag)
                    ? $sender->sendMessage(TF::GREEN . "Tag removed!")
                    : $sender->sendMessage(TF::RED . "Failed to remove!");
            }
        }
        $sender->sendMessage(TF::RED . "Tag not found!");
    }

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare()
    : void
    {
        $this->registerArgument(0, new RawStringArgument("tag"));
    }
}