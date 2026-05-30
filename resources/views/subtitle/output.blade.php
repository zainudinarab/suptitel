<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            margin: 0;
            background: transparent;
            overflow: hidden;
        }

        .container {
            position: fixed;
            bottom: 50px;
            left: 0;
            width: 100%;
            text-align: center;
        }

        .box {
            display: inline-block;

            background: rgba(0, 0, 0, .55);

            padding: 15px 30px;

            border-radius: 10px;
        }

        .word {

            color: white;

            font-size: 72px;

            font-family:
                'Amiri',
                'Traditional Arabic',
                serif;

            text-shadow:
                0 0 5px #000,
                0 0 10px #000,
                0 0 20px #000;
            transition: all 0.3s ease;
            margin: 0 10px;
        }

        .done {
            color: #00ff66;
            /* Warna kata yang sudah dibaca */
            opacity: 0.7;
        }

        .active {
            color: #ffd700;
            /* Warna kata yang sedang dibaca */
            font-size: 84px;
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <div id="display-container" class="container">
        <!-- Isi akan diupdate oleh JavaScript -->
    </div>

    <script>
        let lastState = "";

        async function updateOutput() {
            try {
                const response = await fetch(
                '/api/live-subtitle'); // Anda perlu mendaftarkan route ini di web.php/api.php
                const data = await response.json();

                const currentState = JSON.stringify(data);
                if (currentState === lastState) return; // Jangan update jika tidak ada perubahan
                lastState = currentState;

                const container = document.getElementById('display-container');
                if (!data.has_item) {
                    container.innerHTML = '';
                    return;
                }

                let html = '<div class="box" dir="rtl">';
                data.words.forEach((word, i) => {
                    let cls = 'word';
                    if (i < data.current_word) cls += ' done';
                    else if (i == data.current_word) cls += ' active';
                    html += `<span class="${cls}">${word}</span>`;
                });
                html += '</div>';
                container.innerHTML = html;
            } catch (e) {
                console.error("Update error", e);
            }
        }

        setInterval(updateOutput, 300); // Cek update setiap 300ms
    </script>
</body>

</html>
