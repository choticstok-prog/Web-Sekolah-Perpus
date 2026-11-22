<?php

namespace App\Services;

use App\Services\NovelStories\AnakSemuaBangsaStory;
use App\Services\NovelStories\AromaKarsaStory;
use App\Services\NovelStories\BulanStory;
use App\Services\NovelStories\BumiManusiaStory;
use App\Services\NovelStories\BumiStory;
use App\Services\NovelStories\CantikItuLukaStory;
use App\Services\NovelStories\DilanStory;
use App\Services\NovelStories\GadisKretekStory;
use App\Services\NovelStories\HujanStory;
use App\Services\NovelStories\LaskarPelangiStory;
use App\Services\NovelStories\LautBerceritaStory;
use App\Services\NovelStories\LimaCmStory;
use App\Services\NovelStories\ManchesterUnitedStory;
use App\Services\NovelStories\Negeri5MenaraStory;
use App\Services\NovelStories\PerahuKertasStory;
use App\Services\NovelStories\PulangStory;
use App\Services\NovelStories\Ranah3WarnaStory;
use App\Services\NovelStories\RonggengDukuhParukStory;
use App\Services\NovelStories\SangPemimpiStory;
use App\Services\NovelStories\SittiNurbayaStory;
use App\Services\NovelStories\VanDerWijckStory;

class NovelStoryContent
{
    /**
     * Get rich chapter story content for a novel title.
     *
     * @return array{prologue: string, characters: array<string, string>, chapters: array<int, array{number: string, title: string, paragraphs: array<int, string>}>, epilogue: string}
     */
    public static function get(string $title): array
    {
        $normalized = strtolower(trim($title));

        if (str_contains($normalized, 'manchester united')) {
            return ManchesterUnitedStory::content();
        }

        if (str_contains($normalized, 'laskar pelangi')) {
            return LaskarPelangiStory::content();
        }
        if (str_contains($normalized, 'sang pemimpi')) {
            return SangPemimpiStory::content();
        }
        if (str_contains($normalized, 'bumi manusia')) {
            return BumiManusiaStory::content();
        }
        if (str_contains($normalized, 'anak semua bangsa')) {
            return AnakSemuaBangsaStory::content();
        }
        if (str_contains($normalized, 'negeri 5 menara')) {
            return Negeri5MenaraStory::content();
        }
        if (str_contains($normalized, 'ranah 3 warna')) {
            return Ranah3WarnaStory::content();
        }
        if (str_contains($normalized, 'hujan')) {
            return HujanStory::content();
        }
        if (str_contains($normalized, 'bumi') && ! str_contains($normalized, 'manusia')) {
            return BumiStory::content();
        }
        if (str_contains($normalized, 'bulan')) {
            return BulanStory::content();
        }
        if (str_contains($normalized, 'perahu kertas')) {
            return PerahuKertasStory::content();
        }
        if (str_contains($normalized, 'aroma karsa')) {
            return AromaKarsaStory::content();
        }
        if (str_contains($normalized, '5 cm')) {
            return LimaCmStory::content();
        }
        if (str_contains($normalized, 'tenggelamnya kapal van der wijck')) {
            return VanDerWijckStory::content();
        }
        if (str_contains($normalized, 'ronggeng dukuh paruk')) {
            return RonggengDukuhParukStory::content();
        }
        if (str_contains($normalized, 'cantik itu luka')) {
            return CantikItuLukaStory::content();
        }
        if (str_contains($normalized, 'gadis kretek')) {
            return GadisKretekStory::content();
        }
        if (str_contains($normalized, 'laut bercerita')) {
            return LautBerceritaStory::content();
        }
        if (str_contains($normalized, 'pulang')) {
            return PulangStory::content();
        }
        if (str_contains($normalized, 'dilan')) {
            return DilanStory::content();
        }
        if (str_contains($normalized, 'sitti nurbaya')) {
            return SittiNurbayaStory::content();
        }

        return self::defaultStory();
    }

    private static function defaultStory(): array
    {
        return [
            'prologue' => 'Koleksi novel sastra nusantara pilihan perpustakaan sekolah digital. Menyajikan cerita penuh sarat makna, nilai budi pekerti, dan kekayaan budaya Indonesia.',
            'characters' => [
                'Tokoh Utama' => 'Sosok sentral yang membawa pesan moral dan perjuangan hidup.',
                'Sahabat' => 'Kawan setia yang mendampingi dalam suka dan duka perjalanan.',
            ],
            'chapters' => [
                [
                    'number' => 'BAB 1',
                    'title' => 'Awal Mula Perjalanan',
                    'paragraphs' => [
                        'Di sebuah masa ketika harapan menjadi satu-satunya pelita di tengah kegelapan, langkah awal selalu dimulai dengan keberanian untuk melangkah.',
                        'Setiap peristiwa yang terhampar di hadapan adalah cerminan dari tekad jiwa yang menolak menyerah pada keterbatasan.',
                    ],
                ],
                [
                    'number' => 'BAB 2',
                    'title' => 'Menemukan Arti Kehidupan',
                    'paragraphs' => [
                        'Waktu mengajarkan bahwa kebahagiaan sejati tidak ditemukan pada kilau materi, melainkan pada ketulusan berbagi dan kesetiaan menjaga amanah.',
                    ],
                ],
            ],
            'epilogue' => 'Karya sastra adalah cermin peradaban yang membimbing nurani manusia menuju keluhuran budi pekerti.',
        ];
    }
}
