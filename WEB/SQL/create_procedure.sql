
DROP PROCEDURE IF EXISTS dblp.explorePublications;
DROP PROCEDURE IF EXISTS dblp.explode;
DROP PROCEDURE IF EXISTS dblp.updateCountWord;
DROP PROCEDURE IF EXISTS dblp.initWordCount;
DROP PROCEDURE IF EXISTS dblp.countAllWords;
DROP PROCEDURE IF EXISTS dblp.increaseWordCount;
DROP TRIGGER IF EXISTS dblp.publications_after_insert;
DROP TRIGGER IF EXISTS dblp.publications_after_update;
DROP TRIGGER IF EXISTS dblp.publications_after_delete;

DROP FUNCTION IF EXISTS dblp.calculateTF;
DROP FUNCTION IF EXISTS dblp.calculateIDF;


DELIMITER //


CREATE PROCEDURE dblp.explode(
    originalString VARCHAR(255),
    delimiter VARCHAR(1),
    publication_id INT
)
BEGIN
    DECLARE curPosition INT DEFAULT 1;
    DECLARE prevPosition INT DEFAULT 0;
    DECLARE elementValue VARCHAR(255);
    DECLARE editedString VARCHAR(255);  -- Added data type for variable
    SET NAMES 'utf8mb4';

    -- Copy the original string to editedString
    SET editedString = LOWER(originalString);

    -- Remove unwanted characters
    SET editedString = REPLACE(editedString, ';', '');
    SET editedString = REPLACE(editedString, '.', '');
    SET editedString = REPLACE(editedString, ',', '');
    SET editedString = REPLACE(editedString, ':', '');
    SET editedString = REPLACE(editedString, '?', '');
    SET editedString = REPLACE(editedString, '*', '');
    SET editedString = REPLACE(editedString, '(', '');
    SET editedString = REPLACE(editedString, ')', '');
    SET editedString = REPLACE(editedString, '"', '');
    SET editedString = REPLACE(editedString, '+', '');
    SET editedString = REPLACE(editedString, '=', '');
    SET editedString = REPLACE(editedString, '[', '');
    SET editedString = REPLACE(editedString, ']', '');
    SET editedString = REPLACE(editedString, '!', '');
    SET editedString = REPLACE(editedString, '<', '');
    SET editedString = REPLACE(editedString, '>', '');
    SET editedString = REPLACE(editedString, '/', '');
    SET editedString = REPLACE(editedString, '\\', '');
    SET editedString = REPLACE(editedString, "{", '');
    SET editedString = REPLACE(editedString, "}", '');
    SET editedString = REPLACE(editedString, '|', '');
    SET editedString = REPLACE(editedString, '#', '');
    SET editedString = REPLACE(editedString, "$", '');
    SET editedString = REPLACE(editedString, '%', '');
    SET editedString = REPLACE(editedString, '¿', '');
    SET editedString = REPLACE(editedString, '¡', '');
    SET editedString = REPLACE(editedString, '»', '');
    SET editedString = REPLACE(editedString, '~', '');
    SET editedString = REPLACE(editedString, '&', '');
    SET editedString = REPLACE(editedString, '°', '');
    SET editedString = REPLACE(editedString, '-', ' ');
    SET editedString = REPLACE(editedString, '@', ' ');
    SET editedString = REPLACE(editedString, '_', ' ');
    SET editedString = REPLACE(editedString, "'", ' ');
    SET editedString = REPLACE(editedString, "^", ' ');
    
    -- Loop to traverse the string
    WHILE curPosition > 0 DO
        -- Find the position of the next delimiter
        SET curPosition = LOCATE(delimiter, editedString, prevPosition + 1);

        -- Get the current element from the string
        IF curPosition = 0 THEN
            SET elementValue = SUBSTRING(editedString, prevPosition + 1);
        ELSE
            SET elementValue = SUBSTRING(editedString, prevPosition + 1, curPosition - prevPosition - 1);
        END IF;

        SET elementValue = TRIM(elementValue);
        SET elementValue = REPLACE(elementValue, '\t', '');

        IF elementValue != '' OR elementValue != ' '  THEN
            -- On prend en compte que les characters qui font plus de deux lettres
            IF CHAR_LENGTH(elementValue) > 2 THEN
                -- Insert the element into the temporary table
                INSERT INTO wordindex (word, publication_id, wcount, firstLetterAscii) VALUES (elementValue, publication_id, 1, ASCII(LEFT(elementValue, 1))) ON DUPLICATE KEY UPDATE wcount = wcount + 1;
                CALL dblp.increaseWordCount(publication_id, 1);
            END IF;
        END IF;
        -- Set the previous position for the next loop iteration
        SET prevPosition = curPosition;
    END WHILE;

