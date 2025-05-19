<?php


namespace pup\chattags\utils;

use pocketmine\utils\TextFormat as TF;

trait PlaceholderApi
{
    /** @var array<string, string> */
    private static array $colorMap = [
        'black' => TF::BLACK,
        'dark_blue' => TF::DARK_BLUE,
        'dark_green' => TF::DARK_GREEN,
        'dark_aqua' => TF::DARK_AQUA,
        'dark_red' => TF::DARK_RED,
        'dark_purple' => TF::DARK_PURPLE,
        'gold' => TF::GOLD,
        'gray' => TF::GRAY,
        'dark_gray' => TF::DARK_GRAY,
        'blue' => TF::BLUE,
        'green' => TF::GREEN,
        'aqua' => TF::AQUA,
        'red' => TF::RED,
        'light_purple' => TF::LIGHT_PURPLE,
        'yellow' => TF::YELLOW,
        'white' => TF::WHITE,
        'bold' => TF::BOLD,
        'italic' => TF::ITALIC,
        'obfuscated' => TF::OBFUSCATED,
        'reset' => TF::RESET
    ];

    public function getFormattedString(string $message): string
    {
        return preg_replace_callback(
            '/{([a-zA-Z_]+)}/',
            fn($matches) => $this->mapColor($matches[1]),
            $message
        );
    }

    public function revert(string $formattedMessage): string
    {
        $result = '';
        $parts = preg_split('/(?=' . TF::ESCAPE . ')/', $formattedMessage);

        $flippedMap = array_flip(self::$colorMap);

        foreach ($parts as $part) {
            if (str_starts_with($part, TF::ESCAPE)) {
                $colorCode = substr($part, 1, 1);
                if (isset($flippedMap[$colorCode])) {
                    $result .= '{' . $flippedMap[$colorCode] . '}';
                    $part = substr($part, 2);
                }
            }
            $result .= $part;
        }

        return $result;
    }

    private function mapColor(string $color): string
    {
        return self::$colorMap[strtolower($color)] ?? TF::WHITE;
    }
}