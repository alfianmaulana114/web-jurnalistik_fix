<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsType;
use App\Models\NewsGenre;
use App\Models\TempImage;
use App\Models\User;
use App\Models\Proker;
use App\Models\Brief;
use App\Models\Content;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\NewsImageService;
use App\Services\EditorService;
use App\Services\ImageCropperService;
use Illuminate\Http\JsonResponse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Carbon\Carbon;

class KoordinatorJurnalistikController extends Controller
{
    private $newsImageService;
    private $editorService;
    private $cropperService;
    private $manager;

    public function __construct(
        NewsImageService $imageService,
        EditorService $editorService,
        ImageCropperService $cropperService
    ) {
        $this->newsImageService = $imageService;
        $this->editorService = $editorService;
        $this->cropperService = $cropperService;
        $this->manager = new ImageManager(new Driver());
    }
    /**
     * Safe count method that handles missing tables
     */
    private function safeCount($modelClass)
    {
        try {
            return $modelClass::count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safe query method that handles missing tables
     */
    private function safeQuery($callback, $default = null)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Display the koordinator jurnalistik dashboard.
     * Dashboard lengkap mengenai semua divisi termasuk sekretaris dan bendahara
     */
    public function dashboard(): View
    {
        // Statistik umum
        $newsCount = News::count();
        $commentCount = Comment::count();
        $userCount = User::count();
        $totalViews = News::sum('views');
        
        // Statistik per divisi
        $divisiStats = [
            'redaksi' => [
                'coordinators' => User::where('role', User::ROLE_KOORDINATOR_REDAKSI)->count(),
                'members' => User::where('role', User::ROLE_ANGGOTA_REDAKSI)->count(),
                'content' => $this->safeCount(Content::class),
                'briefs' => 0,
                'designs' => 0,
            ],
            'litbang' => [
                'coordinators' => User::where('role', User::ROLE_KOORDINATOR_LITBANG)->count(),
                'members' => User::where('role', User::ROLE_ANGGOTA_LITBANG)->count(),
                'content' => 0,
                'briefs' => $this->safeCount(Brief::class),
                'designs' => 0,
            ],
            'humas' => [
                'coordinators' => User::where('role', User::ROLE_KOORDINATOR_HUMAS)->count(),
                'members' => User::where('role', User::ROLE_ANGGOTA_HUMAS)->count(),
                'content' => 0,
                'briefs' => 0,
                'designs' => 0,
            ],
            'media_kreatif' => [
                'coordinators' => User::where('role', User::ROLE_KOORDINATOR_MEDIA_KREATIF)->count(),
                'members' => User::where('role', User::ROLE_ANGGOTA_MEDIA_KREATIF)->count(),
                'content' => 0,
                'briefs' => 0,
                'designs' => $this->safeCount(Design::class),
            ],
            'pengurus' => [
                'coordinators' => User::where('role', User::ROLE_SEKRETARIS)->count(),
                'members' => User::where('role', User::ROLE_BENDAHARA)->count(),
                'content' => 0,
                'briefs' => 0,
                'designs' => 0,
            ]
        ];
        
        // Statistik proker
        $prokerStats = [
            'total' => $this->safeCount(Proker::class),
            'active' => $this->safeQuery(function() {
                return Proker::active()->count();
            }, 0),
            'completed' => $this->safeQuery(function() {
                return Proker::completed()->count();
            }, 0),
        ];
        
        // Mengambil data statistik bulanan untuk berita
        $monthlyNews = News::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Mengambil data statistik bulanan untuk komentar
        $monthlyComments = Comment::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Menyiapkan array data bulanan lengkap
        $months = range(1, 12);
        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $newsData = array_map(function($month) use ($monthlyNews) {
            return $monthlyNews[$month] ?? 0;
        }, $months);
        
        $commentData = array_map(function($month) use ($monthlyComments) {
            return $monthlyComments[$month] ?? 0;
        }, $months);
        
        // Data terbaru
        $recentNews = News::with('user')
            ->latest()
            ->take(5)
            ->get();
            
        $recentComments = Comment::with('news')
            ->latest()
            ->take(5)
            ->get();
            
        $recentProkers = $this->safeQuery(function() {
            return Proker::with('creator')
                ->latest()
                ->take(5)
                ->get();
        }, collect());
            
        $urgentBriefs = $this->safeQuery(function() {
            return Brief::urgent()
                ->with('creator')
                ->latest()
                ->take(5)
                ->get();
        }, collect());
        
        return view('koordinator-jurnalistik.dashboard', compact(
            'newsCount',
            'commentCount',
            'userCount',
            'totalViews',
            'divisiStats',
            'prokerStats',
            'recentNews',
            'recentComments',
            'recentProkers',
            'urgentBriefs',
            'monthlyLabels',
            'newsData',
            'commentData'
        ))->with([
            'totalNews' => $newsCount,
            'totalComments' => $commentCount,
            'totalUsers' => $userCount,
            'divisionStats' => $divisiStats
        ]);
    }

    // News Management Methods
    public function newsIndex(): View
    {
        $news = News::with(['user', 'category', 'type', 'genres'])
                    ->latest()
                    ->paginate(10);
        return view('koordinator-jurnalistik.news.index', compact('news'));
    }

    public function newsCreate(): View
    {
        $categories = NewsCategory::all();
        $types = NewsType::all();
        $genres = NewsGenre::all();
        
        return view('koordinator-jurnalistik.news.create', [
            'categories' => $categories,
            'types' => $types,
            'genres' => $genres,
            'editorStyles' => $this->editorService->getEditorStyles(),
            'editorScripts' => $this->editorService->getEditorScripts(),
            'cropperStyles' => $this->cropperService->getCropperStyles(),
            'cropperScripts' => $this->cropperService->getCropperScripts()
        ]);
    }

    public function newsStore(Request $request): RedirectResponse
    {
        $validatedData = $this->validateNews($request);
        
        // Inisialisasi image dengan null
        $imagePath = null;
        
        // Cek jika ada temp_image_id
        if ($request->has('temp_image_id')) {
            $tempImage = TempImage::find($request->temp_image_id);
            if ($tempImage) {
                // Pindahkan gambar dari folder temp ke folder news
                $newPath = 'images/news/' . $tempImage->filename;
                if (rename(public_path($tempImage->path), public_path($newPath))) {
                    $imagePath = $newPath;
                    // Hapus record temp image
                    $tempImage->delete();
                }
            }
        }
        
        $news = News::create([
            'title' => $validatedData['title'],
            'slug' => Str::slug($validatedData['title']),
            'content' => $validatedData['content'],
            'meta_description' => $validatedData['meta_description'],
            'tags' => $validatedData['tags'],
            'keyword' => $validatedData['keyword'],
            'user_id' => auth()->id() ?? 1,
            'news_category_id' => $validatedData['news_category_id'],
            'news_type_id' => $validatedData['news_type_id'],
            'image' => $imagePath
        ]);
    
        if (!empty($validatedData['genre_ids'])) {
            $news->genres()->attach($validatedData['genre_ids']);
        }
    
        return redirect()->route('koordinator-jurnalistik.news.index')
                        ->with('success', 'Berita berhasil ditambahkan');
    }

    public function newsShow($id): View
    {
        $news = News::with(['user', 'category', 'type', 'genres'])->findOrFail($id);
        return view('koordinator-jurnalistik.news.show', compact('news'));
    }

    public function newsEdit($id): View
    {
        $news = News::with('genres')->findOrFail($id);
        $categories = NewsCategory::all();
        $types = NewsType::all();
        $genres = NewsGenre::all();
        
        return view('koordinator-jurnalistik.news.edit', [
            'news' => $news,
            'categories' => $categories,
            'types' => $types,
            'genres' => $genres,
            'editorStyles' => $this->editorService->getEditorStyles(),
            'editorScripts' => $this->editorService->getEditorScripts(),
            'cropperStyles' => $this->cropperService->getCropperStyles(),
            'cropperScripts' => $this->cropperService->getCropperScripts()
        ]);
    }

    public function newsUpdate(Request $request, $id): RedirectResponse
    {
        $validatedData = $this->validateNews($request, $id);
        $news = News::findOrFail($id);
    
        $news->update([
            'title' => $validatedData['title'],
            'slug' => Str::slug($validatedData['title']),
            'content' => $validatedData['content'],
            'meta_description' => $validatedData['meta_description'],
            'tags' => $validatedData['tags'],
            'keyword' => $validatedData['keyword'],
            'news_category_id' => $validatedData['news_category_id'],
            'news_type_id' => $validatedData['news_type_id']
        ]);
    
        // Handle image from temp
        if ($request->has('temp_image_id')) {
            $tempImage = TempImage::find($request->temp_image_id);
            if ($tempImage) {
                // Delete old image if exists
                if ($news->image && file_exists(public_path($news->image))) {
                    unlink(public_path($news->image));
                }
                
                // Move image from temp to permanent location
                $newPath = 'images/news/' . $tempImage->filename;
                rename(public_path($tempImage->path), public_path($newPath));
                
                $news->image = $newPath;
                $news->save();
                
                // Delete temp record
                $tempImage->delete();
            }
        } else if ($request->hasFile('image')) {
            // Fallback to old method if no temp_image_id
            if ($news->image) {
                $this->newsImageService->delete($news->image);
            }
            
            // Konversi ke WebP menggunakan Intervention Image v3
            $image = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.webp';
            
            $img = $this->manager->read($image->getRealPath());
            $img->toWebp(80)->save(public_path('images/news/' . $filename));
            
            $news->image = 'images/news/' . $filename;
            $news->save();
        }
    
        $news->genres()->sync($validatedData['genre_ids']);
    
        return redirect()->route('koordinator-jurnalistik.news.index')
                        ->with('success', 'Berita berhasil diperbarui');
    }

    public function newsDestroy($id): RedirectResponse
    {
        $news = News::findOrFail($id);
        
        if ($news->image) {
            $this->newsImageService->delete($news->image);
        }
        
        $news->genres()->detach();
        $news->delete();

        return redirect()->route('koordinator-jurnalistik.news.index')
                        ->with('success', 'Berita berhasil dihapus');
    }

    public function uploadImage(Request $request): JsonResponse
    {
        if ($request->hasFile('image')) {
            $path = $this->newsImageService->store($request->file('image'));
            return response()->json(asset($path));
        }
        return response()->json('Upload failed', 400);
    }

    private function validateNews(Request $request, $id = null): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_description' => 'required|string|max:160',
            'tags' => 'required|string',
            'keyword' => 'nullable|string',
            'news_category_id' => 'required|exists:news_categories,id',
            'news_type_id' => 'required|exists:news_types,id',
            'genre_ids' => 'required|array|exists:news_genres,id'
        ];
    
        return $request->validate($rules);
    }
}