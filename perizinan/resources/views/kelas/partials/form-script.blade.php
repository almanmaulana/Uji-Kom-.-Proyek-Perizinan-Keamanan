<script>
    const jenjangSelect = document.getElementById('jenjang');
    const tingkatSelect = document.getElementById('tingkat');
    const jurusanSelect = document.getElementById('jurusan');
    const jurusanWrapper = document.getElementById('jurusan-wrapper');

    const tingkatOptions = {
        SMP: ['7', '8', '9'],
        SMA: ['10', '11', '12'],
        SMK: ['10', '11', '12']
    };

    const jurusanOptions = {
        SMA: ['IPA', 'IPS'],
        SMK: ['PPLG', 'APL', 'MPLB']
    };

    function updateOptions(selectedJenjang, selectedTingkat = null, selectedJurusan = null) {
        // isi tingkat
        tingkatSelect.innerHTML = '<option value="">-- Pilih Tingkat --</option>';
        if (tingkatOptions[selectedJenjang]) {
            tingkatOptions[selectedJenjang].forEach(t => {
                let option = document.createElement('option');
                option.value = t;
                option.text = t;
                if (t === selectedTingkat) option.selected = true;
                tingkatSelect.appendChild(option);
            });
        }

        // isi jurusan
        jurusanSelect.innerHTML = '<option value="">-- Pilih Jurusan --</option>';
        if (jurusanOptions[selectedJenjang]) {
            jurusanWrapper.style.display = 'block';
            jurusanOptions[selectedJenjang].forEach(j => {
                let option = document.createElement('option');
                option.value = j;
                option.text = j;
                if (j === selectedJurusan) option.selected = true;
                jurusanSelect.appendChild(option);
            });
        } else {
            jurusanWrapper.style.display = 'none';
        }
    }

    // jalankan saat pertama kali
    updateOptions(
        "{{ $selectedJenjang ?? old('jenjang') }}",
        "{{ $selectedTingkat ?? old('tingkat') }}",
        "{{ $selectedJurusan ?? old('jurusan') }}"
    );

    // jalankan saat jenjang berubah
    jenjangSelect.addEventListener('change', function () {
        updateOptions(this.value);
    });
</script>
