# Importez les bibliothèques nécessaires
import json
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
import sys

# Récupérez la requête passée en argument depuis PHP
query = sys.argv[1]

# Liste de mots d'arrêt en anglais
stop_words_en = [
    "i", "me", "my", "myself", "we", "our", "ours", "ourselves", "you", "your", "yours", "yourself", "yourselves",
    "he", "him", "his", "himself", "she", "her", "hers", "herself", "it", "its", "itself", "they", "them", "their",
    "theirs", "themselves", "what", "which", "who", "whom", "this", "that", "these", "those", "am", "is", "are", "was",
    "were", "be", "been", "being", "have", "has", "had", "having", "do", "does", "did", "doing", "a", "an", "the",
    "and", "but", "if", "or", "because", "as", "until", "while", "of", "at", "by", "for", "with", "about", "against",
    "between", "into", "through", "during", "before", "after", "above", "below", "to", "from", "up", "down", "in",
    "out", "on", "off", "over", "under", "again", "further", "then", "once", "here", "there", "when", "where", "why",
    "how", "all", "any", "both", "each", "few", "more", "most", "other", "some", "such", "no", "nor", "not", "only",
    "own", "same", "so", "than", "too", "very", "s", "t", "can", "will", "just", "don", "should", "now", "d", "ll",
    "m", "o", "re", "ve", "y", "ain", "aren", "couldn", "didn", "doesn", "hadn", "hasn", "haven", "isn", "ma", "mightn",
    "mustn", "needn", "shan", "shouldn", "wasn", "weren", "won", "wouldn"
]

# Créez un DataFrame vide
df = pd.DataFrame({'title': [query]})

# Créez un vecteur TF-IDF
tfidf_vectorizer = TfidfVectorizer(stop_words=stop_words_en)
tfidf_matrix = tfidf_vectorizer.fit_transform(df['title'])

# Créez un DataFrame pour stocker les résultats
tfidf_df = pd.DataFrame(tfidf_matrix.toarray(), columns=tfidf_vectorizer.get_feature_names_out())

# Renvoyez les valeurs TF-IDF au format JSON
result = tfidf_df.to_json(orient='records')

# Imprimez directement le résultat JSON
print(result)
