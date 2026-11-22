<?php

namespace App\Services;

class BookReportPdfService
{
    /**
     * Generate an executive landscape PDF report for library book inventory.
     */
    public function generate($books, array $summary, array $filters = []): string
    {
        $pages = [];
        $pageWidth = 841.89;  // A4 Landscape Width
        $pageHeight = 595.28; // A4 Landscape Height

        $totalBooks = count($books);
        $rowsPerPageFirst = 14;
        $rowsPerPageSubsequent = 20;

        // Chunk books into pages
        $bookChunks = [];
        if ($totalBooks <= $rowsPerPageFirst) {
            $bookChunks[] = $books;
        } else {
            $bookChunks[] = $books->slice(0, $rowsPerPageFirst);
            $remaining = $books->slice($rowsPerPageFirst);
            foreach ($remaining->chunk($rowsPerPageSubsequent) as $chunk) {
                $bookChunks[] = $chunk;
            }
        }

        $totalPages = count($bookChunks);
        $globalRowIndex = 1;

        foreach ($bookChunks as $pageIdx => $chunk) {
            $isFirstPage = ($pageIdx === 0);
            $pageNum = $pageIdx + 1;
            $p = '';

            // Background white
            $p .= sprintf("%.3f %.3f %.3f rg 0 0 %.2f %.2f re f\n", 1, 1, 1, $pageWidth, $pageHeight);

            if ($isFirstPage) {
                // ================= PAGE 1 HEADER =================
                // Top Deep Navy Banner
                $p .= sprintf("%.3f %.3f %.3f rg 0 %.2f %.2f 75 re f\n", 15 / 255, 23 / 255, 42 / 255, $pageHeight - 75, $pageWidth);
                $p .= sprintf("%.3f %.3f %.3f rg 0 %.2f %.2f 4 re f\n", 37 / 255, 99 / 255, 235 / 255, $pageHeight - 79, $pageWidth);

                $p .= $this->textCmd(40, 560, 'PUSTAKASEKOLAH - PERPUSTAKAAN ONLINE DIGITAL', 'F2', 14, 255, 255, 255);
                $p .= $this->textCmd(40, 542, 'LAPORAN REKAPITULASI & INVENTARISASI KOLEKSI BUKU', 'F2', 10, 245, 158, 11);
                $p .= $this->textCmd(40, 528, 'Dokumen resmi inventaris katalog, e-reader digital, dan distribusi buku perpustakaan', 'F3', 8.5, 203, 213, 225);

                $dateStr = 'Dicetak: '.date('d/m/Y H:i').' WIB';
                $p .= $this->textCmd(660, 558, $dateStr, 'F1', 8.5, 226, 232, 240);
                $p .= $this->textCmd(660, 544, 'Pengelola: Administrator', 'F1', 8.5, 203, 213, 225);
                $p .= $this->textCmd(660, 530, 'Klasifikasi: Arsip Perpustakaan', 'F2', 8.5, 52, 211, 153);

                // Summary KPI Metric Boxes
                $boxY = 460;
                $boxH = 44;
                $boxW = 180;
                $gap = 13.5;

                // Box 1: Total Koleksi
                $this->drawKpiBox($p, 40, $boxY, $boxW, $boxH, 'TOTAL KOLEKSI BUKU', ($summary['total_books'] ?? $totalBooks).' Judul', 37, 99, 235);
                // Box 2: Siap Baca Online
                $this->drawKpiBox($p, 40 + ($boxW + $gap), $boxY, $boxW, $boxH, 'SIAP BACA ONLINE (PDF)', ($summary['total_online'] ?? 0).' Judul', 16, 185, 129);
                // Box 3: Total Kategori
                $this->drawKpiBox($p, 40 + ($boxW + $gap) * 2, $boxY, $boxW, $boxH, 'KATEGORI TERDAFTAR', ($summary['total_categories'] ?? 0).' Kategori', 245, 158, 11);
                // Box 4: Filter yang Diterapkan
                $filterLabel = $this->buildFilterLabel($filters);
                $this->drawKpiBox($p, 40 + ($boxW + $gap) * 3, $boxY, $boxW, $boxH, 'FILTER DATA TERAPLIKASI', $filterLabel, 100, 116, 139);

                $curY = 432;
            } else {
                // ================= SUBSEQUENT PAGES HEADER =================
                $p .= sprintf("%.3f %.3f %.3f rg 0 %.2f %.2f 32 re f\n", 15 / 255, 23 / 255, 42 / 255, $pageHeight - 32, $pageWidth);
                $p .= sprintf("%.3f %.3f %.3f rg 0 %.2f %.2f 2 re f\n", 37 / 255, 99 / 255, 235 / 255, $pageHeight - 34, $pageWidth);

                $p .= $this->textCmd(40, 574, 'PUSTAKASEKOLAH - Laporan Inventarisasi Data Koleksi Buku (Lanjutan)', 'F2', 9.5, 255, 255, 255);
                $p .= $this->textCmd(680, 574, 'Halaman '.$pageNum.' dari '.$totalPages, 'F1', 8.5, 203, 213, 225);

                $curY = 540;
            }

            // ================= TABLE HEADER =================
            $this->drawTableHeader($p, $curY);
            $curY -= 20;

            // ================= TABLE ROWS =================
            foreach ($chunk as $book) {
                $isZebra = ($globalRowIndex % 2 === 0);
                $this->drawTableRow($p, $curY, $globalRowIndex, $book, $isZebra);
                $curY -= 19;
                $globalRowIndex++;
            }

            // ================= FOOTER =================
            $p .= sprintf("%.3f %.3f %.3f rg 40 32 %.2f 1 re f\n", 226 / 255, 232 / 255, 240 / 255, $pageWidth - 80);
            $p .= $this->textCmd(40, 20, 'PustakaSekolah Perpustakaan Online • Dokumen Inventaris Resmi Sekolah • Dicetak otomatis oleh sistem', 'F3', 8, 148, 163, 184);
            $p .= $this->textCmd(710, 20, 'Halaman '.$pageNum.' dari '.$totalPages, 'F2', 8.5, 71, 85, 105);

            $pages[] = $p;
        }

        return $this->compilePdfLandscape($pages, $pageWidth, $pageHeight);
    }

