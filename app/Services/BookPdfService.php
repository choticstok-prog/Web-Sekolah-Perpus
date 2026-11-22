<?php

namespace App\Services;

class BookPdfService
{
    /**
     * Generate and cache a complete, valid PDF 1.4 document for a book.
     */
    public function getOrGeneratePdf($book): string
    {
        $dir = public_path('documents/books');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filePath = $dir.'/book-'.$book->id.'.pdf';
        if (file_exists($filePath) && filesize($filePath) > 500) {
            return $filePath;
        }

        if (! empty($book->pdf_url) && filter_var($book->pdf_url, FILTER_VALIDATE_URL) && str_starts_with($book->pdf_url, 'https://')) {
            if ($this->downloadRemotePdf($book->pdf_url, $filePath)) {
                return $filePath;
            }
        }

        $pdfContent = $this->buildPdfContent($book);
        file_put_contents($filePath, $pdfContent);

        return $filePath;
    }

    /**
     * Download remote PDF from official Kemdikbud/SIBI repository.
     */
    public function downloadRemotePdf(string $url, string $destination): bool
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && is_string($data) && strlen($data) > 5000 && str_starts_with($data, '%PDF')) {
            file_put_contents($destination, $data);

            return true;
        }

        return false;
    }

    /**
     * Force regenerate a book's PDF.
     */
    public function forceRegeneratePdf($book): string
    {
        $dir = public_path('documents/books');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filePath = $dir.'/book-'.$book->id.'.pdf';

        if (! empty($book->pdf_url) && filter_var($book->pdf_url, FILTER_VALIDATE_URL) && str_starts_with($book->pdf_url, 'https://')) {
            if ($this->downloadRemotePdf($book->pdf_url, $filePath)) {
                return $filePath;
            }
        }

        $pdfContent = $this->buildPdfContent($book);
        file_put_contents($filePath, $pdfContent);

        return $filePath;
    }

    /**
     * Build the raw PDF 1.4 binary/text stream.
     */
    public function buildPdfContent($book): string
    {
        $isNovel = ($book->category && in_array($book->category->slug, ['novel-fiksi', 'sejarah-peminatan-dunia']))
            || str_contains(strtolower($book->title), 'manchester united')
            || (isset($book->cover_image) && str_starts_with($book->cover_image, '/images/covers/novel-'));

        if ($isNovel) {
            return $this->buildNovelPdfContent($book);
        }

        return $this->buildTextbookPdfContent($book);
    }

    /**
     * Build full novel reading PDF with story narrative, chapters, and dialogues.
     */
    public function buildNovelPdfContent($book): string
    {
        $pages = [];
        $pageHeight = 841.89; // A4
        $pageWidth = 595.28;  // A4
        $maxChars = 72;

        $story = NovelStoryContent::get($book->title);

        // ================= PAGE 1: ELEGANT NOVEL COVER =================
        $p1 = '';
        // Background: Deep Midnight Navy
        $p1 .= sprintf("%.3f %.3f %.3f rg 0 0 %.2f %.2f re f\n", 11 / 255, 19 / 255, 41 / 255, $pageWidth, $pageHeight);
        // Outer Elegant Gold Frame
        $p1 .= sprintf("%.3f %.3f %.3f RG 2 w 25 25 %.2f %.2f re S\n", 245 / 255, 158 / 255, 11 / 255, $pageWidth - 50, $pageHeight - 50);
        $p1 .= sprintf("%.3f %.3f %.3f RG 0.75 w 30 30 %.2f %.2f re S\n", 251 / 255, 191 / 255, 36 / 255, $pageWidth - 60, $pageHeight - 60);

        // Header Ribbon
        $p1 .= $this->textCmd(55, 765, 'KOLEKSI SASTRA & NOVEL NUSANTARA', 'F2', 12, 250, 204, 21);
        $p1 .= $this->textCmd(55, 745, 'PustakaSekolah E-Reader - Edisi Naskah Lengkap (Bab 1 s/d Tamat)', 'F3', 10, 203, 213, 225);
        $p1 .= sprintf("%.3f %.3f %.3f rg 55 735 %.2f 2 re f\n", 245 / 255, 158 / 255, 11 / 255, $pageWidth - 110);

        // Large Novel Title
        $titleLines = $this->wrap(strtoupper($book->title), 32);
        $ty = 670;
        foreach (array_slice($titleLines, 0, 3) as $tLine) {
            $p1 .= $this->textCmd(55, $ty, $tLine, 'F2', 26, 255, 255, 255);
            $ty -= 34;
        }

        // Author Byline
        $p1 .= $this->textCmd(55, $ty - 8, 'Karya Sastra: '.($book->author ?: 'Sastrawan Indonesia'), 'F2', 14, 251, 191, 36);

        // Book Decorative Center Plaque
        $p1 .= sprintf("%.3f %.3f %.3f rg 55 350 %.2f 190 re f\n", 24 / 255, 36 / 255, 64 / 255, $pageWidth - 110);
        $p1 .= sprintf("%.3f %.3f %.3f RG 1 w 55 350 %.2f 190 re S\n", 245 / 255, 158 / 255, 11 / 255, $pageWidth - 110);

        $p1 .= $this->textCmd(75, 505, 'DATA BUKU & IDENTITAS EDISI PERPUSTAKAAN:', 'F2', 11, 250, 204, 21);
        $p1 .= $this->textCmd(75, 480, 'Judul Novel  : '.$book->title, 'F1', 10, 241, 245, 249);
        $p1 .= $this->textCmd(75, 458, 'Penulis      : '.($book->author ?: '-'), 'F1', 10, 241, 245, 249);
        $p1 .= $this->textCmd(75, 436, 'Penerbit     : '.($book->publisher ?: 'Penerbit Pilihan').' ('.($book->year ?: date('Y')).')', 'F1', 10, 241, 245, 249);
        $p1 .= $this->textCmd(75, 414, 'Kota & ISBN  : '.($book->city ?: 'Indonesia').' | ISBN: '.($book->isbn ?: '-'), 'F1', 10, 241, 245, 249);
        $p1 .= $this->textCmd(75, 392, 'Format Baca  : Naskah Cerita Lengkap ('.count($story['chapters']).' Bab, Dialog & Alur Penuh)', 'F2', 10, 52, 211, 153);
        $p1 .= $this->textCmd(75, 370, 'Status Akses : Bebas Akses Baca Siswa Perpustakaan Sekolah Digital', 'F1', 10, 203, 213, 225);

        // Teaser on cover
        $teaserLines = $this->wrap($story['prologue'], 66);
        $py = 310;
        $p1 .= $this->textCmd(55, $py, 'PENGANTAR REDAKSI PERPUSTAKAAN:', 'F2', 11, 250, 204, 21);
        $py -= 20;
        foreach (array_slice($teaserLines, 0, 4) as $tLine) {
            $p1 .= $this->textCmd(55, $py, $tLine, 'F3', 9.5, 203, 213, 225);
            $py -= 16;
        }

        // Cover Footer
        $p1 .= $this->textCmd(55, 45, 'Perpustakaan Online PustakaSekolah • Koleksi Khusus Pembaca Literasi Siswa & Guru', 'F3', 9, 148, 163, 184);
        $pages[] = $p1;

        // ================= PAGE 2: HALAMAN JUDUL & INFORMASI PENERBITAN (IMPRINT) =================
        $p2 = '';
        $this->addNovelPageHeader($p2, $pageWidth, $book->title, 'Informasi Penerbitan & Hak Cipta');

        $p2 .= $this->textCmd(60, 720, strtoupper($book->title), 'F2', 18, 15, 23, 42);
        $p2 .= $this->textCmd(60, 698, 'Karya: '.($book->author ?: '-'), 'F2', 12, 37, 99, 235);
        $p2 .= sprintf("%.3f %.3f %.3f rg 60 685 475 1.5 re f\n", 226 / 255, 232 / 255, 240 / 255);

        $y = 660;
        $p2 .= $this->textCmd(60, $y, 'DATA BIBLIOGRAFI & KATALOGISASI:', 'F2', 11, 30, 41, 59);
        $y -= 22;
        $p2 .= $this->textCmd(60, $y, 'Judul Asli       : '.$book->title, 'F1', 10, 51, 65, 85);
        $y -= 18;
        $p2 .= $this->textCmd(60, $y, 'Penulis / Karya  : '.($book->author ?: '-'), 'F1', 10, 51, 65, 85);
        $y -= 18;
        $p2 .= $this->textCmd(60, $y, 'Penerbit         : '.($book->publisher ?: 'Penerbit Nusantara'), 'F1', 10, 51, 65, 85);
        $y -= 18;
        $p2 .= $this->textCmd(60, $y, 'Tahun Terbit     : '.($book->year ?: date('Y')), 'F1', 10, 51, 65, 85);
        $y -= 18;
        $p2 .= $this->textCmd(60, $y, 'Kota Terbit      : '.($book->city ?: 'Indonesia'), 'F1', 10, 51, 65, 85);
        $y -= 18;
        $p2 .= $this->textCmd(60, $y, 'Nomor ISBN       : '.($book->isbn ?: '-'), 'F1', 10, 51, 65, 85);
        $y -= 18;
        $p2 .= $this->textCmd(60, $y, 'Kategori Koleksi : Novel Fiksi, Karya Sastra Remaja & Dewasa Muda', 'F1', 10, 51, 65, 85);
        $y -= 18;
        $p2 .= $this->textCmd(60, $y, 'Format Naskah    : Edisi Lengkap (Prolog, '.count($story['chapters']).' Bab Cerita, dan Epilog)', 'F2', 10, 16, 185, 129);

        $y -= 35;
        $p2 .= sprintf("%.3f %.3f %.3f rg 60 %.2f 475 90 re f\n", 248 / 255, 250 / 255, 252 / 255, $y - 70);
        $p2 .= sprintf("%.3f %.3f %.3f RG 1 w 60 %.2f 475 90 re S\n", 203 / 255, 213 / 255, 225 / 255, $y - 70);
        $p2 .= $this->textCmd(75, $y, 'CATATAN LITERASI PERPUSTAKAAN SEKOLAH:', 'F2', 10, 30, 41, 59);
        $y -= 18;
        $p2 .= $this->textCmd(75, $y, 'Edisi digital ini disajikan untuk mendukung program literasi membaca di kalangan siswa,', 'F1', 9.5, 71, 85, 105);
        $y -= 15;
        $p2 .= $this->textCmd(75, $y, 'pendidik, dan pecinta karya sastra Indonesia. Hak cipta moral karya sastra sepenuhnya', 'F1', 9.5, 71, 85, 105);
        $y -= 15;
        $p2 .= $this->textCmd(75, $y, 'tetap melekat pada penulis dan penerbit resmi masing-masing.', 'F1', 9.5, 71, 85, 105);

        $this->addNovelPageFooter($p2, 2);
        $pages[] = $p2;

        // ================= PAGE 3: DAFTAR ISI LENGKAP =================
        $p3 = '';
        $this->addNovelPageHeader($p3, $pageWidth, $book->title, 'Daftar Isi Naskah Lengkap');

        $p3 .= sprintf("%.3f %.3f %.3f rg 50 720 495 28 re f\n", 239 / 255, 246 / 255, 255 / 255);
        $p3 .= $this->textCmd(60, 730, 'DAFTAR ISI & STRUKTUR CERITA NOVEL', 'F2', 11, 30, 58, 138);

        $y = 695;
        $p3 .= $this->textCmd(60, $y, '• Bagian Awal : Prakata, Latar Belakang & Daftar Tokoh', 'F2', 10, 15, 23, 42);
        $y -= 18;

        foreach ($story['chapters'] as $cIdx => $c) {
            if ($y < 75) {
                $this->addNovelPageFooter($p3, count($pages) + 1);
                $pages[] = $p3;
                $p3 = '';
                $this->addNovelPageHeader($p3, $pageWidth, $book->title, 'Daftar Isi (Lanjutan)');
                $y = 740;
            }
            $p3 .= $this->textCmd(60, $y, '• '.$c['number'].' : '.$c['title'], 'F1', 9.5, 51, 65, 85);
            $y -= 17;
        }

        if ($y < 85) {
            $this->addNovelPageFooter($p3, count($pages) + 1);
            $pages[] = $p3;
            $p3 = '';
            $this->addNovelPageHeader($p3, $pageWidth, $book->title, 'Daftar Isi (Lanjutan)');
            $y = 740;
        }
        $y -= 6;
        $p3 .= $this->textCmd(60, $y, '• Bagian Akhir : Epilog & Renungan Nilai Sastra Budi Pekerti', 'F2', 10, 16, 185, 129);

        $this->addNovelPageFooter($p3, count($pages) + 1);
        $pages[] = $p3;

        // ================= PAGE 4: PRAKATA & PENGENALAN TOKOH =================
        $p4 = '';
        $this->addNovelPageHeader($p4, $pageWidth, $book->title, 'Prakata & Tokoh Utama');

        $p4 .= sprintf("%.3f %.3f %.3f rg 50 720 495 26 re f\n", 239 / 255, 246 / 255, 255 / 255);
        $p4 .= $this->textCmd(60, 729, 'LATAR BELAKANG & PENGANTAR CERITA', 'F2', 11, 30, 58, 138);

        $prologueLines = $this->wrap($story['prologue'], $maxChars);
        $y = 695;
        foreach ($prologueLines as $pLine) {
            $p4 .= $this->textCmd(50, $y, $pLine, 'F1', 10, 51, 65, 85);
            $y -= 16;
        }

        $y -= 12;
        $p4 .= sprintf("%.3f %.3f %.3f rg 50 %.2f 495 26 re f\n", 254 / 255, 243 / 255, 199 / 255, $y);
        $p4 .= $this->textCmd(60, $y + 9, 'TOKOH UTAMA & KARAKTER DALAM NOVEL', 'F2', 11, 146, 64, 14);
        $y -= 26;

        foreach ($story['characters'] as $charName => $charDesc) {
            if ($y < 75) {
                $this->addNovelPageFooter($p4, count($pages) + 1);
                $pages[] = $p4;
                $p4 = '';
                $this->addNovelPageHeader($p4, $pageWidth, $book->title, 'Daftar Tokoh (Lanjutan)');
                $y = 740;
            }
            $p4 .= $this->textCmd(50, $y, '• '.$charName.' :', 'F2', 10, 15, 23, 42);
            $y -= 15;
            $charLines = $this->wrap($charDesc, $maxChars - 4);
            foreach ($charLines as $cLine) {
                if ($y < 75) {
                    $this->addNovelPageFooter($p4, count($pages) + 1);
                    $pages[] = $p4;
                    $p4 = '';
                    $this->addNovelPageHeader($p4, $pageWidth, $book->title, 'Daftar Tokoh (Lanjutan)');
                    $y = 740;
                }
                $p4 .= $this->textCmd(65, $y, $cLine, 'F1', 9.5, 71, 85, 105);
                $y -= 15;
            }
            $y -= 6;
        }

        $this->addNovelPageFooter($p4, count($pages) + 1);
        $pages[] = $p4;

        // ================= PAGES 5+: CHAPTERS (EACH CHAPTER STARTS ON A NEW PAGE) =================
        foreach ($story['chapters'] as $chIdx => $chap) {
            $curPage = '';
            $curPageNum = count($pages) + 1;
            $this->addNovelPageHeader($curPage, $pageWidth, $book->title, $chap['number'].' : '.$chap['title']);

            // Chapter Header Box
            $curY = 740;
            $curPage .= sprintf("%.3f %.3f %.3f rg 45 %.2f 505 34 re f\n", 241 / 255, 245 / 255, 249 / 255, $curY - 6);
            $curPage .= sprintf("%.3f %.3f %.3f rg 45 %.2f 4 34 re f\n", 37 / 255, 99 / 255, 235 / 255, $curY - 6);
            $curPage .= $this->textCmd(55, $curY + 12, $chap['number'].' : '.strtoupper($chap['title']), 'F2', 11, 30, 41, 59);
            $curY -= 40;

            // Chapter story paragraphs
            foreach ($chap['paragraphs'] as $para) {
                $pLines = $this->wrap($para, $maxChars);
                foreach ($pLines as $lIdx => $line) {
                    if ($curY < 75) {
                        $this->addNovelPageFooter($curPage, $curPageNum);
                        $pages[] = $curPage;
                        $curPageNum = count($pages) + 1;
                        $curPage = '';
                        $curY = 740;
                        $this->addNovelPageHeader($curPage, $pageWidth, $book->title, $chap['number'].' (Lanjutan)');
                    }

                    // First line indent for authentic book style
                    $indent = ($lIdx === 0) ? 68.0 : 50.0;
                    $curPage .= $this->textCmd($indent, $curY, $line, 'F1', 10, 30, 41, 59);
                    $curY -= 16.5;
                }
                $curY -= 10; // Paragraph break
            }

            $this->addNovelPageFooter($curPage, $curPageNum);
            $pages[] = $curPage;
        }

        // ================= FINAL PAGE: EPILOGUE SECTION (ON FRESH NEW PAGE) =================
        $curPage = '';
        $curPageNum = count($pages) + 1;
        $curY = 740;
        $this->addNovelPageHeader($curPage, $pageWidth, $book->title, 'Epilog & Renungan');

        $curPage .= sprintf("%.3f %.3f %.3f rg 50 %.2f 495 28 re f\n", 236 / 255, 253 / 255, 245 / 255, $curY - 4);
        $curPage .= $this->textCmd(60, $curY + 6, 'EPILOG & NILAI BUDI PEKERTI LITERASI', 'F2', 11, 6 / 255, 95 / 255, 70 / 255);
        $curY -= 32;

        $epilogueLines = $this->wrap($story['epilogue'], $maxChars);
        foreach ($epilogueLines as $eLine) {
            if ($curY < 75) {
                $this->addNovelPageFooter($curPage, $curPageNum);
                $pages[] = $curPage;
                $curPageNum = count($pages) + 1;
                $curPage = '';
                $curY = 740;
                $this->addNovelPageHeader($curPage, $pageWidth, $book->title, 'Epilog (Lanjutan)');
            }
            $curPage .= $this->textCmd(50, $curY, $eLine, 'F3', 10, 51, 65, 85);
            $curY -= 16.5;
        }

        $curY -= 20;
        $curPage .= sprintf("%.3f %.3f %.3f rg 50 %.2f 495 65 re f\n", 248 / 255, 250 / 255, 252 / 255, $curY - 45);
        $curPage .= sprintf("%.3f %.3f %.3f RG 1 w 50 %.2f 495 65 re S\n", 203 / 255, 213 / 255, 225 / 255, $curY - 45);
        $curPage .= $this->textCmd(65, $curY + 5, 'CATATAN APRESIASI LITERASI PERPUSTAKAAN:', 'F2', 9.5, 30, 41, 59);
        $curY -= 14;
        $curPage .= $this->textCmd(65, $curY + 5, 'PustakaSekolah mengajak setiap pembaca untuk memetik hikmah ketulusan cinta,', 'F1', 9, 71, 85, 105);
        $curY -= 14;
        $curPage .= $this->textCmd(65, $curY + 5, 'keberanian memperjuangkan impian seni, dan keikhlasan dalam menentukan muara hidup.', 'F1', 9, 71, 85, 105);

        $this->addNovelPageFooter($curPage, $curPageNum);
        $pages[] = $curPage;

        return $this->compilePdf($pages);
    }

    /**
     * Build textbook study guide PDF with chapters and learning objectives.
     */
    public function buildTextbookPdfContent($book): string
    {
        $pages = [];
        $pageHeight = 841.89; // A4
        $pageWidth = 595.28;  // A4
        $maxChars = 78;

        // ================= PAGE 1: TEXTBOOK COVER =================
        $p1 = '';
        $p1 .= sprintf("%.3f %.3f %.3f rg 0 680 %.2f 162 re f\n", 15 / 255, 23 / 255, 42 / 255, $pageWidth);
        $p1 .= sprintf("%.3f %.3f %.3f rg 0 674 %.2f 6 re f\n", 245 / 255, 158 / 255, 11 / 255, $pageWidth);

        $p1 .= $this->textCmd(50, 800, 'PUSTAKASEKOLAH - PERPUSTAKAAN ONLINE RESMI', 'F2', 12, 250, 204, 21);
        $p1 .= $this->textCmd(50, 775, 'Buku Teks Kurikulum Merdeka & K13 E-Reader Siswa', 'F3', 10, 203, 213, 225);

        $catName = $book->category ? $book->category->name : 'Buku Pelajaran';
        $p1 .= sprintf("%.3f %.3f %.3f rg 50 735 240 24 re f\n", 37 / 255, 99 / 255, 235 / 255);
        $p1 .= $this->textCmd(60, 742, strtoupper($catName), 'F2', 9, 255, 255, 255);

        $titleLines = $this->wrap($book->title, 42);
        $ty = 630;
        foreach (array_slice($titleLines, 0, 3) as $tLine) {
            $p1 .= $this->textCmd(50, $ty, $tLine, 'F2', 20, 15, 23, 42);
            $ty -= 28;
        }

        $p1 .= sprintf("%.3f %.3f %.3f rg 50 430 495 140 re f\n", 248 / 255, 250 / 255, 252 / 255);
        $p1 .= sprintf("%.3f %.3f %.3f RG 1 w 50 430 495 140 re S\n", 226 / 255, 232 / 255, 240 / 255);

        $p1 .= $this->textCmd(70, 540, 'INFORMASI BUKU PELAJARAN RESMI:', 'F2', 11, 30, 41, 59);
        $p1 .= $this->textCmd(70, 515, 'Penulis / Penyusun : '.($book->author ?: '-'), 'F1', 10, 51, 65, 85);
        $p1 .= $this->textCmd(70, 492, 'Penerbit & Tahun   : '.($book->publisher ?: 'Kemendikbudristek').' ('.($book->year ?: date('Y')).')', 'F1', 10, 51, 65, 85);
        $p1 .= $this->textCmd(70, 469, 'Kota Terbit & ISBN : '.($book->city ?: 'Jakarta').' | ISBN: '.($book->isbn ?: '-'), 'F1', 10, 51, 65, 85);
        $p1 .= $this->textCmd(70, 446, 'Status Akreditasi  : Buku Teks Pelajaran Resmi Kurikulum Nasional', 'F2', 9, 5, 150, 105);

        $desc = $book->description ?: 'Buku materi pelajaran sekolah.';
        $p1 .= $this->textCmd(50, 395, 'SINOPSIS & TUJUAN PEMBELAJARAN:', 'F2', 12, 15, 23, 42);

        $descLines = $this->wrap($desc, $maxChars);
        $dy = 370;
        $maxPreview = min(12, count($descLines));
        for ($i = 0; $i < $maxPreview; $i++) {
            $p1 .= $this->textCmd(50, $dy, $descLines[$i], 'F1', 10, 71, 85, 105);
            $dy -= 17;
        }

        $p1 .= sprintf("%.3f %.3f %.3f rg 50 45 495 1 re f\n", 226 / 255, 232 / 255, 240 / 255);
        $p1 .= $this->textCmd(50, 30, 'Perpustakaan Online PustakaSekolah - Halaman 1', 'F3', 9, 148, 163, 184);
        $pages[] = $p1;

        // ================= PAGE 2+: DETAILED CHAPTER DISCUSSION =================
        $lines = [];
        $rawParagraphs = explode("\n", str_replace("\r", '', $desc));
        foreach ($rawParagraphs as $para) {
            $para = trim($para);
            if ($para === '') {
                $lines[] = ['text' => '', 'type' => 'empty'];

                continue;
            }
            if (str_starts_with($para, 'Bab ') || str_starts_with($para, 'BAB ') || str_starts_with($para, 'Chapter ') || str_ends_with($para, ':')) {
                $lines[] = ['text' => $para, 'type' => 'heading'];
            } elseif (str_starts_with($para, '•') || str_starts_with($para, '-')) {
                $wrapped = $this->wrap($para, $maxChars);
                foreach ($wrapped as $wIdx => $wLine) {
                    $lines[] = ['text' => $wLine, 'type' => ($wIdx === 0 ? 'bullet' : 'bullet-cont')];
                }
            } else {
                $wrapped = $this->wrap($para, $maxChars);
                foreach ($wrapped as $wLine) {
                    $lines[] = ['text' => $wLine, 'type' => 'body'];
                }
            }
        }

        $curPageContent = '';
        $curY = 770;
        $pageNum = 2;

        $initHeader = function (&$pContent, $pNum) use ($pageWidth, $book): void {
            $pContent .= sprintf("%.3f %.3f %.3f rg 0 810 %.2f 32 re f\n", 241 / 255, 245 / 255, 249 / 255, $pageWidth);
            $pContent .= sprintf("%.3f %.3f %.3f rg 0 808 %.2f 2 re f\n", 37 / 255, 99 / 255, 235 / 255, $pageWidth);
            $pContent .= $this->textCmd(50, 822, $book->title, 'F2', 9, 15, 23, 42);
            $pContent .= $this->textCmd(420, 822, 'PustakaSekolah E-Reader', 'F3', 8, 100, 116, 139);
        };

        $initHeader($curPageContent, $pageNum);

        foreach ($lines as $lineData) {
            if ($curY < 75) {
                $curPageContent .= sprintf("%.3f %.3f %.3f rg 50 45 495 1 re f\n", 226 / 255, 232 / 255, 240 / 255);
                $curPageContent .= $this->textCmd(50, 30, 'Perpustakaan Online PustakaSekolah - Halaman '.$pageNum, 'F3', 9, 148, 163, 184);
                $pages[] = $curPageContent;

                $pageNum++;
                $curPageContent = '';
                $curY = 770;
                $initHeader($curPageContent, $pageNum);
            }

            if ($lineData['type'] === 'empty') {
                $curY -= 10;
            } elseif ($lineData['type'] === 'heading') {
                $curY -= 8;
                $curPageContent .= sprintf("%.3f %.3f %.3f rg 45 %.2f 505 20 re f\n", 239 / 255, 246 / 255, 255 / 255, $curY - 4);
                $curPageContent .= $this->textCmd(50, $curY, $lineData['text'], 'F2', 11, 30, 58, 138);
                $curY -= 22;
            } elseif ($lineData['type'] === 'bullet') {
                $curPageContent .= $this->textCmd(55, $curY, $lineData['text'], 'F2', 9.5, 30, 41, 59);
                $curY -= 16;
            } elseif ($lineData['type'] === 'bullet-cont') {
                $curPageContent .= $this->textCmd(65, $curY, $lineData['text'], 'F1', 9.5, 71, 85, 105);
                $curY -= 16;
            } else {
                $curPageContent .= $this->textCmd(50, $curY, $lineData['text'], 'F1', 9.5, 51, 65, 85);
                $curY -= 16;
            }
        }

        $curPageContent .= sprintf("%.3f %.3f %.3f rg 50 45 495 1 re f\n", 226 / 255, 232 / 255, 240 / 255);
        $curPageContent .= $this->textCmd(50, 30, 'Perpustakaan Online PustakaSekolah - Halaman '.$pageNum, 'F3', 9, 148, 163, 184);
        $pages[] = $curPageContent;

        return $this->compilePdf($pages);
    }

    private function addNovelPageHeader(string &$content, float $width, string $title, string $section): void
    {
        $content .= sprintf("%.3f %.3f %.3f rg 0 812 %.2f 30 re f\n", 248 / 255, 250 / 255, 252 / 255, $width);
        $content .= sprintf("%.3f %.3f %.3f rg 0 810 %.2f 2 re f\n", 245 / 255, 158 / 255, 11 / 255, $width);
        $content .= $this->textCmd(50, 822, strtoupper($title).' - '.$section, 'F2', 9, 30, 41, 59);
        $content .= $this->textCmd(430, 822, 'PustakaSekolah Novel Reader', 'F3', 8, 100, 116, 139);
    }

    private function addNovelPageFooter(string &$content, int $pageNum): void
    {
        $content .= sprintf("%.3f %.3f %.3f rg 50 45 495 1 re f\n", 226 / 255, 232 / 255, 240 / 255);
        $content .= $this->textCmd(50, 30, 'Edisi Bacaan Sastra & Novel - PustakaSekolah', 'F3', 8.5, 148, 163, 184);
        $content .= $this->textCmd(480, 30, 'Hal. '.$pageNum, 'F2', 8.5, 100, 116, 139);
    }

    /**
     * Wrap string cleanly by word boundaries.
     */
    private function wrap(string $str, int $width = 75): array
    {
        $wrapped = wordwrap($str, $width, "\n", true);

        return explode("\n", $wrapped);
    }

    /**
     * Build text placement operator string in PDF syntax.
     */
    private function textCmd(float $x, float $y, string $text, string $font = 'F1', float $size = 10, float $r = 0, float $g = 0, float $b = 0): string
    {
        $rNorm = round($r / 255, 3);
        $gNorm = round($g / 255, 3);
        $bNorm = round($b / 255, 3);

        $cleanText = strtr($text, [
            '\\' => '\\\\',
            '(' => '\(',
            ')' => '\)',
            "\r" => '',
            "\n" => ' ',
            '•' => '-',
            '—' => '-',
            '–' => '-',
            '“' => '"',
            '”' => '"',
            '‘' => "'",
            '’' => "'",
        ]);

        return sprintf("BT /%s %.1f Tf %.3f %.3f %.3f rg %.2f %.2f Td (%s) Tj ET\n", $font, $size, $rNorm, $gNorm, $bNorm, $x, $y, $cleanText);
    }

    /**
     * Compile page streams into complete PDF 1.4 binary.
     */
    private function compilePdf(array $pages): string
    {
        $pdf = "%PDF-1.4\n";
        $offsets = [];

        // Obj 1: Catalog
        $offsets[1] = strlen($pdf);
        $pdf .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";

        // Obj 3: Font F1 (Helvetica)
        $offsets[3] = strlen($pdf);
        $pdf .= "3 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";

        // Obj 4: Font F2 (Helvetica-Bold)
        $offsets[4] = strlen($pdf);
        $pdf .= "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>\nendobj\n";

        // Obj 5: Font F3 (Helvetica-Oblique)
        $offsets[5] = strlen($pdf);
        $pdf .= "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Oblique >>\nendobj\n";

        $pageObjIds = [];
        $nextObjId = 6;

        foreach ($pages as $content) {
            $pageId = $nextObjId++;
            $contentId = $nextObjId++;
            $pageObjIds[] = $pageId;

            // Stream
            $streamLen = strlen($content);
            $offsets[$contentId] = strlen($pdf);
            $pdf .= "{$contentId} 0 obj\n<< /Length {$streamLen} >>\nstream\n{$content}\nendstream\nendobj\n";

            // Page
            $offsets[$pageId] = strlen($pdf);
            $pdf .= "{$pageId} 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595.28 841.89] /Contents {$contentId} 0 R /Resources << /Font << /F1 3 0 R /F2 4 0 R /F3 5 0 R >> >> >>\nendobj\n";
        }

        // Obj 2: Pages
        $kidsStr = implode(' ', array_map(fn ($id) => "{$id} 0 R", $pageObjIds));
        $pageCount = count($pageObjIds);
        $offsets[2] = strlen($pdf);
        $pdf .= "2 0 obj\n<< /Type /Pages /Kids [ {$kidsStr} ] /Count {$pageCount} >>\nendobj\n";

        // Xref
        $xrefOffset = strlen($pdf);
        $totalObjs = $nextObjId;
        $pdf .= "xref\n0 {$totalObjs}\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i < $totalObjs; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        // Trailer
        $pdf .= "trailer\n<< /Size {$totalObjs} /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF\n";

        return $pdf;
    }
}
