-- #! mysql
-- #{ init
-- # { players
CREATE TABLE IF NOT EXISTS players
(
    xuid
    VARCHAR
(
    36
) PRIMARY KEY,
    tags TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;
-- # }

-- # { server_tags
CREATE TABLE IF NOT EXISTS server_tags
(
    tags
    TEXT,
    PRIMARY
    KEY (
    tags
(
    255
))
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE =utf8mb4_unicode_ci;

INSERT
IGNORE INTO server_tags (tags) VALUES ('[]');
-- # }

-- # }

-- #{ add
-- # { player
-- # :xuid string
-- # :tags string
INSERT INTO players (xuid, tags)
VALUES (:xuid, :tags) ON DUPLICATE KEY
UPDATE tags =
VALUES (tags);
-- # }

-- # { server_tags
-- # :tags string
START TRANSACTION;
DELETE
FROM server_tags;
INSERT INTO server_tags (tags)
VALUES (:tags);
COMMIT;
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
SELECT tags
FROM server_tags LIMIT 1;
-- # }

-- # }

-- #{ update
-- # { player_tags
-- # :xuid string
-- # :tags string
UPDATE players
SET tags = :tags
WHERE xuid = :xuid;
-- # }

-- # }