END //
-- Index all the words of the publications
CREATE PROCEDURE dblp.explorePublications(
    startPub INT,
    endPub INT
)
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE publicationTitle VARCHAR(255);
    DECLARE pubicationId INT;
    DECLARE cur CURSOR FOR SELECT  title FROM publications WHERE id >= startPub AND id <= endPub;
    DECLARE curIndex CURSOR FOR SELECT id FROM publications WHERE id >= startPub AND id <= endPub;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
    SET NAMES 'utf8mb4';


    OPEN cur;
    OPEN curIndex;
    
    START TRANSACTION;

    read_loop: LOOP
        FETCH cur INTO publicationTitle;
        FETCH curIndex INTO pubicationId;

        IF done THEN
            LEAVE read_loop;
        END IF;
        
        CALL dblp.explode(publicationTitle, ' ', pubicationId);
        
    END LOOP;

    COMMIT;
    CLOSE cur;
    CLOSE curIndex;

END //

CREATE PROCEDURE dblp.initWordCount(
)
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE pubId INT;
    DECLARE cur CURSOR FOR SELECT id FROM publications;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;
    
    START TRANSACTION;
    read_loop: LOOP
        FETCH cur INTO pubId;

        IF done THEN
            LEAVE read_loop;
        END IF;

        INSERT INTO wordcount (publication_id, wcount) VALUES (pubId, 0);
        
    END LOOP;

    COMMIT;

    CLOSE cur;

END //


-- Count all words of a publication, to be used in the TF-IDF calculation

CREATE PROCEDURE dblp.countAllWords(
)
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE pubId INT;
    DECLARE countWord INT;
    DECLARE cur CURSOR FOR SELECT publication_id, SUM(wcount) as wordCount FROM wordindex GROUP BY publication_id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;
    
    START TRANSACTION;
    read_loop: LOOP
        FETCH cur INTO pubId, countWord;

        IF done THEN
            LEAVE read_loop;
        END IF;

        -- UPDATE wordcount SET wcount = wcount + countWord WHERE publication_id = pubId;
        CALL dblp.increaseWordCount(pubId, countWord);
        -- CALL dblp.updateCountWord(pubId);
        
    END LOOP;

    COMMIT;

    CLOSE cur;

END //

CREATE PROCEDURE dblp.increaseWordCount(
    pubId INT,
    countValue INT
)
BEGIN
    UPDATE wordcount SET wcount = wcount + countValue WHERE publication_id = pubId;
END //

CREATE FUNCTION dblp.calculateTF(
    word VARCHAR(255),
    pubId INT
)
RETURNS FLOAT
DETERMINISTIC
BEGIN
    DECLARE total INT;
    DECLARE wcount INT;
    DECLARE wordLower VARCHAR(255);
    
    SET wordLower = LOWER(word);
    
    SELECT COUNT(*), wd.wcount INTO total, wcount
    FROM wordindex AS wi
    JOIN wordcount AS wd ON wi.publication_id = wd.publication_id
    WHERE wi.firstLetterAscii = ASCII(LEFT(wordLower, 1))
        AND wi.word LIKE CONCAT(wordLower, '%')
        AND wi.publication_id = pubId;
        
    IF wcount = 0 THEN
        RETURN 0;
    ELSE
        RETURN total / wcount;
    END IF;
END //

DELIMITER //

CREATE FUNCTION dblp.calculateIDF(
    word VARCHAR(255)
)
RETURNS FLOAT
DETERMINISTIC
BEGIN
    DECLARE idf FLOAT;
    DECLARE totalPubs INT;
    DECLARE wordPubs INT;
    DECLARE wordLower VARCHAR(255);
    
    SET wordLower = LOWER(word);
    
    SELECT COUNT(*) INTO totalPubs FROM publications;
    
    SELECT COUNT(DISTINCT wi.publication_id) INTO wordPubs
    FROM wordindex as wi
    WHERE wi.firstLetterAscii = ASCII(LEFT(wordLower, 1))
        AND wi.word LIKE CONCAT(wordLower, '%');
        
    IF wordPubs = 0 THEN
        SET idf = 0;
    ELSE
        SET idf = LOG(totalPubs / wordPubs);
    END IF;
    
    RETURN idf;
END //

DELIMITER ;


DELIMITER //

CREATE TRIGGER dblp.publications_after_insert
AFTER INSERT ON publications
FOR EACH ROW
BEGIN
    INSERT INTO wordcount (publication_id, wcount) VALUES (NEW.id, 0);
    CALL dblp.explode(NEW.title, ' ', NEW.id);
END //

CREATE TRIGGER dblp.publications_after_update
AFTER UPDATE ON publications
FOR EACH ROW
BEGIN
    DELETE FROM wordindex WHERE publication_id = NEW.id;
    
    UPDATE wordcount SET wcount = 0 WHERE publication_id = NEW.id;
    CALL dblp.explode(NEW.title, ' ', NEW.id);
    -- Incluez ici le code que vous souhaitez exécuter
END //

CREATE TRIGGER dblp.publications_after_delete
AFTER DELETE ON publications
FOR EACH ROW
BEGIN
    DELETE FROM wordindex WHERE publication_id = OLD.id;
    DELETE FROM wordcount WHERE publication_id = OLD.id;
    -- Incluez ici le code que vous souhaitez exécuter
END //
