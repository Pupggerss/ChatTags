# ChatTags Plugin

A lightweight PocketMine plugin that allows players to use customizable chat tags with color formatting.

## Features

- Customizable tags with color formatting
- In-game commands and JSON file editing
- Full support for all TextFormat colors using `{colorcode}` syntax
- Menu interface for tag selection
- SQLite database support
- MYSQL database support

## Commands

- `/tags add {name}` - Add a new tag (OP)
- `/tags remove {name}` - Remove a tag (OP)
- `/tags menu` - Open tag selection menu
- `/tags give {player} [tag]` - Give tag(s) to player (OP)


## Tag Formatting

Use any TextFormat color in tags:
```json
["{red}VIP", "{gold}M{bold}EMBER", "{obfuscated}!!{reset}{blue}MYSTIC"]
```

## Permissions
- 'chattags.command.use' - Default /tags command permission
- 'chattags.command.menu' - Permission to open chattags tags menu
- 'chattags.admin.add' - Permission to add a new chattag
- 'chattags.admin.remove' - Permission to remove a chattag
- 'chattags.admin.give' - Permission to give players chattags


## TODO:
- [ ] Add UI for tag removal
- [ ] Add UI for tag addition
- [ ] Add UI for tag edits
- [ ] Add tag items for better access
- [ ] Add RankSystem chat format support (IDK?)
