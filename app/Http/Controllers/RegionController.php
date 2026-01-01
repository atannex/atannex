<?php

namespace App\Http\Controllers;

use App\Enums\Flag;
use Illuminate\View\View;
use Atannex\Binders\HasView;
use App\Models\Posts\Post;
use App\Models\Tags\Tag;
use App\Models\Regions\Category;
use App\Models\Regions\Employee;
use Atannex\Concerns\HasResolver;
use Atannex\Services\RegionService;
use Atannex\Concerns\HasDocument;
use Atannex\Traits\HasGlobal;

class RegionController extends Controller
{
    use HasResolver;
    use HasGlobal;
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
        /**
         * 1️⃣ Document listing pages (explicit slugs)
         */
        if ($this->isSupportedDocumentType($slug) || $slug === 'testimonials') {
            return view('documents.index', [
                'documents'           => $this->getDocumentsBySlug($slug),
                'type'                => $slug,
                'isValidDocumentType' => $this->isSupportedDocumentType($slug),
                'isTestimonialType'   => $slug === 'testimonials',
            ]);
        }

        /**
         * 2️⃣ Single document page (exact match)
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

        /**
         * 3️⃣ Post by date (year/month routes)
         */
        if ($date = $this->resolvePostByDate($slug)) {
            return $this->viewBinder->renderDateView(
                year: $date['year'],
                month: $date['month'],
                type: $date['type']
            );
        }

        /**
         * 4️⃣ Single post
         */
        if ($this->postExists($slug)) {
            $post = Post::published()
                ->where('slug_path', $slug)
                ->firstOrFail();

            return $this->viewBinder->renderPostShow(
                $post->category,
                $slug
            );
        }

        /**
         * 5️⃣ Author
         */
        if ($this->authorExists($slug)) {
            $author = Employee::with('user')
                ->whereHas('user', fn($q) => $q->where('slug', $slug))
                ->firstOrFail();

            return $this->viewBinder->renderAuthorView($author);
        }

        /**
         * 6️⃣ Tag
         */
        if ($this->tagExists($slug)) {
            $tag = Tag::where('slug', $slug)->firstOrFail();

            return $this->viewBinder->renderTagView($tag);
        }

        /**
         * 7️⃣ Category
         */
        if ($this->categoryExists($slug)) {
            $category = Category::where([
                ['flag', Flag::PUBLISHED],
                ['slug_path', $slug],
            ])->firstOrFail();

            return $this->viewBinder->renderCategoryView($category);
        }

        /**
         * 8️⃣ Region (most generic, highest collision risk)
         */
        if ($region = $this->regionService->getRegionBySlug($slug)) {
            return $this->viewBinder->renderRegionView($region);
        }

        abort(404);
    }
}
