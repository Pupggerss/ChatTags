-- #! sqlite

-- #{ init
-- # { players
CREATE TABLE IF NOT EXISTS players
(
    xuid TEXT PRIMARY KEY,
    tags TEXT
);
-- # }

-- # { server_tags
CREATE TABLE IF NOT EXISTS server_tags
(
    tags TEXT PRIMARY KEY DEFAULT '[]'
);
-- # }

-- # }

-- #{ add
-- # { player
-- # :xuid string
-- # :tags string
INSERT OR
REPLACE
INTO players(xuid, tags)
VALUES (:xuid,
        :tags);
-- # }

-- # {server_tags
-- # :tags string
INSERT OR REPLACE INTO server_tags(rowid, tags) VALUES (
                                                           (SELECT rowid FROM server_tags LIMIT 1),
                                                           :tags
                                                       );
-- # }

-- # }

-- #{ get
-- # { player_tags
-- # :xuid string
SELECT tags
FROM players
WHERE xuid = :xuid;
-- # }

-- # { server_tags
SELECT *
FROM server_tags;
-- #}

-- #  }

-- # { update
-- # { player_tags
-- # :xuid string
-- # :tags string
UPDATE players
SET tags = :tags
WHERE xuid = :xuid;
-- # }

-- # }