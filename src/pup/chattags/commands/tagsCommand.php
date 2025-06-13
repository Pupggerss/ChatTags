<?php

namespace pup\chattags\commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pup\chattags\commands\subCommands\addCommand;
use pup\chattags\commands\subCommands\giveCommand;
use pup\chattags\commands\subCommands\menuCommand;
use pup\chattags\commands\subCommands\removeCommand;
use pup\chattags\Main;

class tagsCommand extends BaseCommand
{

    public function __construct(private readonly Main $main)
    {
        parent::__construct($this->main, "tags");
        $this->setPermission("chattags.command.use");

        $this->setUsage("/tags <add|remove|menu|give> [player] [tag]");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args)
    : void
    {
        $sender->sendMessage($this->getUsageMessage());
    }

    protected function prepare()
    : void
    {
        $this->registerSubCommand(new addCommand($this->main));
        $this->registerSubCommand(new removeCommand($this->main));
        $this->registerSubCommand(new menuCommand($this->main));
        $this->registerSubCommand(new giveCommand($this->main));
    }
}