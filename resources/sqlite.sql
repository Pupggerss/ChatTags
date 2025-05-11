-- #! sqlite

-- #{ init
-- # { players
CREATE TABLE IF NOT EXISTS players
(
    xuid     TEXT PRIMARY KEY,
    tags     TEXT DEFAULT NULL
);
-- # }
-- # }

-- #{ add
-- # { player
-- # :xuid string
-- # :tags string
INSERT OR IGNORE INTO players(xuid, tags)
VALUES (:xuid,
        :tags);
-- # }
-- # }

-- #{ get
-- # { tags
-- # :xuid string
SELECT tags
FROM players
WHERE xuid = :xuid;
-- # }
-- #  }

-- # { update
-- # { tags
-- # :xuid string
-- # :tags string
UPDATE players SET tags = :tags
WHERE xuid = :xuid;
-- # }
-- # }