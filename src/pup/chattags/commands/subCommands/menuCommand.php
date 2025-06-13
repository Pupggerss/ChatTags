<?php

namespace pup\chattags\commands\subCommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use CortexPE\Commando\exception\ArgumentOrderException;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use pup\chattags\Main;
use pup\chattags\session\Session;
use pup\chattags\TagManager;
use pup\chattags\utils\PlaceholderApi;

class menuCommand extends BaseSubCommand
{
    use PlaceholderApi;

    public function __construct(private Main $main)
    {
        parent::__construct($this->main, "menu");
        $this->setPermission("chattags.command.menu");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args)
    : void
    {
        if (!$sender instanceof Player) return;

        $session = Main::getInstance()->getSessionManager()->getSession($sender->getXuid());
        if (!$session) {
            $sender->sendMessage(TF::RED . "Invalid session!");
            return;
        }
        $sender->sendForm($this->createForm($session));
    }

    private function createForm(Session $session)
    : MenuForm
    {
        $options = [];
        foreach (TagManager::getTags() as $tag) {
            $status = $tag === $session->getActiveTag()
                ? TF::GOLD . "Active"
                : (in_array($tag, $session->getData()["tags"], true) ? TF::GREEN . "Unlocked" : TF::RED . "Locked");
            $options[] = new MenuOption($this->getFormattedString($tag) . "\n(" . TF::RESET . $status . TF::RESET . ")");
        }

        return new MenuForm(
            "ChatTags",
            "Select a tag",
            $options,
            function (Player $player, int $selected) use ($session)
            : void {
                $tags = TagManager::getTags();
                if (!isset($tags[$selected])) return;

                $tag = $tags[$selected];
                if (!in_array($tag, $session->getData()["tags"], true)) {
                    $player->sendMessage(TF::RED . "You don't have this tag!");
                    return;
                }

                if ($tag === $session->getActiveTag()) {
                    $session->setActiveTag("");
                    $player->sendMessage(TF::GREEN . "Tag deselected!");
                } else {
                    $session->setActiveTag($tag);
                    $player->sendMessage(TF::GREEN . "Selected: " . $this->getFormattedString($tag));
                }
                $player->sendForm($this->createForm($session));
            }
        );
    }

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare()
    : void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }
}