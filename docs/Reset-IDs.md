# Reset database ID

SET @count = 0;

UPDATE documents
SET id = (@count := @count + 1)
ORDER BY id;
