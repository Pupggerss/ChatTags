<?php


namespace pup\chattags;

final class TagManager
{
    /** @var string[] */
    private static array $tags = [];

    public function __construct()
    {
        self::loadTags();
    }

    public static function loadTags()
    : void
    {
        Main::getInstance()->getDataManager()->getServerTags(function ($tagsJson) {
            if (is_string($tagsJson)) {
                $decodedData = json_decode($tagsJson, true);
            } else {
                $decodedData = $tagsJson;
            }
            self::$tags = $decodedData['tags'] ?? [
                "{bold}{gold}C{yellow}H{gold}I{yellow}L{gold}L{reset}",
                "{aqua}W{dark_aqua}A{blue}V{dark_blue}Y{reset}",
                "{red}F{dark_red}I{red}R{dark_red}E{reset}",
                "{green}G{dark_green}R{green}A{dark_green}S{green}S{reset}",
                "{light_purple}D{dark_purple}R{light_purple}E{dark_purple}A{light_purple}M{reset}",
                "{bold}{gray}R{dark_gray}O{gray}C{dark_gray}K{reset}",
                "{bold}{gold}BLING{reset}",
                "{obfuscated}{aqua}GLITCH{reset}",
                "{italic}{dark_blue}DEEP DIVER{reset}",
                "{red}B{light_purple}L{blue}A{aqua}S{green}T{yellow}O{gold}F{orange}F{reset}",
                "{green}VINE{reset}",
                "{yellow}SUNNY{reset}",
                "{dark_purple}VOID{reset}",
                "{white}CLOUD{reset}",
                "{blue}OCEANIC{reset}",
                "{bold}{red}EXPLOSIVE{reset}",
                "{italic}{aqua}SHADOW{reset}",
                "{obfuscated}{gold}MYSTERY{reset}",
                "{dark_gray}NIGHT{reset}",
                "{light_purple}STARGAZER{reset}",
                "{gold}F{yellow}L{gold}A{yellow}S{gold}H{reset}",
                "{green}G{light_green}R{aqua}O{blue}W{dark_blue}T{reset}",
                "{red}H{dark_red}O{red}T{reset}",
                "{dark_blue}S{blue}K{aqua}Y{reset}",
                "{bold}{gold}C{yellow}R{gold}O{yellow}W{gold}N{reset}",
                "{italic}{dark_green}FORAGER{reset}",
                "{obfuscated}{red}CHAOS{reset}",
                "{light_purple}P{dark_purple}O{light_purple}W{dark_purple}E{light_purple}R{reset}",
                "{yellow}LUCKY{reset}",
                "{aqua}B{light_blue}L{white}I{light_blue}S{aqua}S{reset}"
            ];
            self::save();
        });
    }

    public static function save()
    : void
    {
        //Safe?
        $data = json_encode(self::$tags);
        Main::getInstance()->getDataManager()->addServerTags($data);
    }

    /** @return string[] */
    public static function getTags()
    : array
    {
        return self::$tags;
    }

    public static function addTag(string $tag)
    : bool
    {
        $cleanTag = strip_tags($tag);
        if (!self::hasTag($cleanTag) && $cleanTag !== '') {
            self::$tags[] = $tag;
            self::save();
            return true;
        }
        return false;
    }

    public static function hasTag(string $tag)
    : bool
    {
        return in_array($tag, self::$tags, true);
    }

    public static function removeTag(string $tag)
    : bool
    {
        $index = array_search($tag, self::$tags, true);
        if ($index !== false) {
            array_splice(self::$tags, $index, 1);
            return true;
        }
        return false;
    }

    public static function getTag(int $index)
    : ?string
    {
        return self::$tags[$index] ?? null;
    }
}