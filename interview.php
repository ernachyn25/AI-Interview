        <!-- Masthead-->
        <header class="masthead">
            <div class="container position-relative">
                <div class="row justify-content-center">
                    <div class="col-xl-6">

                        <div class="text-center text-white" id="form-data-diri">
                                <div class="row">
                                    <div class="col" style="margin-bottom:15px">
                                        <h1 class="mb-5" >Nama</h1>
                                        <input type="text"  class="form-control form-control-lg" name="nama" id="nama" placeholder="Masukan Nama">
                                    </div>
                                    <div class="col" style="margin-bottom:15px">
                                        <h1 class="mb-5" >Kode</h1>
                                        <input type="text"  class="form-control form-control-lg" name="kode" id="kode" placeholder="Masukan Kode">
                                    </div>
                                </div>
                                <div class="col-auto"><h1 id='hasil'></h1></div>
                                <button class="btn btn-primary btn-lg" type="button" id="debugmodeButton"onclick="debugMode()" hidden>Debug Mode</button>
                                <div class="col-auto"><button class="btn btn-primary btn-lg" id="mulaiInterviewButton" type="button" onclick="submitDataDiri()">Mulai Interview</button></div>
                        </div>

                        <div class="container mt-5" style="color:white" id='table-debug' hidden>
                        <h2>Debug Hasil Wawancara</h2>
                            <table class="table" style="color:white">
                                <thead>
                                <tr>
                                    <th scope="col">Pertanyaan</th>
                                    <th scope="col">Jawaban</th>
                                    <th scope="col">Hasil</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody" style="color:white">
                                <!-- Table rows will be added dynamically here -->
                                </tbody>
                            </table>
                        </div>


                        <div class="text-center text-white" id="form-interview" hidden >
                                <h1 class="mb-5" id='question'>Pertanyaan 1</h1>
                                <div class="row">
                                    <div class="col" style="margin-bottom:15px">
                                        <textarea class="form-control form-control-lg result" name="answer" id="answer" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                                <button class="btn btn-primary btn-lg record" style="margin-bottom:10px"><p>Aktifkan Mikrofon</p></button>
                                <div class="col-auto"><button class="btn btn-primary btn-lg" id="submitButton" type="button" onclick="ChangeQuestion()">Lanjut</button></div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Footer-->
        <footer class="footer bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 h-100 text-center text-lg-start my-auto">
                        <p class="text-muted small mb-4 mb-lg-0">&copy; SakuTambah 2024. All Rights Reserved.</p>
                    </div>
                    <div class="col-lg-6 h-100 text-center text-lg-end my-auto">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item me-4">
                                <a href="https://play.google.com/store/apps/details?id=com.app.sakutambah&hl=en&gl=US"><i class="bi-google fs-3"></i></a>
                            </li>
                            <li class="list-inline-item me-4">
                                <a href="https://www.linkedin.com/company/saku-tambah/?trk=public_profile_experience-item_profile-section-card_image-click&originalSubdomain=id"><i class="bi-linkedin fs-3"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="https://www.instagram.com/sakutambah/"><i class="bi-instagram fs-3"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    </body>
</html>

