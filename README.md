We can add words in db. can change letter in word. New word would be written in table with foreing key, related 1 to many.

CREATE DATABASE IF NOT EXISTS `mysql`;
USE `mysql`;

CREATE TABLE  `first_word` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `word` VARCHAR(255) NOT NULL,
    `original_word_id` INT DEFAULT NULL,
    FOREIGN KEY (`original_word_id`) REFERENCES `first_word`(`id`)
);

Using smallest id, if we deleted something from db.
DELIMITER //

CREATE PROCEDURE GetSmallestAvailableID(OUT smallestID INT)
BEGIN
    DECLARE lastID INT DEFAULT 0;
    DECLARE candidateID INT DEFAULT 1;

    SELECT MAX(id) INTO lastID FROM first_word;

    IF lastID IS NULL THEN
        SET smallestID = 1;
    ELSE
        REPEAT
            IF NOT EXISTS (SELECT 1 FROM first_word WHERE id = candidateID) THEN
                SET smallestID = candidateID;
            ELSE
                SET candidateID = candidateID + 1;
            END IF;
        UNTIL candidateID > lastID OR smallestID IS NOT NULL END REPEAT;

        IF smallestID IS NULL THEN
            SET smallestID = lastID + 1;
        END IF;
    END IF;
END //

DELIMITER ;
