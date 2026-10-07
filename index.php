<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>LEPTEK</title>
    <style>
      html {
        scroll-behavior: smooth;
      }

      /* ================= AI CHAT WIDGET STYLES ================= */
      .ai-chat-widget {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 12px;
        /* Animasi transisi saat muncul/sembunyi saat di-scroll */
        opacity: 0;
        transform: translateY(30px) scale(0.9);
        pointer-events: none;
        transition: opacity 0.5s ease, transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.27);
      }

      /* Kelas aktif saat di-scroll mencapai area pendaftaran */
      .ai-chat-widget.visible {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
      }

      /* Modal tersembunyi secara bawaan */
      .ai-chat-modal.hidden,
      .ai-chat-tooltip.dismissed {
        display: none !important;
      }

      /* Balon Info (Tooltip) Dipindah ke Atas Tombol Chat & Dipertegas */
      .ai-chat-tooltip {
        background: #111428;
        color: #ffffff;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13px;
        box-shadow: 0 8px 25px rgba(0, 210, 255, 0.25), 0 4px 15px rgba(0, 0, 0, 0.5);
        border: 1.5px solid #00d2ff; /* Border bercahaya agar tegas */
        max-width: 230px;
        line-height: 1.4;
        position: relative;
        animation: floatBounce 2.5s ease-in-out infinite;
      }

      /* Badge / Header Balon Bicara */
      .tooltip-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
      }

      .tooltip-badge {
        background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%);
        color: #000;
        font-weight: 800;
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 20px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
      }

      .tooltip-close {
        background: none;
        border: none;
        color: #888;
        font-size: 14px;
        cursor: pointer;
        padding: 0;
        line-height: 1;
      }

      .tooltip-close:hover {
        color: #fff;
      }

      .tooltip-body {
        font-weight: 500;
        color: #e0e6ed;
      }

      /* Panah Kecil Balon Info Menunjuk ke Bawah (ke arah Tombol Chat) */
      .ai-chat-tooltip::after {
        content: "";
        position: absolute;
        bottom: -9px;
        right: 22px;
        border-width: 9px 8px 0 8px;
        border-style: solid;
        border-color: #00d2ff transparent transparent transparent;
      }

      /* Tombol Melayang Chat */
      .ai-chat-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: #fff;
        border: 2px solid rgba(255, 255, 255, 0.2);
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(37, 117, 252, 0.5);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .ai-chat-btn:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 8px 28px rgba(37, 117, 252, 0.7);
      }

      /* Modal / Jendela Chat */
      .ai-chat-modal {
        position: fixed;
        bottom: 105px;
        right: 30px;
        width: 340px;
        height: 440px;
        background-color: #181824;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
        z-index: 1001;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        font-family: inherit;
        animation: modalFadeIn 0.3s ease-out;
      }

      .chat-header {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: #fff;
        padding: 14px 16px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .chat-close-btn {
        background: none;
        border: none;
        color: #fff;
        font-size: 22px;
        cursor: pointer;
        opacity: 0.8;
      }

      .chat-close-btn:hover {
        opacity: 1;
      }

      .chat-messages {
        flex: 1;
        padding: 14px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background-color: #12121c;
      }

      .message {
        max-width: 80%;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 13px;
        line-height: 1.4;
      }

      .message.bot {
        background-color: #232338;
        color: #e1e1e1;
        align-self: flex-start;
      }

      .message.user {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: #fff;
        align-self: flex-end;
      }

      .chat-input-area {
        display: flex;
        padding: 10px;
        background-color: #181824;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        gap: 8px;
      }

      .chat-input-area input {
        flex: 1;
        background-color: #232338;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        padding: 8px 12px;
        border-radius: 6px;
        outline: none;
      }

      .chat-input-area input:focus {
        border-color: #2575fc;
      }

      .chat-input-area .btn-send {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: #fff;
        border: none;
        padding: 8px 14px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
      }

      .chat-input-area .btn-send:hover {
        opacity: 0.9;
      }

      @keyframes floatBounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-7px); }
      }

      @keyframes modalFadeIn {
        from {
          opacity: 0;
          transform: translateY(15px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
    /* ================= SECTION YANG DISEMBUNYIKAN (SEMENTARA) =================
         Silabus & Program (id="program"), Jadwal Kursus, dan PORTFOLIO masih
         tersimpan lengkap di index.php, tetapi tidak ditampilkan di halaman.
         Untuk menampilkan kembali: ubah "display: none" menjadi "display: block"
         pada aturan di bawah (atau hapus blok aturan ini). */
      .silabus,
      .JK,
      .portfolio {
        display: none !important;
      }

      /* ===== RESPONSIVE (mobile) — AI chat widget ===== */
      @media (max-width: 420px) {
        .ai-chat-widget {
          right: 16px;
          bottom: 16px;
        }

        .ai-chat-modal {
          width: calc(100vw - 24px);
          right: 12px;
          bottom: 95px;
        }

        .ai-chat-tooltip {
          max-width: 210px;
          right: 12px;
        }
      }
    </style>
    <link rel="stylesheet" href="Leptek_LandingPages/LP.css?v=23" />
  </head>

  <body>
    <!-- ================= NAVBAR ================= -->
    <header class="navbar">
      <div class="navbar-container">
        <div class="logo">LEPTEK</div>
        <button
          class="nav-toggle"
          type="button"
          aria-label="Buka menu navigasi"
          aria-expanded="false"
        >
          <span></span>
          <span></span>
          <span></span>
        </button>
        <nav>
          <ul>
            <li><a href="#home">HOME</a></li>
           <!-- <li><a href="#tentang-kami">TENTANG KAMI</a></li> -->
            <!--<li><a href="#program">PROGRAM</a></li> -->
            <li><a href="Leptek_LandingPages/Kontak_Person.html">KONTAK</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <!-- ================= HERO 2 ================= -->
    <section class="hero2" id="home">
      <div class="hero-content2">
        <h1>SELAMAT DATANG DI</h1>
        <div class="hero-image">
          <img src="Images/LepTek Logo.png" alt="Leptek" />
          <p class="hero-small-title2"></p>
        </div>
      </div>
    </section>

    <!-- ================= INTRO VIDEO SECTION ================= -->
    <section class="intro-video" id="tentang-kami">
      <div class="intro-container">
        <div class="image-thumbnail">
          <img src="Images/LepTek Logo.png" alt="Leptek" />
        </div>

        <!-- Right: Text content -->
        <div class="intro-text">
          <h2>LEPTEK</h2>
          <p>
            Salah satu Lembaga kursus Gunadarma dibidang teknologi dan kreatif, yang berisi dua LABORATORIUM
            CITRA & MULTIMEDIA
          </p>
          <p>
            Laboratorium Citra menyediakan kursus Animasi dan games. Pada Laboratorium Multimedia menyediakan kursus Editing dan 
            Desain Grafis.
          </p>
        </div>
      </div>
    </section>

    <!--============== FEATURES ==============-->
    <section class="features">
      <h2 class="features-title">DUA KURSUS LEPTEK</h2>

      <div class="features-container">
        <div class="feature">
          <h3>Multimedia</h3>
          <p>Video Editing, UI/UX</p>
        </div>
        <div class="feature">
          <h3>Citra</h3>
          <p>Animasi and Games</p>
        </div>
      </div>
    </section>

    <!-- ================= PROFIL LABORATORIUM ================= -->
    <section class="profil-lab">
      <div class="profil-container">
        <div class="section-title">
          <h2>Profil Laboratorium</h2>
          <span></span>
        </div>

        <div class="profil-cards">
          <div class="profil-card">
            <h3 class="profil-name">
              Laboratorium Pengembangan Pengolahan Citra
            </h3>

            <ul class="profil-list">
              <li>
                <span class="profil-label">Lokasi</span>
                <span class="profil-colon">:</span>
                <span class="profil-value"
                  >Kampus D Ruang D317B (Gedung 3, Lantai 1, Ruang 7B)</span
                >
              </li>
              <li>
                <span class="profil-label">Penanggung Jawab</span>
                <span class="profil-colon">:</span>
                <span class="profil-value"
                  >Dr. Sulistyo Puspitodjati., SSi., MSc</span
                >
              </li>
              <li>
                <span class="profil-label">Berdiri Sejak</span>
                <span class="profil-colon">:</span>
                <span class="profil-value"
                  >1996 (sebelumnya Lab. Pengembangan Pengolahan Citra dan
                  Multimedia)</span
                >
              </li>
              <li>
                <span class="profil-label">Afiliasi</span>
                <span class="profil-colon">:</span>
                <span class="profil-value"
                  >Di bawah Lembaga Pengembangan Teknologi (LePTek) Universitas
                  Gunadarma</span
                >
              </li>
              <li class="struktur-row">
                <span class="struktur-heading">Struktur Organisasi:</span>
                <span class="struktur-line">
                  <span class="struktur-role"
                    >Kepala Laboratorium Pengembangan Pengolahan Citra</span
                  >
                  <span class="struktur-colon">:</span>
                  <span class="struktur-name"
                    >Dr. Sulistyo Puspitodjati., SSi., MSc.</span
                  >
                </span>
                <span class="struktur-subheading">Staff Laboratorium :</span>
                <span class="struktur-staff">Nur Putri Agustiyani, Skom.,</span>
                <span class="struktur-staff"
                  >Achdiyat Fadhillah Tjakradidjaja, Skom.</span
                >
              </li>
            </ul>
          </div>

          <div class="profil-card">
            <h3 class="profil-name">Laboratorium Pengembangan Multimedia</h3>

            <ul class="profil-list">
              <li>
                <span class="profil-label">Lokasi</span>
                <span class="profil-colon">:</span>
                <span class="profil-value"
                  >Kampus D Ruang D317D (Gedung 3, Lantai 1, Ruang 7D)</span
                >
              </li>
              <li>
                <span class="profil-label">Penanggung Jawab</span>
                <span class="profil-colon">:</span>
                <span class="profil-value">Arief Subroto, Skom,. MMSI.</span>
              </li>
              <li>
                <span class="profil-label">Berdiri Sejak</span>
                <span class="profil-colon">:</span>
                <span class="profil-value"
                  >1996 (sebelumnya Lab. Pengembangan Pengolahan Citra dan
                  Multimedia)</span
                >
              </li>
              <li>
                <span class="profil-label">Afiliasi</span>
                <span class="profil-colon">:</span>
                <span class="profil-value"
                  >Di bawah Lembaga Pengembangan Teknologi (LePTek) Universitas
                  Gunadarma</span
                >
              </li>
              <li class="struktur-row">
                <span class="struktur-heading">Struktur Organisasi:</span>
                <span class="struktur-line">
                  <span class="struktur-role"
                    >Kepala Laboratorium Pengembangan Multimedia</span
                  >
                  <span class="struktur-colon">:</span>
                  <span class="struktur-name"
                    >Arief Subroto, Skom., MMSI.</span
                  >
                </span>
                <span class="struktur-subheading">Staff Laboratorium :</span>
                <span class="struktur-staff">Budi Setiawan, ST., MMSI.,</span>
                <span class="struktur-staff">Yudi Sdha, ST., MMSI.,</span>
                <span class="struktur-staff">Itar Mintarsih, ST., MMSI.,</span>
                <span class="struktur-staff"
                  >Dina Suci Darwati, ST., MMSI.</span
                >
              </li>
            </ul>
          </div>
        </div>

      </div>
    </section>

    <!-- ================= KEGIATAN KURSUS & WORKSHOP ================= -->
    <!-- Two lab title cards - each opens the full Kegiatan page for that lab -->
    <section class="profil-lab fasilitas-lab" id="kegiatan">
      <div class="profil-container">
        <div class="section-title">
          <h2>Kegiatan Kursus &amp; Workshop</h2>
          <span></span>
        </div>

        <!-- Centered note between the title and the two lab cards: visitors
             should learn the difference between kursus intensif and reguler
             before picking a course. The bold text sits above the "Disini"
             button, which links to the explanation page. -->
        <div class="lokasi-desc">
          <p class="kegiatan-note-text">
            Sebelum memilih kursus pahami lebih dulu mengenai kursus intensif
            dan reguler
          </p>
          <a
            href="Leptek_LandingPages/Kursus.html"
            class="btn-red kegiatan-note-btn"
            >Disini</a
          >
        </div>

        <div class="lokasi-grid">
          <div class="lokasi-item">
            <h3>Kegiatan Kursus &amp; Workshop Citra</h3>
            <a href="Leptek_LandingPages/Kegiatan_Citra.html" class="btn-red">Lihat Detail</a>
          </div>

          <div class="lokasi-item">
            <h3>Kegiatan Kursus &amp; Workshop Multimedia</h3>
            <a href="Leptek_LandingPages/Kegiatan_Multimedia.html" class="btn-red">Lihat Detail</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ================= FASILITAS & PERALATAN ================= -->
    <!-- Two lab title cards - each opens the full Fasilitas & Peralatan page -->
    <section class="profil-lab fasilitas-lab">
      <div class="profil-container">
        <div class="section-title">
          <h2>Fasilitas &amp; Peralatan</h2>
          <span></span>
        </div>

        <div class="lokasi-grid">
          <div class="lokasi-item">
            <h3>Fasilitas &amp; Peralatan Multimedia</h3>
            <a href="Leptek_LandingPages/Fasilitas_Multimedia.html" class="btn-red">Lihat Detail</a>
          </div>

          <div class="lokasi-item">
            <h3>Fasilitas &amp; Peralatan Citra</h3>
            <a href="Leptek_LandingPages/Fasilitas_Citra.html" class="btn-red">Lihat Detail</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ================= SILABUS & PROGRAM ================= -->
    <!-- Catatan: section ini disembunyikan dari tampilan lewat CSS .silabus (lihat <style> di <head>) -->
    <section class="silabus" id="program">
      <!-- Background image -->
      <div class="silabus-bg">
        <img src="Images/Gunadarma Logo.png" alt="Background" />
      </div>

      <div class="silabus-container">
        <div class="section-title">
          <div class="title-text">
            <h2>Silabus & Program Pembelajaran </h2>
          </div>
        </div>

        <div class="silabus-grid">
          <!-- Card 1 -->
          <div class="silabus-item">
            <h3>Contoh</h3>
            <p>Contoh</p>
            <a href="Leptek_LandingPages/Materi.html?judul=Contoh" class="btn-red">Lihat Materi</a>
          </div>

          <!-- Card 2 -->
          <div class="silabus-item">
            <h3>Contoh</h3>
            <p>Contoh</p>
            <a href="Leptek_LandingPages/Materi.html?judul=Contoh" class="btn-red">Lihat Materi</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ================= Jadwal Kursus ================= -->
    <!-- Catatan: section ini disembunyikan dari tampilan lewat CSS .JK (lihat <style> di <head>) -->
    <section class="JK">
      <div class="JK-container">
        <div class="JK-title">
          <h2>Jadwal Kursus Semester Ganjil PTA 26/27</h2>
        </div>

        <p class="JK-desc">
          Lihat jadwal lengkap kursus per angkatan untuk setiap hari dan slot waktu
          pada semester ganjil PTA 26/27.
        </p>

        <div class="JK-action">
          <a href="Leptek_LandingPages/Jadwal.html" class="btn-red">Lihat Jadwal</a>

          <div class="JK-select-group">
            <select id="JK-angkatan">
              <option value="" disabled selected hidden>
                Silahkan Pilih Angkatan
              </option>
              <option value="2023">Angkatan 2023</option>
              <option value="2024">Angkatan 2024</option>
              <option value="2025">Angkatan 2025</option>
            </select>

            <a href="#" id="JK-lihat-angkatan" class="btn-red">Lihat Jadwal Angkatan</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ================= Lokasi Kursus ================= -->
    <section class="lokasi">
      <div class="lokasi-container">
        <div class="section-title">
          <h2>Lokasi Kursus</h2>
          <span></span>
        </div>

        <p class="lokasi-desc">
          Lokasi Kursus berada di gedung 3 Universitas Gunadarma.
        </p>

        <div class="lokasi-grid">
          <div class="lokasi-item">
            <h3>Multimedia</h3>
            <p>Berada di Ruang D317D</p>
            <a href="Leptek_LandingPages/Materi.html?img=Multimedia1.jpeg%2CMultimedia2.jpeg%2CMultimedia3.jpeg&amp;judul=Ruang%20D317D" class="btn-red">Lihat gambar</a>
          </div>

          <div class="lokasi-item">
            <h3>Citra</h3>
            <p>Berada di Ruang D317B</p>
            <a href="Leptek_LandingPages/Materi.html?img=Citra1.jpeg%2CCitra2.jpeg%2CCitra3.jpeg&amp;judul=Ruang%20D317B" class="btn-red">Lihat gambar</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ================= PORTFOLIO ================= -->
    <!-- Catatan: section ini disembunyikan dari tampilan lewat CSS .portfolio (lihat <style> di <head>) -->
    <section class="portfolio">
      <div class="portfolio-container">
        <div class="section-title">
          <p>OUR WORK</p>
          <h2>PORTFOLIO</h2>
        </div>

        <div class="portfolio-grid">
          <!-- Card 1 -->
          <div class="portfolio-item">
            <div class="portfolio-image">
              <img src="Images/3.jpeg" alt="Portofolio Multimedia" />
            </div>
            <h3>Portofolio Multimedia</h3>
          </div>

          <!-- Card 2 -->
          <div class="portfolio-item">
            <div class="portfolio-image">
              <img src="Images/bg.jpeg" alt="Pendaftaran Kursus" />
            </div>
            <h3>Pendaftaran Kursus</h3>
          </div>

          <!-- Card 3 -->
          <div class="portfolio-item">
            <div class="portfolio-image">
              <img src="Images/b_Apply_the_outfit_fro.png" alt="Portofolio Citra" />
            </div>
            <h3>Portofolio Citra</h3>
          </div>
        </div>
      </div>
    </section>

    <!-- ================= HERO (PENDAFTARAN) ================= -->
    <section class="hero">
      <div class="hero-content">
        <p class="hero-small-title"></p>
        <h1>SILAHKAN DAFTAR KURSUS DISINI</h1>
        <a href="Leptek_LandingPages/Syarat_Daftar.html" class="hero-button">DAFTAR</a>
      </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-logo">LEPTEK</div>

        <p>© 2026 LEPTEK</p>

        <div class="socials" id="kontak">
          <a href="#">Instagram</a>
        </div>
      </div>
    </footer>

    <!-- ================= AI CHAT FLOATING WIDGET ================= -->
    <div id="ai-chat-widget" class="ai-chat-widget">
      <!-- Balon Info (Berada di atas Tombol Chat) -->
      <div id="ai-chat-tooltip" class="ai-chat-tooltip">
        <div class="tooltip-header">
          <span class="tooltip-badge">AI Assistant 💡</span>
          <button id="tooltip-close" class="tooltip-close" title="Tutup balon">&times;</button>
        </div>
        <div class="tooltip-body">
          Ada pertanyaan? Gunakan AI-Assisted Chat kami di sini!
        </div>
      </div>

      <!-- Tombol Melayang Chat -->
      <button id="ai-chat-toggle" class="ai-chat-btn" title="Chat dengan AI">
        💬
      </button>
    </div>

    <!-- Modal / Jendela Chat Widget -->
    <div id="ai-chat-modal" class="ai-chat-modal hidden">
      <div class="chat-header">
        <span>AI Assistant LEPTEK</span>
        <button id="chat-close" class="chat-close-btn">&times;</button>
      </div>
      <div id="chat-messages" class="chat-messages">
        <div class="message bot">
          Halo! Ada yang bisa saya bantu terkait kursus dan informasi di LEPTEK?
        </div>
      </div>
      <div class="chat-input-area">
        <input type="text" id="chat-input" placeholder="Tulis pertanyaan Anda..." />
        <button id="chat-send" class="btn-send">Kirim</button>
      </div>
    </div>

    <!-- ================= NAVBAR SCROLL BEHAVIOR ================= -->
    <script>
      const navbar = document.querySelector(".navbar");
      const NAVBAR_HEIGHT = 60;
      let lastScrollY = window.scrollY;
      let hoveringNavbar = false;
      let clickCooldown = 0;
      let firstScrollAfterLoad = true;

      function showNavbar() {
        navbar.classList.remove("hidden");
      }

      showNavbar();

      window.addEventListener("pageshow", () => {
        showNavbar();
        lastScrollY = window.scrollY;
      });

      window.addEventListener("scroll", () => {
        const currentY = window.scrollY;

        if (firstScrollAfterLoad) {
          firstScrollAfterLoad = false;
          lastScrollY = currentY;
          return;
        }

        if (currentY < lastScrollY || currentY <= NAVBAR_HEIGHT) {
          showNavbar();
        } else if (
          currentY > lastScrollY &&
          !hoveringNavbar &&
          Date.now() > clickCooldown
        ) {
          navbar.classList.add("hidden");
        }

        lastScrollY = currentY;
      });

      navbar.addEventListener("mouseenter", () => {
        hoveringNavbar = true;
        showNavbar();
      });
      navbar.addEventListener("mouseleave", () => {
        hoveringNavbar = false;
      });

      document.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", () => {
          showNavbar();
          clickCooldown = Date.now() + 1000;
        });
      });

      document.addEventListener("click", (event) => {
        if (
          navbar.contains(event.target) ||
          event.target.closest("a, button, select, input, textarea, label")
        ) {
          return;
        }

        navbar.classList.toggle("hidden");
        clickCooldown = Date.now() + 600;
      });

      // ===== MOBILE HAMBURGER MENU =====
      const navToggle = document.querySelector(".nav-toggle");
      if (navToggle) {
        navToggle.addEventListener("click", (e) => {
          e.stopPropagation();
          const isOpen = navbar.classList.toggle("nav-open");
          navToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
        });

        // Close the menu when a nav link is tapped
        document.querySelectorAll(".navbar nav a").forEach((link) => {
          link.addEventListener("click", () => {
            navbar.classList.remove("nav-open");
            navToggle.setAttribute("aria-expanded", "false");
          });
        });

        // Close the menu when tapping outside the navbar
        document.addEventListener("click", (e) => {
          if (
            navbar.classList.contains("nav-open") &&
            !navbar.contains(e.target)
          ) {
            navbar.classList.remove("nav-open");
            navToggle.setAttribute("aria-expanded", "false");
          }
        });
      }
    </script>

    <!-- ================= JADWAL ANGKATAN NAVIGATION ================= -->
    <script>
      const angkatanSelect = document.getElementById("JK-angkatan");
      const lihatAngkatanBtn = document.getElementById("JK-lihat-angkatan");

      lihatAngkatanBtn.addEventListener("click", function (event) {
        event.preventDefault();

        if (!angkatanSelect.value) {
          alert("Silahkan pilih angkatan terlebih dahulu.");
          return;
        }

        window.location.href =
          "Jadwal_kelas.html?angkatan=" +
          encodeURIComponent(angkatanSelect.value);
      });
    </script>

    <!-- ================= AI CHAT SCROLL DETECTOR, MODAL TOGGLE & OLLAMA API INTEGRATION ================= -->
    <script>
      const chatWidget = document.getElementById("ai-chat-widget");
      const chatModal = document.getElementById("ai-chat-modal");
      const chatToggleBtn = document.getElementById("ai-chat-toggle");
      const chatCloseBtn = document.getElementById("chat-close");
      const chatTooltip = document.getElementById("ai-chat-tooltip");
      const tooltipCloseBtn = document.getElementById("tooltip-close");
      const heroSection = document.querySelector(".hero"); // Area Pendaftaran

      const chatInput = document.getElementById("chat-input");
      const chatSendBtn = document.getElementById("chat-send");
      const chatMessages = document.getElementById("chat-messages");

      // MASUKKAN URL NGROK ANDA DARI COLAB DI SINI
      const NGROK_ENDPOINT = "https://barrel-radiator-vanquish.ngrok-free.dev/api/generate";

      // Animasi muncul berangsur saat di-scroll ke area pendaftaran (.hero)
      window.addEventListener("scroll", () => {
        if (!heroSection) return;

        const heroRect = heroSection.getBoundingClientRect();
        const windowHeight = window.innerHeight;

        // Widget muncul saat seksi pendaftaran terlihat di layar
        if (heroRect.top <= windowHeight - 100) {
          chatWidget.classList.add("visible");
        } else {
          chatWidget.classList.remove("visible");
          chatModal.classList.add("hidden");
        }
      });

      // Buka / Tutup Modal Chat
      chatToggleBtn.addEventListener("click", () => {
        chatModal.classList.toggle("hidden");
        // Sembunyikan balon tooltip jika jendela chat sedang dibuka
        if (!chatModal.classList.contains("hidden")) {
          chatTooltip.classList.add("dismissed");
        }
      });

      chatCloseBtn.addEventListener("click", () => {
        chatModal.classList.add("hidden");
      });

      // Tutup balon bicara jika tombol (X) pada balon diklik
      tooltipCloseBtn.addEventListener("click", () => {
        chatTooltip.classList.add("dismissed");
      });

      // ================= FUNGSI INTEGRASI OLLAMA API =================
      async function sendMessage() {
        const messageText = chatInput.value.trim();
        if (!messageText) return;

        // 1. Tampilkan pesan pengguna di UI Chat
        const userMsgDiv = document.createElement("div");
        userMsgDiv.className = "message user";
        userMsgDiv.textContent = messageText;
        chatMessages.appendChild(userMsgDiv);

        chatInput.value = "";
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // 2. Tampilkan indikator loading (Bot mengetik)
        const botMsgDiv = document.createElement("div");
        botMsgDiv.className = "message bot";
        botMsgDiv.innerHTML = "<i>Sedang mengetik...</i>";
        chatMessages.appendChild(botMsgDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        try {
          // 3. Request POST ke Ollama API melalui Ngrok
          const response = await fetch(NGROK_ENDPOINT, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "ngrok-skip-browser-warning": "true"
            },
            body: JSON.stringify({
              model: "llama3.2:1b",
              prompt: messageText,
              stream: false
            })
          });

          if (response.ok) {
            const data = await response.json();
            let aiReply = data.response;

            // Jika pertanyaan berkaitan dengan jadwal, lampirkan gambar jadwal
            const keywords = ["jadwal", "jam", "hari", "kapan", "sesi", "bentrok"];
            if (keywords.some(kw => messageText.toLowerCase().includes(kw))) {
              aiReply += `<br><br><b>📸 Tabel Operasional Jadwal Lab:</b><br><img src='Leptek_LandingPages/Jadwal.png' alt='Jadwal LEPTEK' style='max-width:100%; border-radius:8px; margin-top:8px; border:1px solid #444;'>`;
            }

            botMsgDiv.innerHTML = aiReply;
          } else {
            botMsgDiv.textContent = "Maaf, terjadi kesalahan saat menghubungi server AI.";
          }
        } catch (error) {
          console.error(error);
          botMsgDiv.textContent = "Gagal terhubung ke AI. Pastikan server Ngrok di Colab sedang aktif.";
        }

        chatMessages.scrollTop = chatMessages.scrollHeight;
      }

      // Event listener tombol Kirim & Enter Key
      chatSendBtn.addEventListener("click", sendMessage);
      chatInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
          sendMessage();
        }
      });
    </script>
  </body>
</html>