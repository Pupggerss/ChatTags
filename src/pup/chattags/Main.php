<?php


namespace pup\chattags;

use pocketmine\plugin\PluginBase;
use pup\chattags\commands\tagsCommand;
use pup\chattags\database\DataManager;
use pup\chattags\session\SessionListener;
use pup\chattags\session\SessionManager;

class Main extends PluginBase
{
    private static self $instance;
    private DataManager $database;
    private SessionManager $sessionManager;

    public static function getInstance()
    : self
    {
        return self::$instance;
    }

    public function onLoad()
    : void
    {
        self::$instance = $this;
    }

    public function onEnable()
    : void
    {
        $this->database = new DataManager();
        $this->database->loadDatabase();

        $this->sessionManager = new SessionManager();
        new TagManager();

        $this->getServer()->getCommandMap()->register("chattags", new tagsCommand($this));

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new SessionListener(), $this);
    }

    public function getDataManager()
    : DataManager
    {
        return $this->database;
    }

    public function getSessionManager()
    : SessionManager
    {
        return $this->sessionManager;
    }
}