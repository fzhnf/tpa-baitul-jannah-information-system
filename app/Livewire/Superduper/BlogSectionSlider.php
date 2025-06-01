<?php

namespace App\Livewire\SuperDuper;

use App\Models\Blog\Post;
use App\Models\Blog\Category;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\App;

class BlogSectionSlider extends Component
{
    public $articles = [];
    public $activeCategory = null;
    public $categories = [];
    public $featuredOnly = false;
    public $limit = 5;
    /**
     * @param mixed $limit
     * @param mixed $featuredOnly
     * @param mixed $categorySlug
     */
    public function mount($limit = 5, $featuredOnly = false, $categorySlug = null): void
    {
        $this->limit = $limit;
        $this->featuredOnly = $featuredOnly;

        // Get all active categories
        $this->categories = Category::active()
            ->with(['posts' => function ($query) {
                $query->select('category_id');
            }])
            ->withCount('posts')
            ->whereHas('posts', function ($query) {
                $query->where('id', '>', 0); // Or any condition that ensures posts exist
            })
            ->orderBy('name')
            ->get();

        // Set active category if provided
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $this->activeCategory = $category->id;
            }
        }

        $this->loadArticles();
    }
    /**
     * @param mixed $categoryId
     */
    public function filterByCategory($categoryId = null): void
    {
        $this->activeCategory = $categoryId;
        $this->loadArticles();
    }

    public function toggleFeatured(): void
    {
        $this->featuredOnly = !$this->featuredOnly;
        $this->loadArticles();
    }

    public function loadArticles(): void
    {
        $query = Post::with(['category', 'author', 'media'])
            ->published()
            ->locale(App::getLocale());

        if ($this->featuredOnly) {
            $query->featured();
        }

        if ($this->activeCategory) {
            $query->where('blog_category_id', $this->activeCategory);
        }

        $this->articles = $query->orderBy('published_at', 'desc')
            ->limit($this->limit)
            ->get();
    }
    /**
     * @param mixed $postId
     */
    public function trackView($postId): void
    {
        $post = Post::find($postId);
        if ($post) {
            $post->trackView();
        }
    }

    public function render(): View
    {
        return view('livewire.superduper.blog-section-slider');
    }
}
