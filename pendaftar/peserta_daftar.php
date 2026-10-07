<?php
// ---- Terms gate: the visitor must accept Syarat_Daftar.html first ----
// The DAFTAR button on the landing page opens that warning page, and its
// "Lanjut ke Pendaftaran" action records the acceptance via setuju.php.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['syarat_diterima'])) {
    header('Location: ../Leptek_LandingPages/Syarat_Daftar.html');
    exit;
}

require_once __DIR__ . '/../db.php';

$errors = [];
$values = ['nama'=>'','npm'=>'','kelas'=>'','email'=>'','nohp'=>'','id_kursus'=>'','id_jurusan'=>''];
$kursus = $pdo->query('SELECT id, nama FROM kursus ORDER BY id ASC')->fetchAll();
$jurusan = $pdo->query('SELECT id_jurusan, nama_jurusan FROM jurusan ORDER by id_jurusan ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['nama'] = trim($_POST['nama'] ?? '');
    $values['npm'] = trim($_POST['npm'] ?? '');
    $values['kelas'] = trim($_POST['kelas'] ?? '');
    $values['email'] = trim($_POST['email'] ?? '');
    $values['nohp'] = trim($_POST['nohp'] ?? '');
    $values['id_kursus'] = trim($_POST['id_kursus'] ?? '');
    $values['id_jurusan'] = trim($_POST['id_jurusan'] ?? '');

    // Nama: tidak boleh kosong dan tidak mengandung angka
    if ($values['nama'] === '') {
        $errors['nama'] = 'Nama wajib diisi.';
    } elseif (preg_match('/\d/', $values['nama'])) {
        $errors['nama'] = 'Nama tidak boleh mengandung angka.';
    }

    // NPM: harus 8 digit angka, tidak boleh kosong
    if ($values['npm'] === '') {
        $errors['npm'] = 'NPM wajib diisi.';
    } elseif (!preg_match('/^[0-9]{8}$/', $values['npm'])) {
        $errors['npm'] = 'NPM harus tepat 8 digit angka.';
    }

    // Kelas: wajib
    if ($values['kelas'] === '') {
        $errors['kelas'] = 'Kelas wajib diisi.';
    }

    // Daftar typo domain umum yang pasti diblokir
    $knownTypos = [
        'gmil.com', 'gmuil.com', 'gmaill.com', 'gmai.com', 'gmail.co', 'gmail.c',
        'yaho.com', 'yahho.com', 'yaho.co.id', 'yahoo.c', 'yahoo.co',
        'outlokk.com', 'outlok.com', 'outlook.c', 'hotmail.c', 'hotmai.com'
    ];

    // Email Validation: Whitelist provider utama (.com & .co.id) + blokir typo
    $emailDomain = strtolower(substr(strrchr($values['email'], "@"), 1));

    if ($values['email'] === '') {
        $errors['email'] = 'Email wajib diisi.';
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Format email tidak valid (contoh: user@gmail.com).';
    } elseif (in_array($emailDomain, $knownTypos, true)) {
        $errors['email'] = 'Domain email terdeteksi salah/typo. Periksa kembali penulisan email anda.';
    } elseif (!preg_match('/^[a-zA-Z0-9.-]+\.(com|co\.id|ac\.id|sch\.id|net|org|id)$/', $emailDomain)) {
        $errors['email'] = 'Gunakan email dengan domain yang valid (misal: .com, .co.id, .ac.id).';
    }

    // NoHP: 10-13 digit angka
    if ($values['nohp'] === '') {
        $errors['nohp'] = 'Nomor Whatsapp wajib diisi.';
    } elseif (!preg_match('/^[0-9]{10,13}$/', $values['nohp'])) {
        $errors['nohp'] = 'Nomor Whatsapp harus berupa 10-13 digit angka.';
    }

    // Kursus: wajib dan harus tersedia
    $kursusIds = array_column($kursus, 'id');
    if ($values['id_kursus'] === '' || !ctype_digit($values['id_kursus']) || !in_array((int) $values['id_kursus'], array_map('intval', $kursusIds), true)) {
        $errors['id_kursus'] = 'Pilihan kursus wajib dipilih.';
    }

    // Jurusan: wajib dan harus tersedia
    $jurusanIds = array_column($jurusan, 'id_jurusan');
    if ($values['id_jurusan'] === '' || !ctype_digit($values['id_jurusan']) || !in_array((int) $values['id_jurusan'], array_map('intval', $jurusanIds), true )) {
        $errors['id_jurusan'] = 'Pilihan Jurusan Wajib dipilih.';
    }

    // Cek unik NPM dan NoHP ke Database
    if (empty($errors)) {
        $check = $pdo->prepare('SELECT npm, nohp FROM pendaftar WHERE npm = :npm OR nohp = :nohp LIMIT 1');
        $check->execute(['npm' => $values['npm'], 'nohp' => $values['nohp']]);
        $exists = $check->fetch();
        if ($exists) {
            if ($exists['npm'] === $values['npm']) {
                $errors['npm'] = 'NPM sudah terdaftar. Gunakan NPM lain.';
            }
            if ($exists['nohp'] === $values['nohp']) {
                $errors['nohp'] = 'Nomor Whatsapp sudah terdaftar. Gunakan nomor lain.';
            }
        }
    }

    if (empty($errors)) {
        $ins = $pdo->prepare('INSERT INTO pendaftar (npm, nama, kelas, email, nohp, kursus_id, id_jurusan, tanggal_daftar, waktu_daftar) VALUES (:npm, :nama, :kelas, :email, :nohp, :id_kursus, :id_jurusan, NOW(), CURTIME())');
        $ins->execute([
            'npm' => $values['npm'],
            'nama' => $values['nama'],
            'kelas' => $values['kelas'],
            'email' => $values['email'],
            'nohp' => $values['nohp'],
            'id_kursus' => (int) $values['id_kursus'],
            'id_jurusan' => (int) $values['id_jurusan'],
        ]);
        // PRG: redirect to the thank-you page so a refresh cannot resubmit
        header('Location: terima_kasih.html');
        exit;
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulir Pendaftaran Peserta</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 16px;
            position: relative;
        }

        /* Tombol Back Panah */
        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-decoration: none;
            z-index: 10;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-back:hover {
            background: #00e5ff;
            color: #090a16;
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        }

        .btn-back svg {
            width: 22px;
            height: 22px;
            fill: currentColor;
        }

        /* Container Responsif */
        .container { 
            background: rgba(255, 255, 255, 0.96);
            border-radius: 20px;
            padding: 35px 28px;
            width: 100%;
            max-width: 680px; /* Diperlebar sedikit agar nyaman 2-kolom di Desktop */
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            position: relative;
            margin-top: 40px; /* Memberi ruang untuk tombol back di mobile */
        }

        h1 {
            text-align: center;
            color: #1a1c35;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        /* Grid Layout Responsif */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .form-row { 
            display: flex;
            flex-direction: column;
        }

        .form-row.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #444444;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input[type="text"],
        input[type="email"],
        select { 
            width: 100%; 
            padding: 12px 14px; 
            border: 1px solid #dcdcdc; 
            border-radius: 8px; 
            background: #ffffff; 
            color: #333333; 
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input::placeholder,
        select:invalid {
            color: #a0a0a0;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        select:focus { 
            border-color: #00e5ff; 
            box-shadow: 0 0 0 3px rgba(0, 229, 255, 0.2);
        }

        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: #00e5ff;
            color: #090a16;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 229, 255, 0.3);
            grid-column: 1 / -1;
        }

        button[type="submit"]:hover {
            background-color: #00b3cc;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 229, 255, 0.5);
        }

        .invalid { border: 1.5px solid #e74c3c !important; }
        .msg { margin-bottom: 20px; padding: 12px 16px; border-radius: 8px; font-size: 14px; text-align: center; font-weight: 500;}
        .msg.success { background: #2ecc71; color: #fff; }
        .msg.error { background: #e74c3c; color: #fff; }
        .err { background: #fceae9; color: #d63031; border-left: 3px solid #e74c3c; padding: 6px 10px; margin-top: 6px; border-radius: 4px; font-size: 12px; font-weight: 500;}

        /* Penyesuaian Media Query untuk Desktop (Layar >= 640px) */
        @media (min-width: 640px) {
            body {
                padding: 40px;
            }

            .container {
                padding: 40px 45px;
                margin-top: 0;
            }

            .btn-back {
                position: fixed;
                top: 24px;
                left: 24px;
            }

            .form-grid {
                grid-template-columns: 1fr 1fr;
                gap: 18px;
            }

            h1 {
                font-size: 28px;
                margin-bottom: 30px;
            }
        }
    </style>
</head>

<body>

    <!-- Tombol Back Icon -->
    <a href="../index.php" class="btn-back" title="Kembali ke Beranda">
        <svg viewBox="0 0 24 24">
            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
        </svg>
    </a>

    <div class="container">
        <h1>Formulir Pendaftaran</h1>


        
        <div id="main-error-msg" class="msg error" style="display: <?= $errors ? 'block' : 'none' ?>;">
            Terdapat kesalahan pada formulir. Periksa input yang ditandai.
        </div>

        <form id="regform" method="post" action="" novalidate class="form-grid">
            
            <div class="form-row">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="<?=isset($errors['nama']) ? 'invalid' : ''?>" placeholder="Ketikkan Nama Lengkap" value="<?=htmlspecialchars($values['nama'])?>">
                <div class="err" id="err-nama" style="display: <?=isset($errors['nama']) ? 'block' : 'none'?>;"><?=htmlspecialchars($errors['nama'] ?? '')?></div>
            </div>

            <div class="form-row">
                <label for="npm">NPM</label>
                <input type="text" id="npm" name="npm" class="<?=isset($errors['npm']) ? 'invalid' : ''?>" inputmode="numeric" maxlength="8" placeholder="Contoh: 12345678" value="<?=htmlspecialchars($values['npm'])?>">
                <div class="err" id="err-npm" style="display: <?=isset($errors['npm']) ? 'block' : 'none'?>;"><?=htmlspecialchars($errors['npm'] ?? '')?></div>
            </div>

            <div class="form-row">
                <label for="id_jurusan">Jurusan</label>
                <select name="id_jurusan" id="id_jurusan" class="<?=isset($errors['id_jurusan']) ? 'invalid' : '' ?>">
                    <option value="" disabled <?= empty($values['id_jurusan']) ? 'selected' : '' ?>>Silahkan Pilih Jurusan</option>
                    <?php foreach($jurusan as $item): ?>
                        <option value="<?=htmlspecialchars($item['id_jurusan'])?>"
                        <?= ((string) $values['id_jurusan'] === (string) $item['id_jurusan']) ? 'selected': ''?>><?=htmlspecialchars($item['nama_jurusan']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="err" id="err-id_jurusan" style="display: <?=isset($errors['id_jurusan']) ? 'block' : 'none'?>;"><?=htmlspecialchars($errors['id_jurusan'] ?? '')?></div>
            </div>

            <div class="form-row">
                <label for="kelas">Kelas</label>
                <input type="text" id="kelas" name="kelas" class="<?=isset($errors['kelas']) ? 'invalid' : ''?>" placeholder="Contoh: 3IA01" value="<?=htmlspecialchars($values['kelas'])?>">
                <div class="err" id="err-kelas" style="display: <?=isset($errors['kelas']) ? 'block' : 'none'?>;"><?=htmlspecialchars($errors['kelas'] ?? '')?></div>
            </div>

            <div class="form-row">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="<?=isset($errors['email']) ? 'invalid' : ''?>" placeholder="nama@domain.com" value="<?=htmlspecialchars($values['email'])?>">
                <div class="err" id="err-email" style="display: <?=isset($errors['email']) ? 'block' : 'none'?>;"><?=htmlspecialchars($errors['email'] ?? '')?></div>
            </div>

            <div class="form-row">
                <label for="nohp">Nomor WhatsApp</label>
                <input type="text" id="nohp" name="nohp" class="<?=isset($errors['nohp']) ? 'invalid' : ''?>" inputmode="numeric" maxlength="13" placeholder="08xxxxxxxxxx" value="<?=htmlspecialchars($values['nohp'])?>">
                <div class="err" id="err-nohp" style="display: <?=isset($errors['nohp']) ? 'block' : 'none'?>;"><?=htmlspecialchars($errors['nohp'] ?? '')?></div>
            </div>

            <div class="form-row full-width">
                <label for="id_kursus">Pilihan Kursus</label>
                <select id="id_kursus" name="id_kursus" class="<?=isset($errors['id_kursus']) ? 'invalid' : ''?>">
                    <option value="" disabled <?=empty($values['id_kursus']) ? 'selected' : ''?>>Silahkan Pilih Kursus</option>
                    <?php foreach ($kursus as $item): ?>
                        <option value="<?=htmlspecialchars($item['id'])?>" <?=((string) $values['id_kursus'] === (string) $item['id']) ? 'selected' : ''?>><?=htmlspecialchars($item['nama'])?></option>
                    <?php endforeach; ?>
                </select>
                <div class="err" id="err-id_kursus" style="display: <?=isset($errors['id_kursus']) ? 'block' : 'none'?>;"><?=htmlspecialchars($errors['id_kursus'] ?? '')?></div>
            </div>

            <button type="submit">Daftar Sekarang</button>
        </form>
    </div>

    <script>
    (function(){
        const form = document.getElementById('regform');
        const mainErrorMsg = document.getElementById('main-error-msg');

        // Daftar domain typo yang langsung diblokir
        const knownTypos = [
            'gmil.com', 'gmuil.com', 'gmaill.com', 'gmai.com', 'gmail.co', 'gmail.c',
            'yaho.com', 'yahho.com', 'yaho.co.id', 'yahoo.c', 'yahoo.co',
            'outlokk.com', 'outlok.com', 'outlook.c', 'hotmail.c', 'hotmai.com'
        ];

        // Provider resmi yang selalu diizinkan
        const validDomains = [
            'gmail.com', 'yahoo.com', 'yahoo.co.id',
            'outlook.com', 'hotmail.com', 'icloud.com',
            'live.com', 'msn.com'
        ];

        function showError(fieldId, message) {
            const el = document.getElementById(fieldId);
            const errBox = document.getElementById('err-' + fieldId);
            el.classList.add('invalid');
            errBox.innerText = message;
            errBox.style.display = 'block';
        }

        function clearError(fieldId) {
            const el = document.getElementById(fieldId);
            const errBox = document.getElementById('err-' + fieldId);
            el.classList.remove('invalid');
            errBox.innerText = '';
            errBox.style.display = 'none';
        }

        form.addEventListener('submit', function(e){
            let hasError = false;

            // 1. Validasi Nama
            const nama = document.getElementById('nama').value.trim();
            if (nama === '') {
                showError('nama', 'Nama wajib diisi.');
                hasError = true;
            } else if (/\d/.test(nama)) {
                showError('nama', 'Nama tidak boleh mengandung angka.');
                hasError = true;
            } else {
                clearError('nama');
            }

            // 2. Validasi NPM
            const npm = document.getElementById('npm').value.trim();
            if (npm === '') {
                showError('npm', 'NPM wajib diisi.');
                hasError = true;
            } else if (!/^[0-9]{8}$/.test(npm)) {
                showError('npm', 'NPM harus tepat 8 digit angka.');
                hasError = true;
            } else {
                clearError('npm');
            }

            // 3. Validasi Jurusan
            const id_jurusan = document.getElementById('id_jurusan').value;
            if (!id_jurusan){
                showError('id_jurusan', 'Pilihan jurusan wajib dipilih.');
                hasError = true;
            } else {
                clearError('id_jurusan');
            }

            // 4. Validasi Kelas
            const kelas = document.getElementById('kelas').value.trim();
            if (kelas === '') {
                showError('kelas', 'Kelas wajib diisi.');
                hasError = true;
            } else {
                clearError('kelas');
            }

            // 5. Validasi Email
            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            const domain = email.includes('@') ? email.split('@')[1].toLowerCase() : '';
            const validSuffixRegex = /\.(com|co\.id|ac\.id|sch\.id|net|org|id)$/;

            if (email === '') {
                showError('email', 'Email wajib diisi.');
                hasError = true;
            } else if (!emailRegex.test(email)) {
                showError('email', 'Format email tidak valid.');
                hasError = true;
            } else if (knownTypos.includes(domain)) {
                showError('email', 'Domain email typo (misal: ' + domain + '). Periksa penulisan email.');
                hasError = true;
            } else if (!validSuffixRegex.test(domain) && !validDomains.includes(domain)) {
                showError('email', 'Gunakan domain email yang valid (misal: .com, .co.id, .ac.id).');
                hasError = true;
            } else {
                clearError('email');
            }

            // 6. Validasi No HP / Whatsapp
            const nohp = document.getElementById('nohp').value.trim();
            if (nohp === '') {
                showError('nohp', 'Nomor Whatsapp wajib diisi.');
                hasError = true;
            } else if (!/^[0-9]{10,13}$/.test(nohp)) {
                showError('nohp', 'Nomor Whatsapp harus 10-13 digit angka.');
                hasError = true;
            } else {
                clearError('nohp');
            }

            // 7. Validasi Pilihan Kursus
            const id_kursus = document.getElementById('id_kursus').value;
            if (!id_kursus) {
                showError('id_kursus', 'Pilihan kursus wajib dipilih.');
                hasError = true;
            } else {
                clearError('id_kursus');
            }

            if (hasError) {
                e.preventDefault();
                mainErrorMsg.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    })();
    </script>
</body>
</html>