<script>
    var index=0;
    var list_pertanyaan = [
        'Ceritakan pengalaman kerja Anda sebelumnya ?',
        'Apa yang membuat Anda tertarik menjadi barista?',
        'Bagaimana Anda mengatasi situasi saat kafe penuh dan ada pelanggan yang tidak puas?',
        'Jelaskan bagaimana Anda membuat espresso yang sempurna ?',
        'Bagaimana Anda menangani situasi konflik antara rekan kerja di kafe?'
    ];
    var answer = [];
    var nama="";
    var kode="";
    var hasil=[];
    function ChangeQuestion(){
        if(index !=0 && index <=5 )
        {
            answer[index-1] =document.getElementById('answer').value ;

            var regex = /[^a-zA-Z0-9.,\s]/;
            console.log((document.getElementById('answer').value))
            console.log(regex.test(document.getElementById('answer').value))

            if(regex.test(document.getElementById('answer').value))
            {
                alert('Gunakan kata yang benar');
                index--;
            }
            else
            {
               
            }
            
        }

        if(index<5)
        {
            var newQuestion = document.getElementById('question').innerHTML = list_pertanyaan[index];
            var newAnswer = document.getElementById('answer').value = '';
            index++;
        }
        else
        {
            $.ajax({
                type: 'POST',
                url: 'AIScript.php', // Gantilah dengan URL skrip server Anda
                data: { answer: answer },
                success: function(response) {
                    // Mengubah isi textarea dengan respons dari server
                    var data = JSON.parse(response,true);
                    var results = data.result;
                    hasil = results;
                    //hitung berapa benar atau salah
                    var countSalah = 0;
                    var countBenar = 0;
                    results.forEach(function(result) {
                        if (result === 'Salah') {
                            countSalah++;
                        } else if (result === 'Benar') {
                            countBenar++;
                        }
                    });
                    console.log(results);
                    document.getElementById('form-data-diri').hidden = false;
                    document.getElementById('form-interview').hidden = true;
                    document.getElementById('nama').setAttribute('readonly', true);
                    document.getElementById('kode').setAttribute('readonly', true);
                    document.getElementById('mulaiInterviewButton').hidden = true;
                    document.getElementById('debugmodeButton').hidden = false;
                    if(countSalah > 3)
                    {
                        $('#hasil').html('Hasil adalah : Tidak Lulus');
                    }
                    else
                    {
                        $('#hasil').html('Hasil adalah : Lulus');
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

    }

    function submitDataDiri(){
        nama = document.getElementById('nama').value;
        kode = document.getElementById('kode').value;
        document.getElementById('form-data-diri').hidden = true;
        document.getElementById('form-interview').hidden = false;
    }
    function debugMode(){
        document.getElementById('table-debug').hidden = false;
        var tableBody = document.getElementById('tableBody');
        // Loop through the data and create a row for each entry
        for(var i = 0; i < index; i++){
        var row = document.createElement('tr');
        // Add cells with data
        var pertanyaanCell = document.createElement('td');
        pertanyaanCell.textContent = list_pertanyaan[i];
        row.appendChild(pertanyaanCell);
        var jawabanCell = document.createElement('td');
        jawabanCell.textContent = answer[i];
        row.appendChild(jawabanCell);
        var hasilCell = document.createElement('td');
        hasilCell.textContent = hasil[i];
        row.appendChild(hasilCell);
        // Add the row to the table body
        tableBody.appendChild(row);
        };
        document.getElementById('debugmodeButton').hidden = true;
    }

    ChangeQuestion(index);
</script>


<script>

const recordBtn = document.querySelector(".record")
result = document.querySelector(".result")

let SpeechRecognition =
    window.SpeechRecognition || window.webkitSpeechRecognition,
  recognition,
  recording = false;



function speechToText() {
  try {
    recognition = new SpeechRecognition();
    recognition.lang = 'id';
    recognition.interimResults = true;
    recordBtn.classList.add("recording");
    recordBtn.querySelector("p").innerHTML = "Sedang Mendengar";
    recognition.start();
    recognition.onresult = (event) => {
      const speechResult = event.results[0][0].transcript;
      //detect when intrim results
      if (event.results[0].isFinal) {
        result.value += " " + speechResult;
        
      } else {
        //creative p with class interim if not already there
        if (!document.querySelector(".interim")) {
          const interim = document.createElement("p");
          interim.classList.add("interim");
          result.appendChild(interim);
        }
        //update the interim p with the speech result
        document.querySelector(".interim").innerHTML = " " + speechResult;
      }
    };
    recognition.onspeechend = () => {
      speechToText();
    };
    recognition.onerror = (event) => {
      stopRecording();
      if (event.error === "no-speech") {
        alert("Mikrofon di non aktifkan");
      } else if (event.error === "audio-capture") {
        alert(
          "No microphone was found. Ensure that a microphone is installed."
        );
      } else if (event.error === "not-allowed") {
        alert("Permission to use microphone is blocked.");
      } else if (event.error === "aborted") {
        alert("Listening Stopped.");
      } else {
        alert("Error occurred in recognition: " + event.error);
      }
    };
  } catch (error) {
    recording = false;

    console.log(error);
  }
}

recordBtn.addEventListener("click", () => {
  if (!recording) {
    speechToText();
    recording = true;
  } else {
    stopRecording();
  }
});

function stopRecording() {
  recognition.stop();
  recordBtn.querySelector("p").innerHTML = "Aktifkan Mikrofon";
  recordBtn.classList.remove("recording");
  recording = false;
}
</script>