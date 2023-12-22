-- Index all the words of the publications
-- CALL explorePublications(1, 1000000);

-- Count all words of a publication, to be used in the TF-IDF calculation
-- CALL dblp.initWordCount();
-- CALL dblp.countAllWords();

-- Need to be call one time before calculateTF
-- CALL dblp.initWordCount();

--Example of calculateTF-IDF
SELECT dblp.calculateTF('spectre', 1) * dblp.calculateIDF('spectre');
SELECT dblp.calculateTF('imaging', 4974917) * dblp.calculateIDF('imaging');
