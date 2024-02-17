import pandas as pd
import sys
import json
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB
from sklearn.model_selection import train_test_split
from sklearn import metrics
def predict_answer(answer):
    # Baca dataset dari file CSV
    file_path = 'data_set_interview.csv'
    dataset = pd.read_csv(file_path)
    # Contoh dataset (X: fitur, y: label)
    X = dataset[['Pertanyaan', 'Jawaban']]
    y = dataset['Label']

    # Bagi dataset menjadi set pelatihan dan set pengujian
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

    # Representasi teks menggunakan TF-IDF
    vectorizer = TfidfVectorizer()
    X_train_tfidf = vectorizer.fit_transform(X_train['Pertanyaan'] + ' ' + X_train['Jawaban'])
    X_test_tfidf = vectorizer.transform(X_test['Pertanyaan'] + ' ' + X_test['Jawaban'])

    # Inisialisasi model Naive Bayes
    clf = MultinomialNB()

    # Melatih model
    clf.fit(X_train_tfidf, y_train)

    # Prediksi
    y_pred = clf.predict(X_test_tfidf)

    # Evaluasi model
    accuracy = metrics.accuracy_score(y_test, y_pred)
    # print(f'Accuracy: {accuracy}')

    # Data test
    data_test = pd.DataFrame({
        'Pertanyaan': [
            'Ceritakan pengalaman kerja Anda sebelumnya',
            'Apa yang membuat Anda tertarik menjadi barista?',
            'Bagaimana Anda mengatasi situasi saat kafe penuh dan ada pelanggan yang tidak puas?',
            'Jelaskan bagaimana Anda membuat espresso yang sempurna?',
            'Bagaimana Anda menangani situasi konflik antara rekan kerja di kafe?'
        ],
        'Jawaban': answer
        # [
        #     'Sebelumnya saya bekerja sebagai dokter',
        #     'Karena saya adalah pecinta kopi dan ingin mengetahui lebih banyak lagi tentang kopi',
        #     'Pertama saya akan berkoordinasi dengan rekan kerja yang lain agar pekerjaan menjadi lebih efektif',
        #     'Espresso yang sempurna hanya dapat dicapai dengan menggunakan biji kopi termahal',
        #     'Saya mencoba menemukan solusi win-win untuk memuaskan semua pihak yang terlibat dalam konflik'
        # ]
    })

    # Representasi teks menggunakan TF-IDF
    data_test_tfidf = vectorizer.transform(data_test['Pertanyaan'] + ' ' + data_test['Jawaban'])

    # Prediksi
    hasil_prediksi = clf.predict(data_test_tfidf)

    # Menampilkan hasil prediksi
    data_test['Prediksi'] = hasil_prediksi
    array = data_test['Prediksi'].to_numpy().tolist()
    return json.dumps({"result": array})

answer = sys.argv[1]
result = predict_answer(answer)
print(result)
sys.stdout.flush()
    
