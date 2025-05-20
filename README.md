# ChatTags Plugin

A lightweight PocketMine plugin that allows players to use customizable chat tags with color formatting.

## Features

- Customizable tags with color formatting
- In-game commands and JSON file editing
- Full support for all TextFormat colors using `{colorcode}` syntax
- Menu interface for tag selection
- SQLite database support

## Commands
(Admin commands, require permission "chattags.command.admin")
- `/tag add {name}` - Add a new tag (OP)
- `/tag remove {name}` - Remove a tag (OP)
- `/tag give {player} {tag|all}` - Give tag(s) to player (OP)

(Player command, require permission "chattags.command.use")
- - `/mytags` - Open tag selection menu


## Tag Formatting

Use any TextFormat color in tags:
```json
["{red}VIP", "{gold}M{bold}EMBER", "{obfuscated}!!{reset}{blue}MYSTIC"]
