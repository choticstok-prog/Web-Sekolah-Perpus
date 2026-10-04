<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with complete school library SMA/SMK subject textbooks.
     */
    public function run(): void
    {
        // 1. Admin Utama / Admin Besar Perpustakaan Sekolah
        $admin = User::updateOrCreate(
            ['email' => 'choticstok@gmail.com'],
            [
                'name' => 'Riky (Admin Besar)',
                'password' => 'RikyAja',
                'role' => 'Admin Besar',
                'phone' => '085158965665',
                'address' => 'Rumah Riky',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@sekolah.test'],
            [
                'name' => 'Ibu Rahmawati, S.Pd (Pustakawan)',
                'password' => 'password',
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Ruang Perpustakaan SMA/SMK Cendekia Bangsa Lantai 2',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@peminjaman.test'],
            [
                'name' => 'Admin Petugas Perpustakaan',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'address' => 'Ruang Pengelola Perpustakaan Sekolah',
            ]
        );

        // 2. Siswa / Anggota Perpustakaan Sekolah
        $siswa1 = User::firstOrCreate(
            ['email' => 'siswa@sekolah.test'],
            [
                'name' => 'Riky Hidayat (Kelas XI)',
                'password' => bcrypt('password'),
                'role' => 'peminjam',
                'phone' => '089876543210',
                'address' => 'NISN: 0065432109 - Siswa Kelas XI SMA/SMK Cendekia',
            ]
        );

        $siswa2 = User::firstOrCreate(
            ['email' => 'user@peminjaman.test'],
            [
                'name' => 'Riky Siswa (Kelas XI)',
                'password' => bcrypt('password'),
                'role' => 'peminjam',
                'phone' => '089876543210',
                'address' => 'NISN: 0065432109 - Siswa Kelas XI SMA/SMK Cendekia',
            ]
        );

        // 3. Kategori Mata Pelajaran SMA/SMK
        $categoriesData = [
            [
                'name' => 'Sejarah Indonesia',
                'slug' => 'sejarah-indonesia',
                'description' => 'Buku teks resmi Sejarah Indonesia SMA/SMK Kelas 10, 11, dan 12 kurikulum nasional Kemendikbud.',
            ],
            [
                'name' => 'Matematika',
                'slug' => 'matematika',
                'description' => 'Buku teks Matematika Wajib & Peminatan SMA/SMK Kelas 10, 11, dan 12 untuk pemodelan aljabar, kalkulus, dan geometri.',
            ],
            [
                'name' => 'Bahasa Indonesia',
                'slug' => 'bahasa-indonesia',
                'description' => 'Buku teks Bahasa Indonesia SMA/SMK Kelas 10, 11, dan 12 untuk literasi teks, kebahasaan, dan penulisan ilmiah.',
            ],
            [
                'name' => 'Bahasa Inggris',
                'slug' => 'bahasa-inggris',
                'description' => 'Buku teks Bahasa Inggris SMA/SMK Kelas 10, 11, dan 12 untuk kecakapan komunikasi global (Listening, Speaking, Reading, Writing).',
            ],
            [
                'name' => 'Pendidikan Agama Islam (PAI)',
                'slug' => 'pendidikan-agama-islam',
                'description' => 'Buku pelajaran Pendidikan Agama Islam dan Budi Pekerti SMA/SMK Kelas 10, 11, dan 12 pembina akhlak mulia dan ketakwaan.',
            ],
            [
                'name' => 'Pendidikan Pancasila (PPKn)',
                'slug' => 'pendidikan-pancasila-ppkn',
                'description' => 'Buku teks PPKn SMA/SMK penguat wawasan kebangsaan, konstitusi UUD 1945, hukum, dan hak asasi manusia.',
            ],
            [
                'name' => 'Prakarya & Kewirausahaan (PKWU)',
                'slug' => 'prakarya-kewirausahaan',
                'description' => 'Buku teks PKWU SMA/SMK pengembang kreativitas kerajinan, rekayasa produk, budidaya, dan bisnis wirausaha.',
            ],
            [
                'name' => 'Pendidikan Jasmani (PJOK)',
                'slug' => 'pjok',
                'description' => 'Buku teks PJOK SMA/SMK pembina kebugaran fisik, teknik cabang olahraga, sportivitas, dan pola hidup sehat.',
            ],
            [
                'name' => 'Seni Budaya',
                'slug' => 'seni-budaya',
                'description' => 'Buku pelajaran Seni Budaya SMA/SMK bidang Seni Rupa, Musik, Tari, dan Seni Teater Nusantara.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $categories[$cData['slug']] = Category::updateOrCreate(
                ['slug' => $cData['slug']],
                $cData
            );
        }

        // 4. Koleksi 27 Buku Pelajaran SMA/SMK Lengkap (Kelas X, XI, XII)
        // Disusun lengkap dengan Sinopsis, Rangkuman Pembahasan Bab, dan Poin-Poin Penting Tiap Bab
        $booksData = [
            // ================= SEJARAH INDONESIA =================
            [
                'category_id' => $categories['sejarah-indonesia']->id,
                'title' => 'Sejarah Indonesia SMA/SMK Kelas X',
                'author' => 'Restu Gunawan, Amurwani Dwi Lestariningsih, Sardiman',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-119-0',
                'stock' => 16,
                'available_stock' => 15,
                'description' => "Sinopsis Buku:\nBuku Sejarah Indonesia Kelas X menyajikan perjalanan peradaban kepulauan Indonesia sejak masa praaksara hingga berkembangnya pengaruh kebudayaan dan kerajaan Hindu-Buddha serta Islam.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Menelusuri Peradaban Awal di Kepulauan Indonesia\n• Membahas: Asal-usul nenek moyang bangsa Indonesia, kehidupan zaman praaksara dari berburu dan meramu hingga bercocok tanam dan perundagian, serta temuan fosil manusia purba di Sangiran dan Trinil.\n• Poin Penting: Fosil Meganthropus paleojavanicus, Pithecanthropus erectus, Homo soloensis; pembagian zaman batu (Paleolitikum, Mesolitikum, Neolitikum, Megalitikum); migrasi bangsa Proto Melayu dan Deutro Melayu; peninggalan kapak persegi, kapak lonjong, nekara, dan menhir.\n\nBab 2: Pedagang, Penguasa dan Pujangga pada Masa Klasik Hindu-Buddha\n• Membahas: Masuk dan berkembangnya kebudayaan Hindu-Buddha melalui jalur sutra maritim, perkembangan kerajaan-kerajaan maritim dan agraris, serta akulturasi kebudayaan nusantara.\n• Poin Penting: Teori masuknya Hindu-Buddha (Brahmana, Ksatria, Waisya, Sudra, Arus Balik); Kerajaan Kutai (Yupa), Tarumanegara (Prasasti Ciaruteun), Sriwijaya sebagai pusat maritim dan keagamaan Buddha, serta kejayaan Majapahit di bawah Hayam Wuruk dan Patih Gajah Mada (Sumpah Palapa).\n\nBab 3: Islamisasi dan Silang Budaya di Nusantara\n• Membahas: Saluran penyebaran Islam melalui perdagangan, perkawinan, dakwah pesantren, tasawuf, dan kesenian; munculnya kesultanan Islam di pesisir dan pedalaman nusantara; serta integrasi budaya nusantara.\n• Poin Penting: Teori masuknya Islam (Gujarat, Mekkah, Persia); peran Walisongo di Pulau Jawa; Kesultanan Samudera Pasai, Demak, Mataram Islam, Banten, Aceh Darussalam, Gowa-Tallo, serta Ternate dan Tidore; tradisi silang budaya arsitektur masjid beratap tumpang dan seni kaligrafi.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Sejarah-Indonesia-Kelas-X',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20X%20Sejarah%20BS%20press.pdf',
                'cover_image' => '/images/covers/sejarah-indonesia-kelas-x.png',
            ],
            [
                'category_id' => $categories['sejarah-indonesia']->id,
                'title' => 'Sejarah Indonesia SMA/SMK Kelas XI',
                'author' => 'Sardiman AM, Amurwani Dwi Lestariningsih',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-122-0',
                'stock' => 18,
                'available_stock' => 17,
                'description' => "Sinopsis Buku:\nBuku Sejarah Indonesia Kelas XI mengulas masa kolonialisme bangsa Barat di Nusantara, perlawanan rakyat nusantara di berbagai daerah, tumbuhnya kesadaran kebangsaan, hingga masa pendudukan militer Jepang.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Antara Kolonialisme dan Imperialisme Barat\n• Membahas: Latar belakang kedatangan bangsa Eropa ke Nusantara pasca jatuhnya Konstantinopel, perburuan rempah-rempah, motivasi 3G (Gold, Glory, Gospel), serta awal monopoli VOC.\n• Poin Penting: Penjelajahan samudra bangsa Portugis, Spanyol, dan Belanda; berdirinya VOC 1602 dengan hak istimewa Octrooi; politik adu domba Devide et Impera; serta kebijakan tanam paksa (Cultuurstelsel) oleh Van den Bosch.\n\nBab 2: Perang Melawan Kolonialisme dan Imperialisme\n• Membahas: Perlawanan bersenjata rakyat di berbagai daerah nusantara sebelum abad ke-20 dalam menentang penindasan penjajah Belanda dan VOC.\n• Poin Penting: Perang Maluku dipimpin Pattimura, Perang Padri dipimpin Tuanku Imam Bonjol, Perang Diponegoro (1825-1830) dengan strategi perang gerilya, Perang Banjar oleh Pangeran Antasari, Perang Jagaraga Bali oleh I Gusti Ketut Jelantik, serta Perang Aceh oleh Teuku Umar dan Cut Nyak Dien.\n\nBab 3: Membangun Jati Diri Keindonesiaan dan Pergerakan Nasional\n• Membahas: Transformasi strategi perjuangan dari kedaerahan fisik menjadi pergerakan modern berbasis organisasi, pendidikan, dan persatuan kebangsaan.\n• Poin Penting: Politik Etis (Edukasi, Irigasi, Emigrasi); lahirnya Budi Utomo 20 Mei 1908 (Hari Kebangkitan Nasional); Sarekat Islam, Indische Partij; Kongres Pemuda II yang melahirkan Sumpah Pemuda 28 Oktober 1928; serta lagu Indonesia Raya ciptaan W.R. Soepratman.\n\nBab 4: Tirani Matahari Terbit dan Masa Pendudukan Jepang\n• Membahas: Masuknya militer Dai Nippon ke Indonesia tahun 1942, propaganda gerakan 3A, eksploitasi ekonomi dan tenaga kerja perang, serta jalan menuju kemerdekaan.\n• Poin Penting: Penyerahan Kalijati 1942; penderitaan kerja paksa Romusha; pembentukan organisasi semi-militer (PETA, Heiho, Seinendan); pembentukan BPUPKI dan PPKI; serta Peristiwa Rengasdengklok yang mempercepat Proklamasi Kemerdekaan.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Sejarah-Indonesia-Semester-1-Kelas-XI',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Sejarah%20Sm1%20Kelas%20XI%20BS%20press.pdf',
                'cover_image' => '/images/covers/sejarah-indonesia-semester-1-kelas-xi.png',
            ],
            [
                'category_id' => $categories['sejarah-indonesia']->id,
                'title' => 'Sejarah Indonesia SMA/SMK Kelas XII',
                'author' => 'Abdurakhman, Arif Pradono, Linda Sunarti, Susanto Zuhdi',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2018,
                'isbn' => '978-602-427-125-1',
                'stock' => 20,
                'available_stock' => 19,
                'description' => "Sinopsis Buku:\nBuku Sejarah Indonesia Kelas XII membahas perjuangan mempertahankan kedaulatan negara dari ancaman disintegrasi pascakemerdekaan, dinamika ketatanegaraan Indonesia, hingga peran aktif bangsa di panggung dunia internasional.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Perjuangan Menghadapi Ancaman Disintegrasi Bangsa (1948-1965)\n• Membahas: Berbagai pergolakan dan pemberontakan bersenjata yang mengancam keutuhan NKRI pasca kemerdekaan, dikelompokkan berdasarkan ideologi, kepentingan, dan sistem pemerintahan.\n• Poin Penting: Pemberontakan PKI Madiun 1948 dipimpin Musso; pemberontakan DI/TII di Jawa Barat, Jawa Tengah, Aceh, Sulsel, dan Kalsel; pemberontakan APRA, Andi Azis, dan RMS; pergolakan PRRI dan Permesta; serta tragedi G30S/PKI 1965.\n\nBab 2: Sistem dan Struktur Politik-Ekonomi Masa Demokrasi Parlementer (1950-1959)\n• Membahas: Penerapan sistem multi-partai dan kabinet parlementer pasca pengakuan kedaulatan, dinamika jatuh bangunnya kabinet, serta upaya perbaikan ekonomi nasional.\n• Poin Penting: Jatuh bangun 7 kabinet (Natsir, Sukiman, Wilopo, Ali I, Burhanuddin Harahap, Ali II, Djuanda); Pemilu pertama 1955; Deklarasi Djuanda 1957 yang menetapkan batas wilayah laut kepulauan Indonesia; serta kegagalan Dewan Konstituante merumuskan UUD baru.\n\nBab 3: Sistem dan Struktur Politik-Ekonomi Masa Demokrasi Terpimpin (1959-1965)\n• Membahas: Kembalinya UUD 1945 melalui Dekrit Presiden, pemusatan kekuasaan pada Presiden Soekarno, politik luar negeri konfrontatif, dan pembebasan Irian Barat.\n• Poin Penting: Dekrit Presiden 5 Juli 1959; Operasi Trikora dan pembebasan Irian Barat (Persetujuan New York 1962, Pepera 1969); Dwikora dan konfrontasi Malaysia; politik poros Jakarta-Peking; serta hiperinflasi ekonomi di akhir masa Demokrasi Terpimpin.\n\nBab 4: Masa Orde Baru (1966-1998)\n• Membahas: Peralihan kekuasaan pasca Supersemar, pemulihan stabilitas politik, pembangunan berencana (Pelita), dan integrasi Timor Timur.\n• Poin Penting: Supersemar 1966; trilogi pembangunan dan Repelita; fusi partai politik 1973; swasembada pangan beras 1984; krisis moneter Asia 1997 dan gerakan reformasi mahasiswa Mei 1998 yang mengakhiri era Orde Baru.\n\nBab 5: Era Reformasi dan Kiprah Diplomasi Indonesia\n• Membahas: Gelombang demokratisasi, amandemen UUD 1945, desentralisasi otonomi daerah, serta kontribusi diplomasi perdamaian Indonesia di tingkat regional dan global.\n• Poin Penting: BJ Habibie, Gus Dur, Megawati, SBY, hingga Jokowi; amandemen UUD 1945 sebanyak 4 kali (1999-2002); kebebasan pers dan pembentukan KPK; serta peran aktif Indonesia di ASEAN, PBB (Pasukan Garuda), dan G20.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Sejarah-Indonesia-Kelas-XII',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20XII%20Sejarah%20BS%20press.pdf',
                'cover_image' => '/images/covers/sejarah-indonesia-kelas-xii.png',
            ],

            // ================= MATEMATIKA =================
            [
                'category_id' => $categories['matematika']->id,
                'title' => 'Matematika SMA/SMK Kelas X',
                'author' => 'Bornok Sinaga, Pardomuan N.J.M Sinambela, Andri Kristianto Sitanggang, Tri Andri Hutapea, Sudianto Manulang',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-115-2',
                'stock' => 22,
                'available_stock' => 22,
                'description' => "Sinopsis Buku:\nBuku Matematika Kelas X menyajikan fondasi konsep penalaran logis, simbolis, dan pemodelan matematis untuk memecahkan persoalan dunia nyata bagi siswa SMA/SMK.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Persamaan dan Pertidaksamaan Nilai Mutlak Linear Satu Variabel\n• Membahas: Definisi matematis nilai mutlak sebagai jarak non-negatif, sifat-sifat nilai mutlak, serta prosedur aljabar menyelesaikan persamaan dan pertidaksamaan linier satu variabel berpangkat satu.\n• Poin Penting: Konsep |x| = x jika x >= 0 dan |x| = -x jika x < 0; sifat perkalian |ab| = |a||b|; serta metode kuadrat kedua ruas dan metode grafik garis bilangan.\n\nBab 2: Sistem Persamaan Linear Tiga Variabel (SPLTV)\n• Membahas: Pemodelan masalah kontekstual ekonomi dan sains menjadi sistem linier tiga peubah x, y, dan z serta langkah penyelesaian sistematis.\n• Poin Penting: Metode Eliminasi, Substitusi, dan Campuran (Eliminasi-Substitusi); analisis jenis solusi (solusi tunggal, banyak solusi, atau tidak ada solusi/inkonsisten).\n\nBab 3: Relasi dan Fungsi Aljabar\n• Membahas: Konsep pemetaan daerah asal (domain), kawan (kodomain), dan hasil (range); operasi aljabar fungsi, fungsi komposisi (f o g)(x), dan fungsi invers f^-1(x).\n• Poin Penting: Syarat suatu fungsi memiliki invers (harus bijektif); rumus komposisi (f o g)(x) = f(g(x)); serta menentukan rumus invers fungsi pecahan linier f(x) = (ax + b)/(cx + d).\n\nBab 4: Trigonometri Dasar\n• Membahas: Konsep ukuran sudut derajat dan radian, perbandingan sisi segitiga siku-siku, sudut istimewa di 4 kuadran, aturan sinus-cosinus, dan identitas trigonometri.\n• Poin Penting: Definisi sin = depan/miring, cos = samping/miring, tan = depan/samping; nilai sudut istimewa 0, 30, 45, 60, 90 derajat; rumus identitas sin^2(x) + cos^2(x) = 1; serta aturan Sinus a/sin(A) = b/sin(B) dan aturan Cosinus a^2 = b^2 + c^2 - 2bc*cos(A).",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Matematika-Kelas-X',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20X%20Matematika%20BS%20press.pdf',
                'cover_image' => '/images/covers/matematika-kelas-x.png',
            ],
            [
                'category_id' => $categories['matematika']->id,
                'title' => 'Matematika SMA/SMK Kelas XI',
                'author' => 'Sudianto Manullang, Andri Kristianto S., Tri Andri Hutapea, Lasker Pangarapan Sinaga, Bornok Sinaga',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-114-5',
                'stock' => 20,
                'available_stock' => 20,
                'description' => "Sinopsis Buku:\nBuku Matematika Kelas XI memfasilitasi penguatan kemampuan analisis kuantitatif tingkat menengah untuk siswa SMA/SMK pada materi aljabar matriks, geometri, hingga kalkulus diferensial.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Induksi Matematika\n• Membahas: Metode pembuktian deduktif matematis untuk menguji kebenaran formula deret bilangan asli, keterbagian aljabar, dan ketidaksamaan.\n• Poin Penting: Dua langkah utama induksi: Langkah Basis (buktikan benar untuk n = 1) dan Langkah Induksi (asumsikan benar untuk n = k, lalu buktikan benar untuk n = k + 1).\n\nBab 2: Program Linear Dua Variabel\n• Membahas: Optimalisasi alokasi sumber daya dengan memodelkan kendala pertidaksamaan linier serta menentukan nilai maksimum atau minimum fungsi sasaran/objektif.\n• Poin Penting: Daerah Bersih Himpunan Penyelesaian (DHP); metode uji titik pojok (ekstrem); serta metode garis selidik ax + by = k.\n\nBab 3: Matriks dan Operasinya\n• Membahas: Susunan bilangan dalam baris dan kolom, operasi penjumlahan, perkalian skalar, perkalian dua matriks, determinan, dan invers matriks ordo 2x2 dan 3x3.\n• Poin Penting: Syarat perkalian matriks (kolom A = baris B); determinan det(A) = ad - bc; rumus invers A^-1 = (1/det(A)) * Adj(A); serta sifat matriks singular det(A) = 0 yang tidak memiliki invers.\n\nBab 4: Transformasi Geometri\n• Membahas: Perubahan posisi dan ukuran bangun geometri pada bidang koordinat Cartesius menggunakan representasi matriks transformasi.\n• Poin Penting: Translasi (pergeseran koordinat); Refleksi (pencerminan sumbu X, Y, y=x, titik asal); Rotasi (perputaran sudut theta terhadap pusat O atau P); dan Dilatasi (perbesaran/perkecilan skala k).\n\nBab 5: Barisan dan Deret Bilangan\n• Membahas: Pola bilangan aritmatika dengan beda tetap dan geometri dengan rasio tetap, jumlah n suku pertama, serta deret geometri tak hingga konvergen.\n• Poin Penting: Rumus suku ke-n aritmatika Un = a + (n-1)b dan jumlah Sn = n/2(2a + (n-1)b); rumus geometri Un = a*r^(n-1); serta deret tak hingga konvergen S_inf = a / (1 - r) untuk -1 < r < 1.\n\nBab 6: Limit Fungsi dan Turunan Pertama\n• Membahas: Konsep pendekatan nilai limit fungsi aljabar x mendekati c, bentuk tak tentu 0/0, serta definisi turunan fungsi f'(x) dan aturan diferensial dasar.\n• Poin Penting: Metode substitusi, pemfaktoran, dan perkalian akar sekawan; turunan fungsi f(x) = ax^n menjadi f'(x) = n*a*x^(n-1); turunan perkalian (uv)' = u'v + uv'; serta aplikasi gradien garis singgung kurva m = f'(x).",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Matematika-Kelas-XI',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Matematika%20Kelas%20XI%20BS%20press.pdf',
                'cover_image' => '/images/covers/matematika-kelas-xi.png',
            ],
            [
                'category_id' => $categories['matematika']->id,
                'title' => 'Matematika SMA/SMK Kelas XII',
                'author' => 'Abdur Rahman As’ari, Tjang Daniel Chandra, Ipung Yuwono, Lathiful Anwar, Syaiful Hamzah Nasution',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2018,
                'isbn' => '978-602-427-117-6',
                'stock' => 25,
                'available_stock' => 24,
                'description' => "Sinopsis Buku:\nBuku Matematika Kelas XII memfokuskan pembelajaran pada dimensi spasial tiga dimensi, pengolahan statistika data kelompok, serta teori probabilitas pencacahan dan peluang kejadian majemuk.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Geometri Ruang Dimensi Tiga\n• Membahas: Kedudukan dan jarak antara elemen ruang: jarak titik ke titik, jarak titik ke garis, dan jarak titik ke bidang pada bangun kubus, balok, dan limas teratur.\n• Poin Penting: Teorema Pythagoras pada segitiga ruang; proyeksi tegak lurus titik terhadap bidang; diagonal sisi s*sqrt(2) dan diagonal ruang kubus s*sqrt(3); serta sudut antara garis dan bidang.\n\nBab 2: Statistika Data Kelompok\n• Membahas: Penyajian data distribusi frekuensi dalam bentuk histogram, poligon, dan ogive; perhitungan ukuran pemusatan dan ukuran penyebaran data berkelompok.\n• Poin Penting: Mean data kelompok x_bar = sum(fi*xi)/sum(fi); Median Me = Tb + ((n/2 - Fk)/fi)*p; Modus Mo = Tb + (d1/(d1+d2))*p; serta Ragam (Varians) dan Simpangan Baku (Standar Deviasi) s = sqrt(varians).\n\nBab 3: Kaidah Pencacahan\n• Membahas: Prinsip dasar membilang banyaknya kemungkinan susunan: aturan penjumlahan, aturan perkalian, notasi faktorial, permutasi urutan berbeda, dan kombinasi pemilihan bebas.\n• Poin Penting: Aturan perkalian m x n; permutasi nPr = n! / (n - r)! (memperhatikan urutan, misal juara 1, 2, 3); permutasi siklis (n - 1)!; dan kombinasi nCr = n! / (r! * (n - r)!) (tanpa memperhatikan urutan, misal memilih anggota tim).\n\nBab 4: Peluang Kejadian Majemuk\n• Membahas: Ruang sampel S, peluang suatu kejadian tunggal P(A) = n(A)/n(S), serta hukum peluang gabungan dua kejadian atau lebih.\n• Poin Penting: Peluang saling lepas P(A U B) = P(A) + P(B); peluang tidak saling lepas P(A U B) = P(A) + P(B) - P(A n B); peluang saling bebas P(A n B) = P(A) * P(B); serta peluang bersyarat P(A|B) = P(A n B) / P(B).",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Matematika-Kelas-XII',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20XII%20Matematika%20BS%20press.pdf',
                'cover_image' => '/images/covers/matematika-kelas-xii.png',
            ],

            // ================= BAHASA INDONESIA =================
            [
                'category_id' => $categories['bahasa-indonesia']->id,
                'title' => 'Bahasa Indonesia SMA/SMK Kelas X',
                'author' => 'Suherli, Maman Suryaman, Aji Septiaji, Istiqomah',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-099-5',
                'stock' => 18,
                'available_stock' => 18,
                'description' => "Sinopsis Buku:\nBuku Bahasa Indonesia Kelas X memandu siswa menguasai kompetensi berbahasa berbasis genre teks autentik, analitis, kritis, dan komunikatif dalam kehidupan bermasyarakat.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Menyusun Laporan Hasil Observasi (LHO)\n• Membahas: Pengamatan objektif fakta lingkungan ilmiah, struktur teks LHO, serta kaidah kebahasaan kalimat definisi, deskripsi, verba, dan nomina.\n• Poin Penting: Struktur LHO terdiri dari Pernyataan Umum/Klasifikasi, Deskripsi Bagian, dan Deskripsi Manfaat; menggunakan fakta konkret tanpa opini subjektif.\n\nBab 2: Mengembangkan Pendapat dalam Teks Eksposisi\n• Membahas: Menyampaikan gagasan argumentatif yang meyakinkan pembaca disertai data pendukung yang valid dan rekomendasi logis.\n• Poin Penting: Struktur Tesis (pernyataan pendapat), Argumentasi (alasan & bukti pendukung), dan Penegasan Ulang; pemakaian kata teknis, konjungsi sebab-akibat, dan kata persuasif.\n\nBab 3: Menyampaikan Ide Melalui Anekdot Kritis\n• Membahas: Cerita lucu berbalut sindiran halus konstruktif terhadap fenomena sosial, layanan publik, atau kebijakan pemerintah.\n• Poin Penting: Struktur Abstraksi, Orientasi, Krisis, Reaksi, dan Koda; membedakan humor murni tanpa pesan sosial dengan teks anekdot yang sarat kritik sosial bermakna.\n\nBab 4: Melestarikan Nilai Kearifan Lokal Melalui Cerita Rakyat (Hikayat)\n• Membahas: Nilai moral, religius, sosial, dan edukasi dalam karya sastra klasik Melayu/Nusantara serta transformasinya menjadi cerpen modern.\n• Poin Penting: Ciri hikayat (anonim, istanasentris, kesaktian tokoh, kemustahilan peristiwa, dan bahasa arkais); analisis unsur intrinsik tema, alur, perwatakan, dan sudut pandang.\n\nBab 5: Membuat Kesepakatan Melalui Teks Negosiasi\n• Membahas: Seni mencapai mufakat win-win solution saat terjadi perbedaan kepentingan melalui dialog persuasif yang santun.\n• Poin Penting: Struktur Orientasi, Pengajuan, Penawaran, Persetujuan, dan Penutup; kaidah bahasa santun, pronomina persona, kalimat deklaratif, dan kalimat interogatif santun.\n\nBab 6: Berdebat dengan Logis dan Santun\n• Membahas: Menyusun argumen pro (afirmasi) dan kontra (oposisi) terhadap mosi perdebatan terkini dengan berpijak pada fakta empiris dan etika berbicara.\n• Poin Penting: Peran tim afirmasi, tim oposisi, tim netral, dan moderator; struktur penyampaian argumen A-R-E-L (Assertion, Reasoning, Evidence, Linkback).\n\nBab 7: Belajar dari Biografi Tokoh Inspiratif\n• Membahas: Meneladani karakter unggul dan perjuangan hidup tokoh nasional atau dunia melalui analisis narasi biografis perjalanan hidupnya.\n• Poin Penting: Struktur Orientasi (latar belakang tokoh), Peristiwa dan Masalah (rintangan dan pencapaian), serta Reorientasi (kesimpulan pandangan penulis).",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Bahasa-Indonesia-Kelas-X',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20X%20Bahasa%20Indonesia%20BS%20press.pdf',
                'cover_image' => '/images/covers/bahasa-indonesia-kelas-x.png',
            ],
            [
                'category_id' => $categories['bahasa-indonesia']->id,
                'title' => 'Bahasa Indonesia SMA/SMK Kelas XI',
                'author' => 'Suherli, Maman Suyarman, Aji septiaji, Istiqomah',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-098-8',
                'stock' => 16,
                'available_stock' => 16,
                'description' => "Sinopsis Buku:\nBuku Bahasa Indonesia Kelas XI mendalami analisis prosedur operasional, eksplanasi fenomena alam-sosial, ceramah kebangsaan, karya ilmiah baku, resensi apresiatif, hingga naskah drama panggung.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Menyusun Prosedur Kompleks\n• Membahas: Menjelaskan tahapan kerja instruksional yang sistematis, logis, dan mudah dipahami pengguna untuk melakukan suatu kegiatan atau mengoperasikan alat.\n• Poin Penting: Struktur Tujuan, Material/Alat, Langkah-langkah, dan Penegasan Ulang; kaidah kalimat imperatif, verba material, kata bilangan urutan, dan konjungsi temporal.\n\nBab 2: Mempelajari Teks Eksplanasi Fenomena\n• Membahas: Penjelasan ilmiah proses terjadinya peristiwa alam (gempa bumi, gerhana) atau fenomena sosial budaya secara kronologis dan kausalitas.\n• Poin Penting: Struktur Pernyataan Umum, Rangkaian Penjelas (Sebab-Akibat), dan Interpretasi; penggunaan konjungsi kausalitas (sebab, karena, akibatnya) dan konjungsi kronologis (kemudian, setelah itu).\n\nBab 3: Mengelola Informasi dalam Ceramah Publik\n• Membahas: Menyampaikan pesan edukasi, nasihat, atau ajakan persuasif kepada khalayak ramai dengan teknik retorika santun dan intonasi menarik.\n• Poin Penting: Struktur Pembuka (Tesis/Salam), Isi (Rangkaian Argumen), dan Penutup (Ajakan/Simpulan); penggunaan kata sapaan santun, kata ganti orang pertama, dan kalimat persuasif.\n\nBab 4: Meneladani Kehidupan Melalui Cerpen\n• Membahas: Menganalisis pergulatan batin manusia dan konflik sosial yang tersirat dalam cerita pendek Indonesia kontemporer.\n• Poin Penting: Unsur intrinsik (tema, alur maju/mundur/campuran, penokohan analitik/dramatik, latar waktu/tempat/suasana, amanat) dan unsur ekstrinsik (nilai budaya, agama, latar belakang pengarang).\n\nBab 5: Mempersiapkan Proposal Kegiatan dan Penelitian\n• Membahas: Menyusun rancangan usulan program formal (pentas seni, bakti sosial) atau usulan riset ilmiah sederhana untuk mendapatkan persetujuan dan sponsor.\n• Poin Penting: Sistematika proposal: Latar Belakang, Nama Kegiatan/Judul Penelitian, Tujuan, Ruang Lingkup, Susunan Panitia/Peneliti, Rencana Anggaran Biaya (RAB), dan Jadwal Pelaksanaan.\n\nBab 6: Merancang Karya Ilmiah Sistematika Baku\n• Membahas: Penulisan laporan penelitian formal berlandaskan metode ilmiah, kejelasan data, dan ragam bahasa formal bebas dari ambiguitas.\n• Poin Penting: Struktur Bab I Pendahuluan (Rumusan Masalah, Hipotesis), Bab II Kajian Pustaka, Bab III Metodologi, Bab IV Hasil & Pembahasan, Bab V Penutup; penulisan kutipan dan daftar pustaka APA/MLA.\n\nBab 7: Menilai Karya Sastra Melalui Resensi dan Drama\n• Membahas: Memberikan ulasan objektif keunggulan dan kelemahan buku/novel serta mementaskan naskah lakon drama panggung sekolah.\n• Poin Penting: Unsur resensi (identitas buku, sinopsis, analisis kelebihan-kekurangan, rekomendasi pembaca); unsur pementasan drama (naskah lakon, sutradara, aktor, tata panggung/lampu, kostum).",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Buku-bahasa-Indonesia-kelas-XI',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/B%20Indonesia%20Kelas%20XI%20BS%20press.pdf',
                'cover_image' => '/images/covers/buku-bahasa-indonesia-kelas-xi.png',
            ],
            [
                'category_id' => $categories['bahasa-indonesia']->id,
                'title' => 'Bahasa Indonesia SMA/SMK Kelas XII',
                'author' => 'Maman Suryaman, Suherli, Istiqomah',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2018,
                'isbn' => '978-602-427-101-5',
                'stock' => 20,
                'available_stock' => 20,
                'description' => "Sinopsis Buku:\nBuku Bahasa Indonesia Kelas XII mempersiapkan siswa menghadapi dunia kerja profesional, seleksi perguruan tinggi, serta literasi analisis media massa dan kritik karya sastra.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Menulis Surat Lamaran Pekerjaan dan CV\n• Membahas: Tata cara penyusunan surat lamaran formal profesional, penyesuaian kualifikasi dengan lowongan kerja, etika korespondensi, dan CV modern.\n• Poin Penting: Sistematika surat lamaran: Tempat dan Tanggal, Lampiran, Hal, Alamat Tujuan, Salam Pembuka, Paragraf Pembuka (Sumber Info Lowongan), Kualifikasi Diri, Paragraf Penutup, Salam Penutup, Tanda Tangan & Nama Terang; penggunaan bahasa baku EYD.\n\nBab 2: Menikmati Cerita Sejarah Novel Fiksi Historis\n• Membahas: Karya novel sastra yang mengangkat latar peristiwa sejarah nyata nusantara yang diperkaya imajinasi kreatif sastrawan.\n• Poin Penting: Membedakan fakta sejarah murni dengan rekaan fiksi novelis; struktur orientasi, pengungkapan peristiwa, menuju konflik (rising action), klimaks (komplikasi), resolusi, dan koda.\n\nBab 3: Memahami Isu Terkini Melalui Teks Editorial\n• Membahas: Tajuk rencana resmi dewan redaksi media massa yang menyatakan sikap, pandangan kritis, dan solusi terhadap isu publik yang sedang hangat.\n• Poin Penting: Struktur Pengenalan Isu, Penyampaian Argumen, dan Penegasan/Rekomendasi; memisahkan kalimat fakta (terbukti benar) dengan kalimat opini (penilaian, saran, harapan).\n\nBab 4: Menganalisis Struktur dan Gaya Bahasa Novel\n• Membahas: Eksplorasi estetika karya novel melalui alur cerita kompleks, keterkaitan tokoh, perwatakan, dan majas gaya bahasa simbolik.\n• Poin Penting: Analisis gaya bahasa (metafora, personifikasi, hiperbola, ironi); sudut pandang pencerita (orang pertama 'aku' atau orang ketiga 'dia/mereka'); serta nilai-nilai kehidupan (moral, sosial, estetika).\n\nBab 5: Menyajikan Gagasan Melalui Artikel Opini Media\n• Membahas: Penulisan esai opini berbobot yang layak dipublikasikan di surat kabar cetak atau media digital dengan argumentasi bernas dan solutif.\n• Poin Penting: Pola penyusunan artikel: Judul Menarik, Lead Pembuka yang Memikat, Pembahasan Masalah, Data/Bukti Pendukung, dan Penutup Kesimpulan Solutif; kaidah bahasa lugas dan efektif.\n\nBab 6: Menilai Karya Melalui Kritik Sastra dan Esai\n• Membahas: Menghasilkan ulasan kritis terhadap karya sastra secara objektif serta menulis esai reflektif berdasarkan sudut pandang pribadi penulis.\n• Poin Penting: Perbedaan kritik sastra (penilaian objektif berbasis teori sastra) dan esai (pandangan subjektif yang luwes terhadap suatu objek); prinsip kejujuran akademis dan apresiasi karya seni.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Bahasa-Indonesia-Kelas-XII',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20XII%20Bahasa%20Indonesia%20BS%20press.pdf',
                'cover_image' => '/images/covers/bahasa-indonesia-kelas-xii.png',
            ],

            // ================= BAHASA INGGRIS =================
            [
                'category_id' => $categories['bahasa-inggris']->id,
                'title' => 'Bahasa Inggris SMA/SMK Kelas X',
                'author' => 'Utami Widiati, Zuliati Rohmah, Furaidah',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-107-7',
                'stock' => 18,
                'available_stock' => 18,
                'description' => "Sinopsis Buku:\nBuku Bahasa Inggris Kelas X melatih siswa mengekspresikan percakapan interpersonal, pemahaman teks deskriptif tempat bersejarah, teks pengumuman, biografi tokoh, dan dongeng legenda.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nChapter 1: Talking About Self & Family Identity\n• Membahas: Memperkenalkan diri sendiri, hobi, dan anggota keluarga dalam situasi formal maupun informal kepada teman baru (pen-pals).\n• Poin Penting: Pronouns: Subjective (I, you, he), Objective (me, you, him), Possessive Adjective (my, your, his), and Possessive Pronoun (mine, yours, his); Simple Present Tense expressions.\n\nChapter 2: Complimenting and Showing Care\n• Membahas: Mengucapkan pujian tulus atas penampilan atau prestasi orang lain serta menunjukkan empati dan simpati saat orang lain menghadapi musibah.\n• Poin Penting: Expressions of Complimenting: 'What a nice dress!', 'You did a great job!'; Expressions of Showing Care: 'I am sorry to hear that', 'Are you feeling better?'.\n\nChapter 3: Expressing Intention and Future Plans\n• Membahas: Menyampaikan rencana, niat, atau kegiatan yang akan dilakukan di masa depan bersama rekan sebaya.\n• Poin Penting: Penggunaan modal 'Be going to + V1' (rencana pasti) vs 'Would like to + V1' (keinginan/harapan); pola kalimat tanya 'What are you going to do this weekend?'.\n\nChapter 4: Which One is Your Best Getaway? (Descriptive Texts)\n• Membahas: Menulis dan mendeskripsikan keindahan tempat wisata alam dan situs bersejarah dunia (Tanjung Puting National Park, Niagara Falls).\n• Poin Penting: Struktur Identification (mengenalkan objek) dan Description (rincian ciri fisik objek); penggunaan Adjective phrases, Simple Present Tense, dan sensory imagery.\n\nChapter 5: Giving Announcements\n• Membahas: Memahami dan membuat pengumuman tertulis formal mengenai kegiatan sekolah, pembatalan acara, atau perlombaan.\n• Poin Penting: Struktur Head/Title, Date & Time, Location, Main Message, dan Contact Person; bahasa singkat, jelas, dan menggunakan kalimat instruksional imperatif.\n\nChapter 6: Recounting Past Historical Events & Figures\n• Membahas: Menceritakan kembali peristiwa masa lampau secara berurutan dan riwayat hidup tokoh berpengaruh (B.J. Habibie, Cut Nyak Dien).\n• Poin Penting: Struktur Orientation, Events (kronologis urutan waktu), dan Re-orientation; tata bahasa Simple Past Tense (V2) dan time connectives (first, then, afterward).\n\nChapter 7: Narrative Legends (Folklore)\n• Membahas: Menikmati cerita rakyat nusantara dan mancanegara (The Legend of Malin Kundang, Issumboshi) serta memetik pesan moral luhur di dalamnya.\n• Poin Penting: Struktur Orientation, Complication, Resolution, dan Coda; penggunaan Action verbs in Past Tense, Adverbs of time ('Once upon a time'), dan Direct/Indirect Speech.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Bahasa-Inggris-Kelas-X',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20X%20Bahasa%20Inggris%20BS%20press.pdf',
                'cover_image' => '/images/covers/bahasa-inggris-kelas-x.png',
            ],
            [
                'category_id' => $categories['bahasa-inggris']->id,
                'title' => 'Bahasa Inggris SMA/SMK Kelas XI',
                'author' => 'Mahrukh Bashir',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-106-0',
                'stock' => 15,
                'available_stock' => 15,
                'description' => "Sinopsis Buku:\nBuku Bahasa Inggris Kelas XI memperdalam pemikiran analitis siswa lewat pertukaran opini formal, teks eksplanasi sebab akibat fenomena alam, surat pribadi, serta pemaknaan lagu dan puisi.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nChapter 1: Can I Help You? (Offers and Suggestions)\n• Membahas: Tata krama menawarkan bantuan, memberikan saran perbaikan, serta cara menerima atau menolak tawaran dengan sopan.\n• Poin Penting: Suggestions: 'Why don't we...', 'You should...'; Offers: 'May I help you?', 'Can I give you a hand?'; Responding: 'Yes, please', 'No, thanks, I can manage'.\n\nChapter 2: Opinions and Thoughts\n• Membahas: Mengekspresikan pendapat, menyetujui, dan menyanggah pandangan orang lain mengenai isu kontemporer secara santun dan logis.\n• Poin Penting: Giving opinions: 'In my humble opinion...', 'I strongly believe that...'; Agreeing: 'I couldn't agree more'; Disagreeing: 'I see your point, but...'.\n\nChapter 3: Formal Invitations\n• Membahas: Menyusun surat undangan resmi untuk acara wisuda, seminar, atau pertemuan organisasi sesuai standar internasional.\n• Poin Penting: Format undangan resmi: Host Name, Request Phrase ('request the pleasure of your company'), Event Purpose, Date & Time, Venue, RSVP, dan Dress Code.\n\nChapter 4: Writing Personal Letters\n• Membahas: Komunikasi tertulis akrab kepada sahabat karib atau keluarga untuk menceritakan pengalaman pribadi atau kabar terbaru.\n• Poin Penting: Struktur Heading (Address & Date), Salutation ('Dear Sarah'), Introduction, Body Paragraphs, Closure, Complimentary Close ('With love'), and Signature.\n\nChapter 5: Cause and Effect Relationships\n• Membahas: Menghubungkan sebab timbulnya suatu peristiwa dengan dampak yang dihasilkan menggunakan kata penghubung kausalitas yang tepat.\n• Poin Penting: Cause and effect signal words diikuti noun: 'because of', 'due to', 'thanks to'; signal words diikuti klausa kalimat: 'because', 'as', 'since', 'consequently', 'therefore'.\n\nChapter 6: Meaning Through Music and Poetry\n• Membahas: Mengapresiasi pesan emosional, kritik sosial, dan keindahan bahasa kiasan dalam lirik lagu dan puisi berbahasa Inggris.\n• Poin Penting: Menganalisis tema lirik, figurative language (metaphor, simile, personification), rhyming words, dan moral message bagi pendengar.\n\nChapter 7: Explanation Texts on Natural & Social Phenomena\n• Membahas: Menjelaskan urutan proses terjadinya peristiwa ilmiah (tsunami, gempa tektonik, efek rumah kaca) secara runtut dan objektif.\n• Poin Penting: Struktur General Statement, Sequenced Explanation (How and Why it happens), dan Concluding Remarks; penggunaan Passive Voice dan Action Verbs.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Bahasa-Inggris-Kelas-XI',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/B%20Inggris%20Kelas%20XI%20BS%20press.pdf',
                'cover_image' => '/images/covers/bahasa-inggris-kelas-xi.png',
            ],
            [
                'category_id' => $categories['bahasa-inggris']->id,
                'title' => 'Bahasa Inggris SMA/SMK Kelas XII',
                'author' => 'Utami Widiati, Zuliati Rohmah, Furaidah',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2018,
                'isbn' => '978-602-427-109-1',
                'stock' => 20,
                'available_stock' => 20,
                'description' => "Sinopsis Buku:\nBuku Bahasa Inggris Kelas XII membekali siswa keterampilan bahasa Inggris formal tingkat lanjut untuk dunia kerja global, jurnalistik media berita, caption media sosial, serta petunjuk manual alat teknis.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nChapter 1: May I Help You? (Advanced Service & Hospitality)\n• Membahas: Komunikasi tingkat lanjut dalam memberikan pelayanan prima profesional kepada tamu atau klien bisnis.\n• Poin Penting: Penggunaan modal formal 'Would you like me to...', 'Shall I...'; ekspresi keramahan di perhotelan, bandara, dan kantor korporat.\n\nChapter 2: Why Don't You Visit Seattle? (Conditional Suggestions)\n• Membahas: Memberikan rekomendasi bersyarat menggunakan kalimat pengandaian (conditional sentences) diikuti imperatif atau saran.\n• Poin Penting: Pola 'If clause + imperative': 'If you visit Seattle, tour the Space Needle!'; pola 'If clause + suggestion': 'If you want to pass, you should study hard'.\n\nChapter 3: Creating Captions on Media\n• Membahas: Menyusun teks deskripsi singkat (caption) foto jurnalistik atau media sosial yang informatif, menarik, dan sesuai etika pers.\n• Poin Penting: Format caption: Who is in the picture, What is happening, When it happened, Where it happened, and Credit/Photographer name; concise and clear wording.\n\nChapter 4: Writing Job Application Letters and Resume/CV\n• Membahas: Penulisan surat lamaran kerja formal dalam bahasa Inggris beserta resume riwayat hidup yang menarik bagi HRD perusahaan multinasional.\n• Poin Penting: Struktur Applicant Address, Date, Employer Address, Salutation, Opening paragraph, Highlighting Skills & Experiences, Interview request, and Enclosure of CV/portfolio.\n\nChapter 5: Who Was Involved? (News Item Texts)\n• Membahas: Menelaah struktur artikel berita media cetak dan siaran pers internasional mengenai peristiwa penting terkini.\n• Poin Penting: Struktur Newsworthy Event (inti peristiwa), Background Events (kronologi lengkap dan latar belakang), serta Sources (pernyataan saksi/ahli/pejabat); Headline drafting.\n\nChapter 6: Manuals and User Instructions\n• Membahas: Membaca dan membuat teks prosedur panduan pengoperasian perangkat elektronik dan keselamatan kerja teknis.\n• Poin Penting: Kosakata teknis instruksional: 'Ensure that...', 'Do not plug...', 'Rotate clockwise'; urutan langkah kerja berpoin dengan ikon diagram keselamatan.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Bahasa-Inggris-Kelas-XII',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20XII%20Bahasa%20Inggris%20BS%20press.pdf',
                'cover_image' => '/images/covers/bahasa-inggris-kelas-xii.png',
            ],

            // ================= PAI =================
            [
                'category_id' => $categories['pendidikan-agama-islam']->id,
                'title' => 'PAI dan Budi Pekerti SMA/SMK Kelas X',
                'author' => 'Nelty Khairiyah, Endi Suhendi Zen',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-043-8',
                'stock' => 18,
                'available_stock' => 18,
                'description' => "Sinopsis Buku:\nBuku PAI dan Budi Pekerti Kelas X membimbing siswa memperkuat keimanan melalui Asmaul Husna, adab berpakaian syar'i, kejujuran pribadi, serta sejarah dakwah Rasulullah SAW di Makkah dan Madinah.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Meneladani Asmaul Husna dalam Kehidupan\n• Membahas: Memahami makna dan mengamalkan nilai ketuhanan dari 7 Asmaul Husna pilihan Allah SWT dalam perilaku sehari-hari.\n• Poin Penting: Makna Al-Karim (Maha Mulia), Al-Mu'min (Maha Memberi Keamanan), Al-Wakil (Maha Memelihara), Al-Matin (Maha Kokoh), Al-Jami' (Maha Mengumpulkan), Al-'Adl (Maha Adil), dan Al-Akhir (Maha Akhir).\n\nBab 2: Berbusana Muslim dan Muslimah Cermin Kepribadian\n• Membahas: Batasan aurat pria dan wanita menurut Al-Qur'an dan Sunnah serta hikmah menjaga kesucian diri melalui pakaian sopan.\n• Poin Penting: Landasan Surah Al-Ahzab: 59 dan An-Nur: 31; fungsi pakaian sebagai penutup aurat, pelindung tubuh, dan perhiasan ibadah; larangan tabarruj dan pakaian syuhrah.\n\nBab 3: Menjunjung Tinggi Kejujuran dalam Segala Aspek\n• Membahas: Menanamkan integritas kejujuran (shiddiq) dalam ucapan, niat, dan tindakan di lingkungan sekolah, keluarga, dan masyarakat.\n• Poin Penting: Bahaya nifaq (kemunafikan); 3 tanda orang munafik (bila berkata dusta, bila berjanji ingkar, bila dipercaya berkhianat); serta keutamaan kejujuran membawa ketenangan hati.\n\nBab 4: Al-Qur'an dan Hadis sebagai Pedoman Hidup\n• Membahas: Kedudukan Al-Qur'an sebagai sumber hukum Islam primer dan Hadis sebagai penjelas (bayan), dilengkapi ijma' dan qiyas.\n• Poin Penting: Fungsi hadis terhadap Al-Qur'an: Bayan Ta'rir (menguatkan), Bayan Tafsir (merinci ayat umum), dan Bayan Tasyri' (menetapkan hukum baru yang belum ada di Al-Qur'an).\n\nBab 5: Meneladani Dakwah Rasulullah SAW di Makkah dan Madinah\n• Membahas: Strategi perjuangan dakwah Nabi Muhammad SAW mulai dari dakwah sembunyi-sembunyi, terang-terangan di Makkah, hingga pembangunan piagam Madinah.\n• Poin Penting: Ketabahan Assabiqunal Awwalun; hijrah ke Habasyah dan Thaif; Piagam Madinah (persaudaraan Muhajirin-Anshar dan toleransi lintas suku/agama); serta Fathu Makkah.\n\nBab 6: Fiqih Muamalah: Zakat, Wakaf, dan Asuransi Syariah\n• Membahas: Hukum pengelolaan harta sosial keagamaan untuk mengangkat kesejahteraan umat dan prinsip transaksi keuangan syariah tanpa riba.\n• Poin Penting: Syarat dan rukun wakaf (Wakif, Mauquf 'alaih, Mauquf bih, Sighat); larangan riba, maysir (judi), dan gharar (ketidakjelasan); serta prinsip tolong menolong (ta'awun) asuransi syariah.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Pendidikan-Agama-Islam-Dan-Budi-Pekerti-Kelas-X',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%2010%20Islam%20BS%20press.pdf',
                'cover_image' => '/images/covers/pendidikan-agama-islam-dan-budi-pekerti-kelas-x.png',
            ],
            [
                'category_id' => $categories['pendidikan-agama-islam']->id,
                'title' => 'PAI dan Budi Pekerti SMA/SMK Kelas XI',
                'author' => 'Mustahdi, Mustakim',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-044-5',
                'stock' => 17,
                'available_stock' => 17,
                'description' => "Sinopsis Buku:\nBuku PAI dan Budi Pekerti Kelas XI mendalami ketauhidan pada kitab-kitab Allah, kepedulian sosial, etos kerja, tata cara fardhu kifayah perawatan jenazah, dan peradaban Islam masa keemasan.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Beriman kepada Kitab-Kitab Suci Allah SWT\n• Membahas: Mengimani wahyu Allah SWT yang diturunkan kepada para rasul dan meyakini kedudukan Al-Qur'an sebagai penyempurna kitab-kitab terdahulu.\n• Poin Penting: Kitab Taurat (Nabi Musa AS), Zabur (Nabi Daud AS), Injil (Nabi Isa AS), dan Al-Qur'an (Nabi Muhammad SAW); perbedaan kitab (berisi syariat lengkap) dengan suhuf (lembaran wahyu pendek).\n\nBab 2: Berani Hidup Jujur, Adil, dan Bertanggung Jawab (Syaja'ah)\n• Membahas: Makna keberanian menegakkan kebenaran (syaja'ah) dalam menghadapi ketidakadilan dan godaan penyimpangan moral.\n• Poin Penting: Syaja'ah harbiyah (keberanian di medan perang) dan syaja'ah nafsiyah (keberanian mengendalikan hawa nafsu dan mengakui kesalahan); landasan Surah Al-Ma'idah: 8.\n\nBab 3: Melaksanakan Fardhu Kifayah Pengurusan Jenazah\n• Membahas: Rukun, syarat, dan tata cara memuliakan jenazah muslim sesuai tuntunan sunnah Rasulullah SAW secara berurutan.\n• Poin Penting: 4 kewajiban umat Islam: Memandikan jenazah, Mengafani (3 lapis untuk laki-laki, 5 lapis untuk perempuan), Menyalatkan (4 takbir tanpa ruku' dan sujud), dan Memakamkan jenazah.\n\nBab 4: Sampaikan Dariku Walau Satu Ayat (Khutbah, Tabligh, Dakwah)\n• Membahas: Perbedaan, rukun, dan etika menyampaikan ajaran Islam secara lisan melalui mimbar khutbah, ceramah tabligh, maupun dakwah keteladanan bil hal.\n• Poin Penting: Rukun khutbah Jumat (memuji Allah, membaca shalawat, berwasiat taqwa, membaca ayat Al-Qur'an, dan mendoakan kaum mukmin); prinsip dakwah bil hikmah, mau'izhah hasanah, dan mujadalah bil ihsan.\n\nBab 5: Masa Kejayaan Islam di Panggung Peradaban Dunia\n• Membahas: Puncak kemajuan sains, kedokteran, filsafat, dan sastra masa Dinasti Abbasiyah di Baghdad dan Dinasti Umayyah di Cordoba (Andalusia).\n• Poin Penting: Lembaga Baitul Hikmah; tokoh ilmuwan muslim Ibnu Sina (Avicenna - Kedokteran), Al-Khawarizmi (Aljabar & Algoritma), Ibnu Rusyd (Averroes - Filsafat), dan Jabir bin Hayyan (Kimia).\n\nBab 6: Membangun Kerukunan dan Toleransi Antarumat Beragama\n• Membahas: Prinsip tasamuh (toleransi) dalam Islam tanpa mengorbankan aqidah, landasan Surah Al-Kafirun: 6 dan Yunus: 40-41.\n• Poin Penting: Menghormati perbedaan keyakinan dalam urusan sosial muamalah; larangan memaksa orang lain masuk agama; serta menjaga persatuan bangsa majemuk.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Pendidikan-Agama-Islam-Dan-Budi-Pekerti-kelas-XI',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%2011%20Islam%20BS%20press.pdf',
                'cover_image' => '/images/covers/pendidikan-agama-islam-dan-budi-pekerti-kelas-xi.png',
            ],
            [
                'category_id' => $categories['pendidikan-agama-islam']->id,
                'title' => 'PAI dan Budi Pekerti SMA/SMK Kelas XII',
                'author' => 'HA. Sholeh Dimyathi, Feisal Ghozali',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2018,
                'isbn' => '978-602-427-045-2',
                'stock' => 20,
                'available_stock' => 20,
                'description' => "Sinopsis Buku:\nBuku PAI dan Budi Pekerti Kelas XII membekali siswa dengan pemahaman berpikir kritis ayat alam semesta, takdir Ilahi, hukum munakahat (pernikahan), waris (mawaris), dan sejarah islamisasi nusantara.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Berpikir Kritis dan Demokratis (Surah Ali 'Imran: 190-191 & 159)\n• Membahas: Perintah merenungi penciptaan langit dan bumi sebagai tanda kekuasaan Allah (Ulul Albab) serta prinsip musyawarah mufakat dalam bermasyarakat.\n• Poin Penting: Karakter Ulul Albab senantiasa mengingat Allah dalam kondisi berdiri, duduk, dan berbaring; adab musyawarah: bersikap lemah lembut, memaafkan, dan bertawakal kepada Allah.\n\nBab 2: Meyakini Hari Akhir (Kiamat) dan Hikmahnya\n• Membahas: Tanda-tanda kiamat sughra (kecil) dan kubra (besar), fase kehidupan alam barzakh, yaumul ba'ats, mizan, hisab, hingga surga dan neraka.\n• Poin Penting: Dalil Al-Qur'an tentang kehancuran alam semesta; pengaruh iman pada hari akhir melahirkan kehati-hatian dalam bertindak, etos ibadah, dan optimisme amal saleh.\n\nBab 3: Berpikir Positif kepada Qada dan Qadar Allah SWT\n• Membahas: Rukun iman keenam mengenai takdir mubram (ketetapan mutlak yang tidak bisa diubah) dan takdir mu'allaq (ketetapan yang terkait ikhtiar dan doa manusia).\n• Poin Penting: Sikap orang beriman menghadapi takdir: Ikhtiar maksimal, Berdoa khusyuk, dan Tawakal menerima ketetapan Allah; menjauhkan sifat putus asa (ananiyah) dan takabur.\n\nBab 4: Hukum Pernikahan dalam Islam (Munakahat)\n• Membahas: Membina keluarga sakinah, mawaddah, wa rahmah sesuai syariat Islam, hak dan kewajiban suami-istri, serta ketentuan thalaq, iddah, dan rujuk.\n• Poin Penting: Rukun nikah: Calon mempelai pria dan wanita, Wali nikah, Dua orang saksi adil, dan Ijab Qabul; mahar perkawinan; masa tunggu (iddah) wanita; serta larangan pernikahan sepersusuan.\n\nBab 5: Hukum Waris Islam (Mawaris)\n• Membahas: Pembagian harta peninggalan jenazah secara adil berdasarkan Al-Qur'an Surah An-Nisa: 11-12 untuk mencegah perselisihan antarahli waris.\n• Poin Penting: Hak-hak yang harus ditunaikan sebelum bagi waris (biaya jenazah, hutang, wasiat maks 1/3); golongan ahli waris Ashabah (penghabisan sisa) dan Dzawil Furudh (bagian pasti 1/2, 1/4, 1/8, 2/3, 1/3, 1/6).\n\nBab 6: Rahmat Islam bagi Nusantara dan Dunia\n• Membahas: Jejak islamisasi damai di kepulauan Indonesia, peran dakwah Walisongo, berdirinya pesantren tradisional, dan kearifan akulturasi budaya lokal.\n• Poin Penting: Saluran dakwah seni Sunan Kalijaga (wayang kulit dan tembang Ilir-Ilir); kerajaan Islam pertama Samudera Pasai; serta nilai rahmatan lil 'alamin yang menjiwai karakter bangsa Indonesia.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Pendidikan-Agama-Islam-Dan-Budi-Pekerti-Kelas-XII',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%2012%20Islam%20BS%20press.pdf',
                'cover_image' => '/images/covers/pendidikan-agama-islam-dan-budi-pekerti-kelas-xii.png',
            ],

            // ================= PPKN =================
            [
                'category_id' => $categories['pendidikan-pancasila-ppkn']->id,
                'title' => 'PPKn SMA/SMK Kelas X',
                'author' => 'Nuryadi, Tolib',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-091-9',
                'stock' => 15,
                'available_stock' => 15,
                'description' => "Sinopsis Buku:\nBuku PPKn Kelas X menanamkan nilai-nilai luhur Pancasila dalam praktik ketatanegaraan Indonesia, konstitusi UUD 1945, kewenangan lembaga negara, otonomi daerah, serta integrasi nasional Bhinneka Tunggal Ika.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Nilai-Nilai Pancasila dalam Penyelenggaraan Pemerintahan\n• Membahas: Pembagian kekuasaan negara secara horizontal dan vertikal, kedudukan kementerian negara Republik Indonesia, serta lembaga pemerintah non-kementerian.\n• Poin Penting: Konsep pembagian kekuasaan Trias Politika John Locke dan Montesquieu (Eksekutif, Legislatif, Yudikatif); implementasi sila 1 hingga sila 5 dalam pembuatan kebijakan publik.\n\nBab 2: Ketentuan UUD NRI 1945 dalam Kehidupan Berbangsa\n• Membahas: Wilayah kedaulatan NKRI, hak dan kewajiban warga negara, kemerdekaan beragama dan beribadah, serta sistem pertahanan keamanan negara.\n• Poin Penting: Batas laut Indonesia (Laut Teritorial 12 mil, Zona Tambahan, ZEE 200 mil, Landas Kontinen); asas kewarganegaraan Ius Sanguinis (keturunan) dan Ius Soli (tempat kelahiran); sistem Sishankamrata.\n\nBab 3: Kewenangan Lembaga-Lembaga Negara Menurut UUD NRI 1945\n• Membahas: Struktur suprastruktur politik (MPR, DPR, DPD, Presiden, MA, MK, KY, BPK) dan infrastruktur politik (partai politik, ormas, media massa, kelompok penekan).\n• Poin Penting: Fungsi DPR (Legislasi, Anggaran, Pengawasan); hak DPR (Interpelasi, Angket, Menyatakan Pendapat); wewenang MK menguji UU terhadap UUD 1945 dan memutus sengketa pemilu.\n\nBab 4: Hubungan Struktural dan Fungsional Pemerintah Pusat dan Daerah\n• Membahas: Asas otonomi daerah, desentralisasi, dekonsentrasi, tugas pembantuan, serta pembagian urusan pemerintahan absolut, konkuren, dan umum.\n• Poin Penting: Tujuan otonomi daerah meningkatkan pelayanan dan kesejahteraan rakyat; urusan pemerintahan absolut pusat (Politik Luar Negeri, Pertahanan, Keamanan, Yustisi, Moneter/Fiskal, Agama).\n\nBab 5: Integrasi Nasional dalam Bingkai Bhinneka Tunggal Ika\n• Membahas: Upaya merajut persatuan dan kesatuan bangsa di tengah kemajemukan suku, ras, bahasa, dan agama, serta faktor pendorong dan penghambatnya.\n• Poin Penting: Syarat integrasi nasional: norma ditaati, konsensus bersama, kebutuhan saling melengkapi; ancaman primordialisme, etnosentrisme, dan separatisme.\n\nBab 6: Ancaman terhadap Negara di Bidang IPOLEKSOSBUDHANKAM\n• Membahas: Mengidentifikasi ancaman militer dan non-militer terhadap kedaulatan NKRI serta strategi penanggulangannya.\n• Poin Penting: Ancaman di bidang Ideologi (komunisme, liberalisme), Politik (intimidasi, provokasi asing), Ekonomi (monopoli pasar), Sosial Budaya (konsumerisme, hedonisme), serta Pertahanan Keamanan (terorisme, agresi).",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Pendidikan-Pancasila-dan-Kewarganegaraan-Kelas-X',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20X%20PPKn%20BS%20press.pdf',
                'cover_image' => '/images/covers/pendidikan-pancasila-dan-kewarganegaraan-kelas-x.png',
            ],
            [
                'category_id' => $categories['pendidikan-pancasila-ppkn']->id,
                'title' => 'PPKn SMA/SMK Kelas XI',
                'author' => 'Yusnawan Lubis, Mohamad Sodeli',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-092-6',
                'stock' => 16,
                'available_stock' => 16,
                'description' => "Sinopsis Buku:\nBuku PPKn Kelas XI mengupas penegakan hak asasi manusia, dinamika demokrasi Pancasila, sistem hukum peradilan nasional, serta peran aktif Indonesia dalam perdamaian dunia.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Harmonisasi Hak dan Kewajiban Asasi Manusia\n• Membahas: Substansi hak asasi manusia dalam perspektif nilai dasar, instrumental, dan praksis Pancasila, serta penanganan kasus pelanggaran HAM berat.\n• Poin Penting: Landasan UU No. 39 Tahun 1999 tentang HAM; perbedaan pelanggaran HAM biasa dengan pelanggaran HAM berat (Genosida dan Kejahatan terhadap Kemanusiaan); peran Komnas HAM dan Pengadilan HAM.\n\nBab 2: Sistem dan Dinamika Demokrasi Pancasila\n• Membahas: Prinsip kedaulatan rakyat dari rakyat oleh rakyat untuk rakyat berlandaskan nilai-nilai ketuhanan, kemanusiaan, persatuan, dan musyawarah mufakat.\n• Poin Penting: 10 pilar demokrasi konstitusional Indonesia menurut UUD 1945; periodisasi demokrasi Indonesia (Demokrasi Parlementer 1945-1959, Demokrasi Terpimpin 1959-1965, Demokrasi Pancasila Orba 1965-1998, dan Era Reformasi 1998-sekarang).\n\nBab 3: Sistem Hukum dan Lembaga Peradilan di Indonesia\n• Membahas: Tata urutan peraturan perundang-undangan nasional, pembagian jenis hukum, dan tingkatan lembaga peradilan di bawah naungan Mahkamah Agung.\n• Poin Penting: Asas legalitas hukum (Equality before the law); 4 lingkungan peradilan: Peradilan Umum, Peradilan Agama, Peradilan Militer, dan Peradilan Tata Usaha Negara (PTUN); peran Mahkamah Konstitusi.\n\nBab 4: Peran Indonesia dalam Hubungan Internasional dan Perdamaian Dunia\n• Membahas: Politik luar negeri bebas aktif Indonesia, partisipasi dalam organisasi internasional (PBB, ASEAN, GNB), dan misi perdamaian Garuda.\n• Poin Penting: Makna bebas (tidak memihak blok barat/timur) dan aktif (aktif memelihara ketertiban dunia); Konferensi Asia Afrika (KAA) Bandung 1955; serta pembentukan ASEAN di Bangkok 1967.\n\nBab 5: Mewaspadai Ancaman terhadap Kedudukan NKRI\n• Membahas: Ancaman kontemporer non-militer berupa perang siber, berita hoaks pemecah belah bangsa, serta ancaman proxy war.\n• Poin Penting: Membangun ketahanan nasional di bidang Ideologi, Politik, Ekonomi, Sosial, Budaya, dan Pertahanan; pentingnya kesadaran bela negara setiap warga negara.\n\nBab 6: Memperkukuh Persatuan dan Kesatuan Bangsa\n• Membahas: Menjaga keutuhan wilayah kepulauan Indonesia dalam wadah Negara Kesatuan Republik Indonesia dari masa ke masa.\n• Poin Penting: Konsep geopolitik Wawasan Nusantara; perwujudan kepulauan nusantara sebagai satu kesatuan politik, ekonomi, sosial budaya, dan pertahanan keamanan.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Pendidikan-Pancasila-dan-Kewarganegaraan-Kelas-XI',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/PPKn%20Kelas%20XI%20BS%20press.pdf',
                'cover_image' => '/images/covers/pendidikan-pancasila-dan-kewarganegaraan-kelas-xi.png',
            ],
            [
                'category_id' => $categories['pendidikan-pancasila-ppkn']->id,
                'title' => 'PPKn SMA/SMK Kelas XII',
                'author' => 'Yusnawan Lubis, Mohamad Sodeli',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2018,
                'isbn' => '978-602-427-093-3',
                'stock' => 16,
                'available_stock' => 16,
                'description' => "Sinopsis Buku:\nBuku PPKn Kelas XII memperdalam pemahaman penegakan supremasi hukum, penanganan kasus pelanggaran hak & pengingkaran kewajiban warga negara, serta pengaruh kemajuan IPTEK di era globalisasi.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Pelanggaran Hak dan Pengingkaran Kewajiban Warga Negara\n• Membahas: Menyeimbangkan hak asasi warga negara dengan kewajiban konstitusional, akar penyebab pelanggaran, dan upaya pencegahan oleh negara.\n• Poin Penting: Hak konstitusional warga negara (Pasal 27, 28, 29, 30, 31 UUD 1945); contoh pengingkaran kewajiban (tidak membayar pajak, merusak fasilitas umum, melanggar rambu lalu lintas); sanksi hukum tegas dan edukasi karakter.\n\nBab 2: Perlindungan dan Penegakan Hukum Demi Keadilan\n• Membahas: Pentingnya penegakan hukum bagi kepastian dan ketertiban masyarakat serta peran sinergis 5 aparat penegak hukum di Indonesia.\n• Poin Penting: Peran Kepolisian (pemelihara kamtibmas dan penyidik), Kejaksaan (penuntut umum perkara pidana), Hakim (pemutus vonis pengadilan secara independen), Advokat (pemberi bantuan hukum), dan KPK (pemberantas tindak pidana korupsi).\n\nBab 3: Pengaruh Kemajuan IPTEK terhadap NKRI\n• Membahas: Dampak positif dan negatif perkembangan teknologi informasi, kecerdasan buatan, dan komunikasi terhadap kedaulatan bangsa.\n• Poin Penting: Dampak positif: efisiensi birokrasi e-government, kemudahan belajar digital; dampak negatif: individualisme, kejahatan siber, lunturnya nilai gotong royong; sikap selektif berlandaskan Pancasila.\n\nBab 4: Dinamika Persatuan dan Kesatuan Bangsa dalam Konteks NKRI\n• Membahas: Napak tilas bentuk negara kesatuan Indonesia dari era revolusi kemerdekaan, Republik Indonesia Serikat (RIS), era UUDS 1950, Orde Baru, hingga Reformasi.\n• Poin Penting: Pasal 1 ayat (1) UUD 1945: 'Negara Indonesia ialah Negara Kesatuan, yang berbentuk Republik'; komitmen tidak mengubah bentuk NKRI dalam amandemen UUD 1945; menjaga keutuhan Sabang sampai Merauke.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Pendidikan-Pancasila-Dan-Kewarganegaraan-Kelas-XII',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20XII%20PPKN%20BS%20press.pdf',
                'cover_image' => '/images/covers/pendidikan-pancasila-dan-kewarganegaraan-kelas-xii.png',
            ],

            // ================= PKWU =================
            [
                'category_id' => $categories['prakarya-kewirausahaan']->id,
                'title' => 'Prakarya dan Kewirausahaan SMA/SMK Kelas X',
                'author' => 'Hendriana Werdhaningsih, Alberta Haryudanti, Rinrin Jamrianti, Desta Wirmas',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-154-1',
                'stock' => 15,
                'available_stock' => 15,
                'description' => "Sinopsis Buku:\nBuku PKWU Kelas X menumbuhkan jiwa wirausaha muda melalui perancangan kerajinan kearifan lokal, teknologi transportasi logistik, budidaya tanaman pangan, dan pengolahan makanan awetan nabati.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Wirausaha Kerajinan dengan Inspirasi Budaya Nonbenda\n• Membahas: Menggali potensi cerita rakyat, mitos, tarian, dan pantun daerah menjadi produk kerajinan estetik bernilai jual tinggi.\n• Poin Penting: Tahapan perancangan: curah pendapat ide (brainstorming), membuat sketsa model, memilih material lokal (kayu, bambu, serat alam), membuat prototipe, dan pengemasan produk yang menarik.\n\nBab 2: Wirausaha Produk Teknologi Transportasi dan Logistik Sederhana\n• Membahas: Identifikasi permasalahan perpindahan barang di lingkungan sekitar dan perancangan alat angkut sederhana yang ergonomis.\n• Poin Penting: Analisis fungsi alat transportasi, pemilihan bahan kuat dan ringan, perhitungan biaya pokok produksi (HPP), serta uji coba kelayakan beban prototipe.\n\nBab 3: Wirausaha Budidaya Tanaman Pangan\n• Membahas: Peluang usaha agribisnis komoditas pangan pokok (padi, jagung, kedelai, ubi jalar) dari persiapan lahan hingga masa pascapanen.\n• Poin Penting: Standar budidaya GAP (Good Agricultural Practices): pemilihan benih unggul bersertifikat, pemupukan berimbang, pengendalian hama terpadu (PHT), serta perhitungan Break Even Point (BEP).\n\nBab 4: Wirausaha Pengolahan Makanan Awetan Berbahan Nabati\n• Membahas: Teknik pengawetan pangan hasil pertanian (manisan buah, asinan sayur, keripik buah, selai) agar memiliki masa simpan lebih lama.\n• Poin Penting: Metode pengawetan fisik (pengeringan, pembekuan) dan kimia (pemberian gula, garam, asam); kemasan kedap udara (vacuum packaging); serta perizinan edar pangan P-IRT.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Prakarya-dan-Kewirausahaan-Semester-1-Kelas-X',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20X%20Prakarya%20dan%20Kewirausahaan%20BS%20Sem%201%20press.pdf',
                'cover_image' => '/images/covers/prakarya-dan-kewirausahaan-semester-1-kelas-x.png',
            ],
            [
                'category_id' => $categories['prakarya-kewirausahaan']->id,
                'title' => 'Prakarya dan Kewirausahaan SMA/SMK Kelas XI',
                'author' => 'RR. Indah Setyowati, Wawat Naswati, Heatiningsih, Miftakhodin, Cahyadi, Dwi Ayu',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-156-5',
                'stock' => 16,
                'available_stock' => 16,
                'description' => "Sinopsis Buku:\nBuku PKWU Kelas XI memperluas wawasan produksi kreatif berbasis daur ulang limbah bangun datar, rekayasa konversi energi terbarukan, pembenihan ikan konsumsi, dan makanan khas daerah.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Wirausaha Kerajinan dari Bahan Limbah Bangun Datar\n• Membahas: Pemanfaatan limbah lembaran kertas, kardus, kain perca, dan plastik menjadi produk fungsional estetik bernilai ekonomi.\n• Poin Penting: Analisis SWOT (Strengths, Weaknesses, Opportunities, Threats); konsep 3R (Reduce, Reuse, Recycle); perhitungan BEP unit dan BEP rupiah; serta strategi bauran pemasaran 4P (Product, Price, Place, Promotion).\n\nBab 2: Wirausaha Rekayasa Sistem Konversi Energi\n• Membahas: Merancang prototipe alat pengubah energi ramah lingkungan (panel surya mini, kincir angin, generator mikrohidro sederhanan).\n• Poin Penting: Prinsip hukum kekekalan energi; komponen rangkaian kelistrikan dasar (inverter, baterai akumulator); serta keselamatan dan kesehatan kerja (K3) bengkel rekayasa.\n\nBab 3: Wirausaha Budidaya Pembenihan Ikan Konsumsi\n• Membahas: Peluang usaha pemijahan dan pembesaran benih ikan air tawar bernilai komersial tinggi (lele, nila, mas, gurami, patin).\n• Poin Penting: Ciri induk matang gonad; teknik pemijahan alami dan buatan; pemeliharaan kualitas air kolam (pH, suhu, oksigen terlarut); serta manajemen pakan pakan alami cacing sutra dan pelet.\n\nBab 4: Wirausaha Pengolahan Makanan Khas Asli Daerah\n• Membahas: Mengolah dan memasarkan kuliner tradisional warisan nusantara (rendang, pempek, gudeg, soto) dengan standar higienis modern.\n• Poin Penting: Teknik memasak simmering, braising, steaming; pemilihan bumbu rempah alami penyedap dan pengawet alami; serta inovasi kemasan modern siap saji (retort pouch).",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Prakarya-Semester-1-Kelas-XI',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Prakarya%20Sm1%20Kelas%20XI%20BS%20press.pdf',
                'cover_image' => '/images/covers/prakarya-semester-1-kelas-xi.png',
            ],
            [
                'category_id' => $categories['prakarya-kewirausahaan']->id,
                'title' => 'Prakarya dan Kewirausahaan SMA/SMK Kelas XII',
                'author' => 'Hendriana Werdhaningsih, Wawat Naswati, Desta Wirnas, Rinrin Jamriati',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2018,
                'isbn' => '978-602-427-158-9',
                'stock' => 14,
                'available_stock' => 14,
                'description' => "Sinopsis Buku:\nBuku PKWU Kelas XII membimbing siswa merancang proposal rencana bisnis (business plan) profesional berbasis pasar ekspor global, teknologi terapan, agribisnis unggas, dan modifikasi kuliner nusantara.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Wirausaha Kerajinan untuk Pasar Global\n• Membahas: Merancang produk kerajinan nusantara berorientasi ekspor yang memenuhi selera estetika dan standar mutu konsumen internasional.\n• Poin Penting: Riset tren pasar global; pemenuhan regulasi sertifikasi ramah lingkungan; kemasan berstandar ekspor tahan benturan; serta promosi digital via e-commerce global.\n\nBab 2: Wirausaha Rekayasa Peralatan Teknologi Terapan\n• Membahas: Membuat peralatan otomatisasi elektronik sederhana berbasis sensor untuk membantu pekerjaan rumah tangga atau industri UMKM.\n• Poin Penting: Pengenalan mikrokontroler (Arduino/sensor gerak PIR/LDR); pembuatan skema jalur PCB; perakitan casing pelindung; serta pengujian fungsi otomatisasi alat.\n\nBab 3: Wirausaha Budidaya Unggas Petelur dan Pedaging\n• Membahas: Manajemen peternakan ayam petelur (layer) dan pedaging (broiler) mulai dari bibit DOC hingga pemanenan hasil telur dan karkas daging.\n• Poin Penting: Sistem perkandangan tertutup (closed house) dan terbuka; program vaksinasi pencegahan flu burung; biosekuriti ketat; serta analisis kelayakan finansial Return on Investment (ROI).\n\nBab 4: Wirausaha Pengolahan Makanan Khas Modifikasi\n• Membahas: Inovasi memodifikasi resep, bentuk, dan kemasan makanan tradisional khas daerah menjadi produk kekinian yang disukai generasi muda.\n• Poin Penting: Modifikasi rasa (fusion food), perbaikan tekstur dan daya tahan; sertifikasi Halal MUI dan izin BPOM; serta strategi pemasaran konten video media sosial dan delivery online.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Prakarya-dan-Kewirausahaan-Kelas-XII',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20XII%20Prakarya%20BS%20press.pdf',
                'cover_image' => '/images/covers/prakarya-dan-kewirausahaan-kelas-xii.png',
            ],

            // ================= PJOK =================
            [
                'category_id' => $categories['pjok']->id,
                'title' => 'PJOK SMA/SMK Kelas X',
                'author' => 'Sudrajat Wiradihardja, Syarifudin',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-131-2',
                'stock' => 14,
                'available_stock' => 14,
                'description' => "Sinopsis Buku:\nBuku PJOK Kelas X mengarahkan siswa menguasai teknik fundamental cabang olahraga atletik, permainan beregu bola besar dan kecil, bela diri pencak silat, serta kebugaran jasmani terukur.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Keterampilan Gerak Permainan Bola Besar\n• Membahas: Teknik dasar dan koordinasi bermain sepak bola, bola voli, dan bola basket secara sportif dalam simulasi pertandingan beregu.\n• Poin Penting: Sepak bola: passing kaki bagian dalam/luar, dribbling, shooting ke gawang; Bola voli: passing bawah, passing atas, servis atas/bawah; Bola basket: chest pass, bounce pass, lay-up shoot.\n\nBab 2: Keterampilan Gerak Permainan Bola Kecil\n• Membahas: Keterampilan memukul, menangkis, dan pergerakan langkah kaki (footwork) pada cabang bulu tangkis dan tenis meja.\n• Poin Penting: Bulu tangkis: pegangan forehand/backhand grip, servis pendek/panjang, pukulan smash keras, dan drop shot tipuan; Tenis meja: push, drive, block, dan spin bola.\n\nBab 3: Keterampilan Gerak Cabang Atletik\n• Membahas: Nomor-nomor inti atletik: lari cepat jarak pendek (sprint), lompat jauh, dan tolak peluru.\n• Poin Penting: Lari sprint 100m: start jongkok (crouch start), fase akselerasi, dan teknik memasuki garis finis; Lompat jauh gaya melenting: awalan, tumpuan balok, melayang di udara, dan pendaratan; Tolak peluru gaya O'Brien (membelakangi).\n\nBab 4: Keterampilan Bela Diri Pencak Silat\n• Membahas: Sikap pasang, teknik hindaran, tangkisan, serta serangan pukulan dan tendangan dalam warisan bela diri nusantara.\n• Poin Penting: 8 arah mata angin kuda-kuda; teknik tangkisan dalam/luar; pukulan lurus dan bandul; tendangan lurus, sabit, dan tendangan T; serta nilai sportivitas dan budi pekerti pesilat.\n\nBab 5: Latihan Kebugaran Jasmani dan Komposisi Tubuh\n• Membahas: Mengukur dan melatih 4 komponen kebugaran: kekuatan otot, daya tahan kardiorespirasi, kelenturan, dan kelincahan.\n• Poin Penting: Latihan push-up/sit-up/pull-up untuk kekuatan; tes lari 12 menit (Cooper test) untuk daya tahan jantung-paru; tes sit and reach untuk kelenturan; serta shuttle run untuk kelincahan.\n\nBab 6: Pola Hidup Sehat dan Bahaya Pergaulan Bebas\n• Membahas: Membiasakan pola makan gizi seimbang, pencegahan penyakit menular, bahaya rokok, alkohol, dan narkotika (NAPZA) bagi masa depan remaja.\n• Poin Penting: Dampak zat adiktif bagi susunan saraf pusat; cara menolak ajakan negatif rekan sebaya; serta menjaga kesehatan reproduksi remaja.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Pendidikan-Jasmani-Olahraga-dan-Kesehatan-Kelas-X',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20X%20PJOK%20BS%20press.pdf',
                'cover_image' => '/images/covers/pendidikan-jasmani-olahraga-dan-kesehatan-kelas-x.png',
            ],
            [
                'category_id' => $categories['pjok']->id,
                'title' => 'PJOK SMA/SMK Kelas XI',
                'author' => 'Sumaryoto, Soni Nopembri',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-132-9',
                'stock' => 15,
                'available_stock' => 15,
                'description' => "Sinopsis Buku:\nBuku PJOK Kelas XI memandu siswa menganalisis strategi pola penyerangan dan pertahanan tim, taktik nomor atletik estafet, senam lantai ritmik, serta penanggulangan penyakit menular HIV/AIDS.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Menganalisis Taktik Permainan Bola Besar\n• Membahas: Pola formasi penyerangan dan pertahanan terpadu pada sepak bola (formasi 4-4-2 dan 4-3-3), bola voli (sistem 4Sm-2Su), dan pola zone defense bola basket.\n• Poin Penting: Analisis kelemahan lawan, rotasi posisi pemain di lapangan, transisi cepat dari bertahan ke menyerang (counter-attack), serta kepemimpinan kapten tim.\n\nBab 2: Menganalisis Taktik Permainan Bola Kecil\n• Membahas: Strategi pertandingan softball, bulu tangkis ganda (sistem berdampingan dan depan-belakang), serta teknik servis spin tenis meja.\n• Poin Penting: Pukulan bunt dan home run softball; taktik menekan lawan dengan smash bertubi-tubi pada bulu tangkis ganda; serta kontrol tempo bola.\n\nBab 3: Menganalisis Kategori Gerak Cabang Atletik\n• Membahas: Teknik pergantian tongkat lari estafet 4x100m dan 4x400m, lompat tinggi gaya Fosbury Flop, serta lempar lembing.\n• Poin Penting: Pergantian tongkat estafet visual (melihat) dan non-visual (tanpa melihat); titik tumpuan lompat tinggi melengkung melewati mistar; serta sudut lemparan lembing ideal 45 derajat.\n\nBab 4: Menganalisis Bela Diri Pencak Silat Tanding\n• Membahas: Taktik sambut tanding, variasi bantingan, kuncian, dan sapuan kaki untuk menjatuhkan lawan secara sah sesuai aturan IPSI.\n• Poin Penting: Nilai poin pukulan (1), tendangan (2), dan bantingan (3); larangan memukul leher/muka; serta ketenangan mental mengantisipasi serangan lawan.\n\nBab 5: Penyusunan Program Latihan Kebugaran Mandiri\n• Membahas: Merancang jadwal sirkuit latihan (circuit training) terukur untuk meningkatkan indeks kebugaran jasmani pribadi.\n• Poin Penting: Prinsip FITT (Frequency, Intensity, Time, Type); cara menghitung denyut nadi maksimal (DNM = 220 - usia) dan zona latihan aerobik (60-80% DNM).\n\nBab 6: Bahaya dan Pencegahan Penularan HIV/AIDS\n• Membahas: Pengetahuan ilmiah mengenai virus HIV, stadium penyakit AIDS, jalur penularan (darah, jarum suntik, seks bebas), serta cara pencegahannya.\n• Poin Penting: Mitos dan fakta HIV (tidak menular lewat jabat tangan atau makan bersama); pentingnya menjauhi perilaku berisiko; serta empati tanpa stigma terhadap ODHA.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/pendidikan-jasmani-olahraga-dan-kesehatan-kelas-xi',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/PJOK%20Kelas%20XI%20BS%20press.pdf',
                'cover_image' => '/images/covers/pendidikan-jasmani-olahraga-dan-kesehatan-kelas-xi.png',
            ],
            [
                'category_id' => $categories['pjok']->id,
                'title' => 'PJOK SMA/SMK Kelas XII',
                'author' => 'Soemaryoto, Soni Nopembri',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2018,
                'isbn' => '978-602-427-133-6',
                'stock' => 15,
                'available_stock' => 15,
                'description' => "Sinopsis Buku:\nBuku PJOK Kelas XII mengajarkan perancangan strategi turnamen olahraga sekolah, senam ketangkasan artistik, keselamatan aktivitas air (renang pertolongan), serta manajemen P3K gawat darurat.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Merancang Pola Penyerangan & Pertahanan Pertandingan Olahraga\n• Membahas: Menjadi pelatih dan manajer tim dalam menyusun taktik pertandingan resmi bola besar dan kecil serta aturan perwasitan.\n• Poin Penting: Simulasi turnamen sistem gugur dan sistem kompetisi penuh; kode isyarat wasit resmi; serta analisis statistik performa pemain.\n\nBab 2: Merancang Simulasi Perlombaan Atletik dan Bela Diri\n• Membahas: Manajemen kepanitiaan perlombaan atletik antar-kelas (class meeting) dan pertandingan pencak silat sekolah.\n• Poin Penting: Pembagian tugas juri lintasan, pencatat waktu (timer), dan panitia keamanan; penerapan protokol keselamatan atlet di lapangan.\n\nBab 3: Program Peningkatan Kebugaran Jasmani Terstruktur\n• Membahas: Menyusun periodisasi program latihan fisik jangka panjang untuk mempertahankan performa stamina tubuh menjelang ujian akhir.\n• Poin Penting: Evaluasi tingkat VO2Max dengan tes bleep test / lari multi-tahap; latihan beban progresif (progressive overload); serta pentingnya waktu pemulihan (recovery).\n\nBab 4: Senam Ketangkasan Alat dan Senam Ritmik Aerobik\n• Membahas: Rangkaian gerak senam lantai (lompat kangkang di atas peti lompat, roll depan/belakang) serta kreasi senam ritmik berirama musik.\n• Poin Penting: Keberanian, kelenturan tubuh, dan pendaratan kaki yang kokoh tanpa cedera; keselarasan gerak dengan ketukan tempo lagu.\n\nBab 5: Aktivitas Renang dan Penyelamatan Gawat Darurat di Air\n• Membahas: Teknik renang gaya bebas, dada, punggung, dan kupu-kupu serta keterampilan dasar penyelamatan korban tenggelam (water rescue).\n• Poin Penting: Penggunaan pelampung keselamatan; teknik mendekati korban tanpa panik; serta tindakan resusitasi jantung paru (CPR/RJP) saat korban tidak sadar.\n\nBab 6: Pertolongan Pertama pada Kecelakaan (P3K) dan Pola Makan Sehat\n• Membahas: Penanganan cedera olahraga umum (keseleo, kram otot, patah tulang, pingsan) dan prinsip nutrisi gizi seimbang atlet.\n• Poin Penting: Metode R.I.C.E (Rest, Ice, Compression, Elevation) untuk cedera sendi; pembalutan perban dan pembidaian; serta kebutuhan hidrasi air mineral harian.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Pendidikan-Jasmani-Olahraga-dan-Kesehatan-Kelas-XII',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20XII%20PJOK%20BS%20press.pdf',
                'cover_image' => '/images/covers/pendidikan-jasmani-olahraga-dan-kesehatan-kelas-xii.png',
            ],

            // ================= SENI BUDAYA =================
            [
                'category_id' => $categories['seni-budaya']->id,
                'title' => 'Seni Budaya SMA/SMK Kelas X',
                'author' => 'Zackaria Soetedja, Dewi Suryati, Milasari, Agus Supriatna',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-143-5',
                'stock' => 14,
                'available_stock' => 14,
                'description' => "Sinopsis Buku:\nBuku Seni Budaya Kelas X mengajak siswa mengeksplorasi estetika dan apresiasi karya seni rupa dua & tiga dimensi, ragam alat musik tradisional nusantara, gerak dasar tari kreasi, dan teater tradisional.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Berkarya Seni Rupa Dua Dimensi (2D)\n• Membahas: Unsur-unsur fisik seni rupa (garis, bidang, bentuk, tekstur, ruang, gelap-terang) dan prinsip penataannya (kesatuan, keseimbangan, ritme, proporsi).\n• Poin Penting: Eksplorasi media lukis cat air, kanvas, krayon, dan pensil grafis; teknik arsir, dussel, plakat, dan gradasi warna primer, sekunder, serta tersier.\n\nBab 2: Berkarya Seni Rupa Tiga Dimensi (3D)\n• Membahas: Karya seni yang memiliki volume panjang, lebar, dan tinggi (patung, gerabah keramik, anyaman bambu, arsitektur).\n• Poin Penting: Perbedaan seni rupa murni (pure art) dan seni rupa terapan (applied art); teknik butsir, pahat batu/kayu, cor cetak, dan teknik las perakitan.\n\nBab 3: Memahami Jenis dan Fungsi Alat Musik Tradisional Nusantara\n• Membahas: Pengelompokan instrumen musik etnis nusantara berdasarkan sumber bunyi (aerofon, kordofon, idiofon, membranofon, elektrofon) dan fungsinya.\n• Poin Penting: Gamelan Jawa/Bali, Angklung Sunda (bambu getar), Sasando Rote (petik dawai), Tifa Papua (tabuh membran), dan Kolintang Minahasa (bilah kayu); fungsi musik ritual, hiburan, dan komunikasi adat.\n\nBab 4: Eksplorasi Gerak Tari Tradisional Nusantara\n• Membahas: Konsep elemen dasar tari (ruang gerak, waktu, tenaga) dan nilai estetis simbolik tari klasik, kerakyatan, dan tari kreasi baru.\n• Poin Penting: 3 pilar tari: Wiraga (keterampilan gerak tubuh), Wirama (keselarasan irama musik pengiring), dan Wirasa (penghayatan ekspresi emosi); tata rias dan busana adat tari.\n\nBab 5: Konsep dan Prosedur Berkarya Teater Tradisional\n• Membahas: Mengapresiasi teater rakyat nusantara (Lenong Betawi, Ketoprak Jawa, Ludruk Jawa Timur, Randai Minangkabau) yang kental dengan improvisasi dan interaksi penonton.\n• Poin Penting: Ciri teater tradisional: panggung arena terbuka, bahasa daerah, iringan musik live, naskah tidak baku/lisan; tahapan olah tubuh, olah vokal, dan olah rasa bagi aktor pemula.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Seni-Budaya-Kelas-X-Semester-1',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20X%20Seni%20Budaya%20BS%20Sem%201%20press.pdf',
                'cover_image' => '/images/covers/seni-budaya-kelas-x-semester-1.png',
            ],
            [
                'category_id' => $categories['seni-budaya']->id,
                'title' => 'Seni Budaya SMA/SMK Kelas XI',
                'author' => 'Sem Cornelyoes Bangun, Siswandi, Tati Narawati, Jose Rizal Manua',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2017,
                'isbn' => '978-602-427-145-9',
                'stock' => 15,
                'available_stock' => 15,
                'description' => "Sinopsis Buku:\nBuku Seni Budaya Kelas XI melatih siswa merancang pameran galeri seni rupa sekolah, mengaransemen ansambel musik modern, mementaskan tari kontemporer, dan memproduksi teater naskah lakon.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Apresiasi Aliran dan Gaya Seni Rupa Murni\n• Membahas: Karakteristik aliran seni rupa representatif (Realisme, Naturalisme, Romantisisme), deformatif (Impresionisme, Surealisme, Kubisme, Ekspresionisme), dan non-representatif (Abstrak).\n• Poin Penting: Tokoh maestro seni lukis Indonesia: Raden Saleh (Romantisisme), Basoeki Abdullah (Naturalisme), dan Affandi (Ekspresionisme); analisis makna simbolik lukisan.\n\nBab 2: Manajemen Pameran Seni Rupa di Lingkungan Sekolah\n• Membahas: Merencanakan kepanitiaan, kurasi karya lukis/patung, tata letak ruang pameran, katalogus karya, dan evaluasi kegiatan pameran seni siswa.\n• Poin Penting: Jalur sirkulasi pengunjung pameran; pencahayaan spot light karya; buku tamu dan buku kesan/pesan; serta fungsi edukasi dan apresiasi pameran sekolah.\n\nBab 3: Aransemen Musik Modern dan Ansambel Sekolah\n• Membahas: Struktur tangga nada diatonis mayor dan minor, akord trinada, serta teknik membuat variasi aransemen lagu daerah ke dalam format grup band atau vokal grup.\n• Poin Penting: Pembagian suara Sopran, Alto, Tenor, Bass (SATB); pola ritem drum dan bas; progresi akord I-IV-V-I; serta harmonisasi instrumen melodis, ritmis, dan harmonis.\n\nBab 4: Eksplorasi Gerak Tari Kontemporer dan Tata Panggung\n• Membahas: Menciptakan gerak tari kreasi non-tradisional yang mengekspresikan tema isu sosial modern dengan pola lantai dinamis.\n• Poin Penting: Pola lantai garis lurus (simbol tegas) dan garis lengkung (simbol lembut); tata panggung proscenium; desain pencahayaan panggung (lighting); serta musik pengiring digital.\n\nBab 5: Penulisan Naskah Lakon dan Pementasan Teater Modern\n• Membahas: Menyusun naskah drama utuh berdasar konflik sosial nyata, membagi peran casting tokoh, dan menyutradarai pementasan panggung.\n• Poin Penting: Struktur naskah: Tema, Premis, Babak, Adegan, Dialog, dan Petunjuk Teknis Panggung (Kramagung); latihan blocking pemain, mimik muka, artikulasi, dan intonasi suara.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Seni-Budaya-Semester-1-Kelas-XI',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Senbud%20Sm1%20Kelas%20XI%20BS%20press.pdf',
                'cover_image' => '/images/covers/seni-budaya-semester-1-kelas-xi.png',
            ],
            [
                'category_id' => $categories['seni-budaya']->id,
                'title' => 'Seni Budaya SMA/SMK Kelas XII',
                'author' => 'Agus Budiman, Dewi Suryati Budiwati, Sukanta, Zakaria S. Soetedja',
                'publisher' => 'Pusat Kurikulum dan Perbukuan, Balitbang, Kemendikbud',
                'city' => 'Jakarta',
                'year' => 2018,
                'isbn' => '978-602-427-147-3',
                'stock' => 16,
                'available_stock' => 16,
                'description' => "Sinopsis Buku:\nBuku Seni Budaya Kelas XII mendalami penulisan kritik karya seni rupa profesional, kreasi pertunjukan musik kontemporer, apresiasi karya tari mancanegara, serta produksi pergelaran teater musikal sekolah.\n\nRangkuman Pembahasan & Poin Penting Tiap Bab:\n\nBab 1: Apresiasi dan Penulisan Kritik Seni Rupa\n• Membahas: Menilai bobot estetis karya seni rupa secara objektif melalui 4 tahapan kritik seni: Deskripsi, Analisis Formal, Interpretasi, dan Evaluasi/Penilaian.\n• Poin Penting: Jenis kritik seni: Kritik Populer (media massa), Kritik Jurnalis, Kritik Kependidikan, dan Kritik Ilmiah/Akademik; etika memberi saran apresiatif kepada seniman.\n\nBab 2: Kreativitas Musik Kreasi dan Eksplorasi Bunyi Kontemporer\n• Membahas: Menciptakan komposisi musik eksperimental memanfaatkan barang bekas (perkusi tong, botol kaca, ember) berpadu instrumen digital.\n• Poin Penting: Konsep musik konkret; eksplorasi dinamika piano (lembut) hingga forte (keras); timbre warna bunyi unik; serta penyusunan partitur notasi balok atau angka.\n\nBab 3: Apresiasi dan Manajemen Pagelaran Tari Mancanegara\n• Membahas: Mempelajari ragam tari dunia (Ballet Eropa, Flamenco Spanyol, Tari Saman/Kecak internasional) dan mengorganisasi festival tari sekolah.\n• Poin Penting: Karakteristik gerak tari oriental Asia vs tari barat; manajemen tata rias karakter fantasi; rancangan anggaran dana sponsor dan tiket pagelaran seni tari.\n\nBab 4: Produksi Pergelaran Teater Musikal Sekolah\n• Membahas: Menyatukan seni peran akting, vokal nyanyian musikal, koreografi tari, dan artistik panggung dalam satu produksi pergelaran akbar teater sekolah.\n• Poin Penting: Peran vital Tim Produksi (Pimpinan Produksi, Seksi Dana, Publikasi, Tiket) dan Tim Artistik (Sutradara, Stage Manager, Tata Panggung, Tata Busana, Tata Musik); gladi resik dan evaluasi pascapentas.",
                'source_url' => 'https://buku.kemendikdasmen.go.id/katalog/Seni-Budaya-Semester-1-Kelas-XII',
                'pdf_url' => 'https://static-sc.cloudapp.web.id/content/pdf/bukuteks/k13/bukusiswa/Kelas%20XII%20SENBUD%20Sem.%201%20BS%20press.pdf',
                'cover_image' => '/images/covers/seni-budaya-semester-1-kelas-xii.png',
            ],
        ];

        // Kosongkan dan masukkan 27 buku pelajaran SMA/SMK resmi
        $books = [];
        foreach ($booksData as $bData) {
            $books[] = Book::updateOrCreate(
                ['title' => $bData['title']],
                $bData
            );
        }

        // Hapus buku usang yang tidak ada dalam daftar buku resmi SMA/SMK jika ada
        Book::whereNotIn('title', array_column($booksData, 'title'))->delete();

        // 5. Data Sampel Transaksi Peminjaman Siswa
        Borrowing::updateOrCreate(
            ['borrow_code' => 'BRW-20261001-001'],
            [
                'user_id' => $siswa1->id,
                'book_id' => $books[1]->id, // Sejarah Indonesia Kelas XI
                'borrow_date' => now()->subDays(2)->format('Y-m-d'),
                'due_date' => now()->addDays(5)->format('Y-m-d'),
                'return_date' => null,
                'status' => 'dipinjam',
                'fine_amount' => 0,
                'notes' => 'Untuk bahan tugas kelompok materi Pergerakan Nasional Sumpah Pemuda',
                'admin_notes' => 'Disetujui pustakawan. Harap dirawat sampul bukunya.',
            ]
        );

        Borrowing::updateOrCreate(
            ['borrow_code' => 'BRW-20261004-002'],
            [
                'user_id' => $siswa1->id,
                'book_id' => $books[5]->id, // Matematika Kelas XII
                'borrow_date' => now()->format('Y-m-d'),
                'due_date' => now()->addDays(7)->format('Y-m-d'),
                'return_date' => null,
                'status' => 'menunggu',
                'fine_amount' => 0,
                'notes' => 'Persiapan belajar mandiri materi Dimensi Tiga & Statistika data kelompok',
                'admin_notes' => null,
            ]
        );

        Borrowing::updateOrCreate(
            ['borrow_code' => 'BRW-20260925-003'],
            [
                'user_id' => $siswa2->id,
                'book_id' => $books[0]->id, // Sejarah Indonesia Kelas X
                'borrow_date' => now()->subDays(10)->format('Y-m-d'),
                'due_date' => now()->subDays(3)->format('Y-m-d'),
                'return_date' => now()->subDays(3)->format('Y-m-d'),
                'status' => 'dikembalikan',
                'fine_amount' => 0,
                'notes' => 'Belajar materi Praaksara & Kerajaan Maritim untuk Penilaian Harian',
                'admin_notes' => 'Buku kembali lengkap dan rapi.',
            ]
        );
    }
}