    private function drawKpiBox(string &$p, float $x, float $y, float $w, float $h, string $label, string $val, float $r, float $g, float $b): void
    {
        // Background card
        $p .= sprintf("%.3f %.3f %.3f rg %.2f %.2f %.2f %.2f re f\n", 248 / 255, 250 / 255, 252 / 255, $x, $y, $w, $h);
        $p .= sprintf("%.3f %.3f %.3f RG 1 w %.2f %.2f %.2f %.2f re S\n", 226 / 255, 232 / 255, 240 / 255, $x, $y, $w, $h);
        // Left color stripe
        $p .= sprintf("%.3f %.3f %.3f rg %.2f %.2f 3.5 %.2f re f\n", $r / 255, $g / 255, $b / 255, $x, $y, $h);

        // Text
        $p .= $this->textCmd($x + 10, $y + $h - 14, $label, 'F2', 7, 100, 116, 139);
        $p .= $this->textCmd($x + 10, $y + 10, $this->truncate($val, 24), 'F2', 11, 15, 23, 42);
    }

    private function drawTableHeader(string &$p, float $y): void
    {
        $h = 20;
        // Navy table header background
        $p .= sprintf("%.3f %.3f %.3f rg 40 %.2f 761.89 %.2f re f\n", 30 / 255, 41 / 255, 59 / 255, $y - $h, $h);

        $p .= $this->textCmd(45, $y - 14, 'NO', 'F2', 8, 255, 255, 255);
        $p .= $this->textCmd(75, $y - 14, 'JUDUL BUKU', 'F2', 8, 255, 255, 255);
        $p .= $this->textCmd(285, $y - 14, 'PENULIS / PENGARANG', 'F2', 8, 255, 255, 255);
        $p .= $this->textCmd(420, $y - 14, 'KATEGORI', 'F2', 8, 255, 255, 255);
        $p .= $this->textCmd(525, $y - 14, 'TAHUN', 'F2', 8, 255, 255, 255);
        $p .= $this->textCmd(570, $y - 14, 'PENERBIT', 'F2', 8, 255, 255, 255);
        $p .= $this->textCmd(675, $y - 14, 'ISBN', 'F2', 8, 255, 255, 255);
        $p .= $this->textCmd(740, $y - 14, 'E-READER', 'F2', 8, 255, 255, 255);
    }

