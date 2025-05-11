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

    public function loadDatabase(): void
    {
        $this->database = libasynql::create(Main::getInstance(), Main::getInstance()->getConfig()->get("database"), [
            "sqlite" => "sqlite.sql",
        ]);
        $this->database->executeGeneric('init.players');
        $this->database->waitAll();
    }

    public function addPlayer(string $xuid, array $data): void
    {
        $data = json_encode($data);
        $this->database->executeInsert('add.player', [
            'xuid' => $xuid,
            'tags' => $data
        ], null,
            fn(SqlError $err) => Main::getInstance()->getServer()->getLogger()->error($err->getMessage()));
    }


    public function getTags(string $xuid, Closure $callback): void
    {
        $this->database->executeSelect(
            'get.tags', [
            'xuid' => $xuid
        ], function (array $rows) use ($callback) {
            $data = $rows[0] ?? [];
            $callback($data);
        }
        );
    }

    public function updateTags(string $xuid, array $data): void
    {
        $data = json_encode($data);
        $this->database->executeChange('update.tags', [
            'xuid' => $xuid,
            'tags' => $data
        ], null,
            fn(SqlError $err) => Main::getInstance()->getServer()->getLogger()->error($err->getMessage()));
    }
}