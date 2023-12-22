DROP TABLE IF EXISTS dblp.wordindex;
DROP TABLE IF EXISTS dblp.wordcount;

CREATE TABLE dblp.wordcount (
    publication_id INT PRIMARY KEY,
    wcount INT
);


CREATE TABLE dblp.wordindex (
    word VARCHAR(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    publication_id INT,
    wcount INT,
    firstLetterAscii INT(1),

    PRIMARY KEY (word, publication_id, firstLetterAscii),
    INDEX (word)
) ENGINE=InnoDB;

ALTER TABLE dblp.wordindex
    PARTITION BY RANGE (firstLetterAscii) (
        PARTITION pA VALUES LESS THAN (98),   -- a
        PARTITION pB VALUES LESS THAN (99),   -- b
        PARTITION pC VALUES LESS THAN (100),  -- c
        PARTITION pD VALUES LESS THAN (101),  -- d
        PARTITION pE VALUES LESS THAN (102),  -- e
        PARTITION pF VALUES LESS THAN (103),  -- f
        PARTITION pG VALUES LESS THAN (104),  -- g
        PARTITION pH VALUES LESS THAN (105),  -- h
        PARTITION pI VALUES LESS THAN (106),  -- i
        PARTITION pJ VALUES LESS THAN (107),  -- j
        PARTITION pK VALUES LESS THAN (108),  -- k
        PARTITION pL VALUES LESS THAN (109),  -- l
        PARTITION pM VALUES LESS THAN (110),  -- m
        PARTITION pN VALUES LESS THAN (111),  -- n
        PARTITION pO VALUES LESS THAN (112),  -- o
        PARTITION pP VALUES LESS THAN (113),  -- p
        PARTITION pQ VALUES LESS THAN (114),  -- q
        PARTITION pR VALUES LESS THAN (115),  -- r
        PARTITION pS VALUES LESS THAN (116),  -- s
        PARTITION pT VALUES LESS THAN (117),  -- t
        PARTITION pU VALUES LESS THAN (118),  -- u
        PARTITION pV VALUES LESS THAN (119),  -- v
        PARTITION pW VALUES LESS THAN (120),  -- w
        PARTITION pX VALUES LESS THAN (121),  -- x
        PARTITION pY VALUES LESS THAN (122),  -- y
        PARTITION pZ VALUES LESS THAN MAXVALUE -- z
    );

CREATE TABLE IF NOT EXISTS dblp.wordcount (
    publication_id INT PRIMARY KEY,
    wcount INT
);