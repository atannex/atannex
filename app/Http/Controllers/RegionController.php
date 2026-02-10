<?php

namespace App\Http\Controllers;

use App\Enums\Flag;
use App\Models\Tags\Tag;
use Illuminate\View\View;
use App\Models\Posts\Post;
use Atannex\Binders\HasView;
use Atannex\Traits\HasGlobal;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use Atannex\Concerns\HasDocument;
use Atannex\Concerns\HasResolver;
use Atannex\Services\RegionService;
use Atannex\Traits\HandlesPostDateResolution;

class RegionController extends Controller
{
    use HasResolver;
    use HasGlobal;
    use HandlesPostDateResolution;
    use HasDocument;

    public function __construct(
        protected readonly RegionService $regionService,
        protected readonly HasView $viewBinder,
    ) {}

    /**
     * Resolve a slug into its corresponding content entity.
     */
    public function resolve(string $slug): View
    {

        /*
     |--------------------------------------------------------------------------
     | 1️⃣ Explicit document listing pages (lowest collision risk)
     |--------------------------------------------------------------------------
     */
        if ($this->isSupportedDocumentType($slug) || $slug === 'testimonials') {
            return view('documents.index', [
                'documents'           => $this->getDocumentsBySlug($slug),
                'type'                => $slug,
                'isValidDocumentType' => $this->isSupportedDocumentType($slug),
                'isTestimonialType'   => $slug === 'testimonials',
            ]);
        }

        /*
     |--------------------------------------------------------------------------
     | 2️⃣ Single document page (exact match)
     |--------------------------------------------------------------------------
     */
        if ($this->documentExists($slug)) {
            $document = $this->getDocumentByPath($slug);
            $module   = $this->getDocumentModule($slug);

            abort_if(! $module, 404);

            return view('documents.show', [
                'module'    => $module,
                'seoTitle'  => $document->title,
                'type'      => $document->slug,
                'documents' => $this->getRelatedDocuments($slug),
            ]);
        }

        /*
     |--------------------------------------------------------------------------
     | 3️⃣ Date archives (YYYY / YYYY-MM)
     |--------------------------------------------------------------------------
     */
        if ($archive = $this->resolvePostArchiveBySlug($slug)) {
            return $this->viewBinder->renderDateView($archive['year'], $archive['month'], $archive['type']);
        }

        /*
     |--------------------------------------------------------------------------
     | 4️⃣ Single post
     |--------------------------------------------------------------------------
     */
        if ($this->postExists($slug)) {
            $post = Post::published()
                ->where('slug_path', $slug)
                ->firstOrFail();

            return $this->viewBinder->renderPostShow($post->category, $slug);
        }

        /*
     |--------------------------------------------------------------------------
     | 5️⃣ Author
     |--------------------------------------------------------------------------
     */
        if ($this->authorExists($slug)) {
            $author = Employee::with('user')
                ->whereHas('user', fn($q) => $q->where('slug', $slug))
                ->firstOrFail();

            return $this->viewBinder->renderAuthorView($author);
        }

        /*
     |--------------------------------------------------------------------------
     | 6️⃣ Tag
     |--------------------------------------------------------------------------
     */
        if ($this->tagExists($slug)) {
            $tag = Tag::where('slug', $slug)->firstOrFail();

            return $this->viewBinder->renderTagView($tag);
        }

        /*
     |--------------------------------------------------------------------------
     | 7️⃣ Category
     |--------------------------------------------------------------------------
     */
        if ($this->categoryExists($slug)) {
            $category = Category::where([
                ['flag', Flag::PUBLISHED],
                ['slug_path', $slug],
            ])->firstOrFail();

            return $this->viewBinder->renderCategoryView($category);
        }

        /*
     |--------------------------------------------------------------------------
     | 8️⃣ Region (most generic — LAST)
     |--------------------------------------------------------------------------
     */
        if ($region = $this->regionService->getRegionBySlug($slug)) {
            return $this->viewBinder->renderRegionView($region);
        }

        abort(404);
    }
}
