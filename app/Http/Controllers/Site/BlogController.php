<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use App\Services\WhatsApp\LinkBuilder;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(): Response
    {
        seo()->page('blog')
            ->crumb(__('Home'), lroute('home'))->crumb(__('Blog'), lroute('blog.index'));

        return $this->listing(Post::query(), null);
    }

    public function category(string $slug): Response
    {
        $category = PostCategory::query()->where('slug', $slug)->firstOrFail();

        seo()->title(__(':name Articles', ['name' => $category->name]))
            ->crumb(__('Home'), lroute('home'))->crumb(__('Blog'), lroute('blog.index'))->crumb((string) $category->name, lroute('blog.category', $category->slug));

        return $this->listing(Post::query()->where('post_category_id', $category->id), (string) $category->name);
    }

    public function tag(string $slug): Response
    {
        $tag = Tag::query()->where('slug', $slug)->firstOrFail();

        seo()->title(__('Articles tagged :name', ['name' => $tag->name]))
            ->crumb(__('Home'), lroute('home'))->crumb(__('Blog'), lroute('blog.index'))->crumb((string) $tag->name, lroute('blog.tag', $tag->slug));

        return $this->listing(Post::query()->whereHas('tags', fn (Builder $q) => $q->whereKey($tag->id)), (string) $tag->name);
    }

    public function show(string $slug, LinkBuilder $whatsapp): Response
    {
        $post = Post::query()->published()->where('slug', $slug)->with(['author', 'category', 'tags', 'media', 'seo'])->firstOrFail();
        $cover = $post->image('cover');

        seo()->for($post, (string) $post->title, $post->excerpt ?: $post->body, $cover['large'] ?? null)->type('article')
            ->crumb(__('Home'), lroute('home'))->crumb(__('Blog'), lroute('blog.index'))->crumb((string) $post->title, lroute('blog.show', $post->slug))
            ->schema(seo()->builder()->article((string) $post->title, $post->excerpt ?: $post->body, lroute('blog.show', $post->slug), $cover['large'] ?? null,
                $post->author?->name, $post->published_at?->toIso8601String(), $post->updated_at?->toIso8601String()));

        $related = Post::query()->published()->whereKeyNot($post->id)
            ->when($post->post_category_id, fn (Builder $q) => $q->where('post_category_id', $post->post_category_id))
            ->latest('published_at')->with(['category', 'media'])->limit(3)->get();

        return Inertia::render('Site/Blog/Show', [
            'post' => (new PostResource($post))->full()->resolve(),
            'related' => PostResource::collection($related)->resolve(),
            'whatsappUrl' => $whatsapp->url(),
        ]);
    }

    private function listing(Builder $query, ?string $heading): Response
    {
        $posts = $query->published()->latest('published_at')->with(['author', 'category', 'media'])->paginate(12)->withQueryString();

        return Inertia::render('Site/Blog/Index', [
            'intro' => $heading ? ['heading' => $heading, 'lead' => null] : page_intro('blog'),
            'heading' => $heading,
            'posts' => PostResource::collection($posts->getCollection())->resolve(),
            'pagination' => [
                'current' => $posts->currentPage(),
                'last' => $posts->lastPage(),
                'prev' => $posts->previousPageUrl(),
                'next' => $posts->nextPageUrl(),
            ],
            'categories' => PostCategory::query()->ordered()->has('posts')->get()
                ->map(fn (PostCategory $c) => ['name' => $c->name, 'url' => lroute('blog.category', $c->slug)])->all(),
        ]);
    }
}
