<?php


namespace pup\chattags;

final class TagManager
{
    /** @var string[] */
    private static array $tags = [];

    public function __construct()
    {
        Main::getInstance()->saveResource('tags.json');
        self::$tags = self::loadTags();
    }

    public static function loadTags(): array
    {
        $path = Main::getInstance()->getDataFolder() . "tags.json";
        if (!file_exists($path)) {
            return [];
        }

        $content = file_get_contents($path);
        if ($content === false) {
            return [];
        }

        $data = json_decode($content, true);
        return is_array($data) ? $data : [];
    }

    /** @return string[] */
    public static function getTags(): array
    {
        return self::$tags;
    }

    public static function hasTag(string $tag): bool
    {
        return in_array($tag, self::$tags, true);
    }

    public static function addTag(string $tag): bool
    {
        $cleanTag = strip_tags($tag);
        if (!self::hasTag($cleanTag) && $cleanTag !== '') {
            self::$tags[] = $tag;
            self::save();
            return true;
        }
        return false;
    }

    public static function removeTag(string $tag): bool
    {
        $index = array_search($tag, self::$tags, true);
        if ($index !== false) {
            array_splice(self::$tags, $index, 1);
            return true;
        }
        return false;
    }

    public static function getTag(int $index): ?string
    {
        return self::$tags[$index] ?? null;
    }

    public static function save(): void
    {
        $data = json_encode(self::$tags);
        file_put_contents(Main::getInstance()->getDataFolder() . "tags.json", $data);
    }
}