    private function drawTableRow(string &$p, float $y, int $no, $book, bool $isZebra): void
    {
        $h = 19;
        // Row background
        if ($isZebra) {
            $p .= sprintf("%.3f %.3f %.3f rg 40 %.2f 761.89 %.2f re f\n", 248 / 255, 250 / 255, 252 / 255, $y - $h, $h);
        }
        // Bottom border line
        $p .= sprintf("%.3f %.3f %.3f rg 40 %.2f 761.89 0.5 re f\n", 241 / 255, 245 / 255, 249 / 255, $y - $h);

        $textY = $y - 13;

        // No
        $p .= $this->textCmd(45, $textY, (string) $no, 'F1', 7.5, 100, 116, 139);

        // Judul Buku (bold dark)
        $title = $this->truncate($book->title ?? '-', 38);
        $p .= $this->textCmd(75, $textY, $title, 'F2', 8, 15, 23, 42);

        // Penulis
        $author = $this->truncate($book->author ?? '-', 24);
        $p .= $this->textCmd(285, $textY, $author, 'F1', 8, 51, 65, 85);

        // Kategori
        $catName = $this->truncate($book->category ? $book->category->name : '-', 18);
        $p .= $this->textCmd(420, $textY, $catName, 'F1', 8, 71, 85, 105);

        // Tahun
        $year = (string) ($book->year ?? '-');
        $p .= $this->textCmd(525, $textY, $year, 'F2', 8, 30, 41, 59);

        // Penerbit
        $pub = $this->truncate($book->publisher ?? '-', 20);
        $p .= $this->textCmd(570, $textY, $pub, 'F1', 8, 71, 85, 105);

        // ISBN
        $isbn = $this->truncate($book->isbn ?? '-', 13);
        $p .= $this->textCmd(675, $textY, $isbn, 'F1', 7.5, 100, 116, 139);

        // Status E-Reader
        if (! empty($book->pdf_url)) {
            $p .= $this->textCmd(740, $textY, 'Tersedia', 'F2', 7.5, 16, 185, 129);
        } else {
            $p .= $this->textCmd(740, $textY, 'Katalog', 'F1', 7.5, 148, 163, 184);
        }
    }

    private function buildFilterLabel(array $filters): string
    {
        $parts = [];
        if (! empty($filters['search'])) {
            $parts[] = 'Cari: "'.$this->truncate($filters['search'], 10).'"';
        }
        if (! empty($filters['category_name'])) {
            $parts[] = 'Kat: '.$this->truncate($filters['category_name'], 12);
        }
        if (! empty($filters['year'])) {
            $parts[] = 'Thn: '.$filters['year'];
        }

        if (empty($parts)) {
            return 'Semua Koleksi (Tanpa Filter)';
        }

        return implode(', ', $parts);
    }

    private function truncate(string $str, int $limit): string
    {
        if (mb_strlen($str) <= $limit) {
            return $str;
        }

        return mb_substr($str, 0, $limit - 2).'..';
    }

    private function textCmd(float $x, float $y, string $text, string $font = 'F1', float $size = 8, float $r = 0, float $g = 0, float $b = 0): string
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

    private function compilePdfLandscape(array $pages, float $width, float $height): string
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
            $pdf .= sprintf(
                "%d 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %.2f %.2f] /Contents %d 0 R /Resources << /Font << /F1 3 0 R /F2 4 0 R /F3 5 0 R >> >> >>\nendobj\n",
                $pageId,
                $width,
                $height,
                $contentId
            );
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
