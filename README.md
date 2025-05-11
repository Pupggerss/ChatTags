# ChatTags Plugin

A lightweight PocketMine plugin that allows players to use customizable chat tags with color formatting.

## Features

- Customizable tags with color formatting
- In-game commands and JSON file editing
- Full support for all TextFormat colors using `{colorcode}` syntax
- Menu interface for tag selection
- SQLite database support

## Commands

- `/tag add {name}` - Add a new tag (OP)
- `/tag remove {name}` - Remove a tag (OP)
- `/tag menu` - Open tag selection menu
- `/tag give {player} {tag|all}` - Give tag(s) to player (OP)

## Tag Formatting

Use any TextFormat color in tags:
```json
["{red}VIP", "{gold}M{bold}EMBER", "{obfuscated}!!{reset}{blue}MYSTIC"]
