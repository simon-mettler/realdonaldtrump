import sys, json, nltk

# nltk.download('punkt')
# nltk.download('stopwords')

from nltk import FreqDist
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize

# Load the data that PHP sent
try:
    source_raw = sys.argv[1]
except:
    print ("ERROR")
    sys.exit(1)

# Tokenize data
word_tokens = word_tokenize(source_raw)
word_tokens_low = [word.lower() for word in word_tokens]

# Filter stopwords
words_filtered = []
stopwords = set(stopwords.words('english') + ['http', 'https', 'tinyurl', 'com', 'www'])
for word in word_tokens_low:
    if word.isalpha():
        if word not in stopwords:
            words_filtered.append(word)


wordcount = len(source_raw)
wordcount_dist = len(set(word_tokens_low))
lex_richness = wordcount_dist/wordcount

wordFreq = FreqDist(words_filtered).most_common(10)
topTen = ""
for word, freq in wordFreq:
	topTen += "<span class='num'>" + word + " <i>" + str(freq) +"</i></span>"
topTen = topTen[:-2] + "."

# Combine results for output
output = "<p><b>Wörter total:</b> " + str(wordcount) +  "</p><p><b>Unterschiedliche Wörter:</b> " + str(wordcount_dist) + "</p><b>Lexical richness:</b> " + str(round(lex_richness, 4)) + "</p><p><b>Zehn häufigsten Wörter:</b><br>" + topTen + "</p>"

print(output)

