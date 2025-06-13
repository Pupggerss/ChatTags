<?php

namespace pup\chattags\commands\subCommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use pup\chattags\Main;
use pup\chattags\TagManager;

class addCommand extends BaseSubCommand
{
    public function __construct(private readonly Main $main)
    {
        parent::__construct($this->main, "add");
        $this->setPermission("chattags.admin.add");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args)
    : void
    {
        if (!$sender instanceof Player) return;

        $session = Main::getInstance()->getSessionManager()->getSession($sender->getXuid());

        $tag = $args["tag"];
        if (TagManager::addTag($tag)) {
            $session->updateData($tag);
            $sender->sendMessage(TF::GREEN . "Tag added!");
            return;
        }
        $sender->sendMessage(TF::RED . "Tag exists/invalid!");
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