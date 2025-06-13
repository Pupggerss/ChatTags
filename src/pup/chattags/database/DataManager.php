<?php

namespace pup\chattags\database;

use Closure;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use poggit\libasynql\SqlError;
use pup\chattags\Main;

class DataManager
{
    private DataConnector $database;

    public function loadDatabase()
    : void
    {
        $this->database = libasynql::create(Main::getInstance(), Main::getInstance()->getConfig()->get("database"), [
            "sqlite" => "sqlite.sql",
            "mysql"  => "mysql.sql"
        ]);
        $this->database->executeGeneric('init.players');
        $this->database->executeGeneric('init.server_tags');
        $this->database->waitAll();
    }

    public function addPlayer(string $xuid, array $data)
    : void
    {
        $data = json_encode($data);
        $this->database->executeInsert('add.player', [
            'xuid' => $xuid,
            'tags' => $data
        ], null,
            fn(SqlError $err) => Main::getInstance()->getServer()->getLogger()->error($err->getMessage()));
    }

    public function getPlayerTags(string $xuid, Closure $callback)
    : void
    {
        $this->database->executeSelect('get.player_tags', ['xuid' => $xuid], function (array $rows) use ($callback) {
            $callback($rows[0]["tags"] ?? []);
        });
    }

    //addPlayerTags can be used instead
    public function updatePlayerTags(string $xuid, array $data)
    : void
    {
        $this->database->executeChange('update.player_tags', [
            'xuid' => $xuid,
            'tags' => json_encode($data)
        ], null, fn(SqlError $err) => Main::getInstance()->getLogger()->error($err->getMessage()));
    }

    public function getServerTags(Closure $closure)
    : void
    {
        $this->database->executeSelect("get.server_tags", [], function (array $rows) use ($closure) {
            $closure($rows[0]["tags"] ?? []);
        });
    }

    public function addServerTags(string $tags)
    : void
    {
        $this->database->executeInsert("add.server_tags", [
            "tags" => $tags
        ], null, fn(SqlError $err) => Main::getInstance()->getLogger()->error($err->getMessage()));
    }
}