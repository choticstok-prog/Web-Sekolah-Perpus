<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Services\BookPdfService;
use App\Services\NovelStoryContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CatalogController extends Controller
{
    /**
     * Display a listing of books in the catalog.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categorySlug = $request->query('kategori');
        $kelas = $request->query('kelas');
        $year = $request->query('tahun');
        $bacaOnline = $request->boolean('baca_online');

        $query = Book::with('category')->latest();

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('year', 'like', "%{$search}%");
            });
        }

        if ($year) {
            $query->where('year', $year);
        }

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug): void {
                $q->where('slug', $categorySlug);
            });
        }

        if ($kelas) {
            $query->where(function ($q) use ($kelas): void {
                $q->where('title', 'like', "%Kelas {$kelas}%")
                    ->orWhere('title', 'like', '%Kelas '.match ($kelas) {
                        '7', 'VII' => 'VII',
                        '8', 'VIII' => 'VIII',
                        '9', 'IX' => 'IX',
                        '10', 'X' => 'X',
                        '11', 'XI' => 'XI',
                        '12', 'XII' => 'XII',
                        default => $kelas,
                    }.'%')
                    ->orWhere('title', 'like', '%Kelas '.match ($kelas) {
                        'VII' => '7',
                        'VIII' => '8',
                        'IX' => '9',
                        'X' => '10',
                        'XI' => '11',
                        'XII' => '12',
                        default => $kelas,
                    }.'%');
            });
        }

        if ($bacaOnline) {
            $query->whereNotNull('pdf_url');
        }

        $books = $query->paginate(9)->withQueryString();
        $categories = Category::withCount('books')->orderBy('name')->get();
        $selectedCategory = $categorySlug ? Category::where('slug', $categorySlug)->first() : null;
        $availableYears = Book::select('year')->whereNotNull('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        $stats = [
            'total_books' => Book::count(),
            'total_online' => Book::whereNotNull('pdf_url')->count(),
            'total_categories' => Category::count(),
        ];

        return view('catalog.index', [
            'books' => $books,
            'categories' => $categories,
            'availableYears' => $availableYears,
            'selectedCategory' => $selectedCategory,
            'search' => $search,
            'kelas' => $kelas,
            'tahun' => $year,
            'bacaOnline' => $bacaOnline,
            'stats' => $stats,
        ]);
    }

    /**
     * Display the specified book details.
     */
    public function show(Book $book): View
    {
        $book->load('category');
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        return view('catalog.show', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
        ]);
    }

    /**
     * Display the online book reader / e-reader.
     */
    public function read(Book $book): View|RedirectResponse
    {
        if (! auth()->check()) {
            return redirect()->guest(route('register'))
                ->with('info', 'Untuk mulai membaca buku di E-Reader, silakan daftar akun terlebih dahulu (gratis & cepat)!');
        }

        $book->load('category');
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        $novelStory = null;
        if (($book->category && in_array($book->category->slug, ['novel-fiksi', 'sejarah-peminatan-dunia'])) || str_contains(strtolower($book->title), 'manchester united') || str_starts_with($book->cover_image ?? '', '/images/covers/novel-')) {
            $novelStory = NovelStoryContent::get($book->title);
        }

        return view('catalog.read', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
            'novelStory' => $novelStory,
        ]);
    }

    /**
     * Stream the book PDF with proper headers and zero CORS issues.
     */
    public function pdf(Book $book, BookPdfService $pdfService): BinaryFileResponse|RedirectResponse
    {
        if (! empty($book->pdf_url) && filter_var($book->pdf_url, FILTER_VALIDATE_URL)) {
            return redirect()->away($book->pdf_url);
        }

        $filePath = $pdfService->getOrGeneratePdf($book);

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.str($book->title)->slug().'.pdf"',
            'Access-Control-Allow-Origin' => '*',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * Stream real-time Indonesian TTS audio for Web Audio API & Volume Booster up to 500%.
     */
    public function ttsStream(Request $request): Response
    {
        $text = trim((string) $request->query('text', ''));
        if ($text === '') {
            return response('', 400);
        }

        $text = mb_substr($text, 0, 200);
        $url = 'https://translate.google.com/translate_tts?ie=UTF-8&tl=id&client=tw-ob&q='.urlencode($text);

        try {
            $response = Http::withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36')
                ->timeout(5)
                ->get($url);

            if ($response->successful() && strlen($response->body()) > 100) {
                return response($response->body(), 200, [
                    'Content-Type' => 'audio/mpeg',
                    'Access-Control-Allow-Origin' => '*',
                    'Cache-Control' => 'public, max-age=604800',
                ]);
            }
        } catch (\Throwable $e) {
            // Silently fallback on failure
        }

        return response('TTS Service Unavailable', 503);
    }
}
