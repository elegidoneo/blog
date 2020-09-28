<?php


namespace App\Repositories;

use App\Events\UpdatePost;
use App\Models\Filters\Filters;
use App\Models\Filters\PostFilter;
use App\Models\Pagination\Pagination;
use App\Models\Pagination\PostPage;
use App\Models\Post;

class PostRepository extends Repository
{
    /**
     * @var string[]
     */
    protected $inputsStore = ["title", "body", "image_url"];

    /**
     * @var array
     */
    protected $inputsUpdate = ["title", "body", "image_url"];



    /**
     * {@inheritDoc}
     */
    protected function getModel()
    {
        if (!is_a($this->model, Post::class)) {
            $this->model = app(Post::class);
        }
        return $this->model;
    }

    /**
     * {@inheritDoc}
     */
    protected function getFilter(): Filters
    {
        if (!is_a($this->filter, PostFilter::class)) {
            $this->filter = new PostFilter($this->request);
        }

        return $this->filter;
    }

    /**
     * {@inheritDoc}
     */
    protected function getPage(): Pagination
    {
        if (!is_a($this->page, PostPage::class)) {
            $this->page = app(PostPage::class);
        }

        return $this->page;
    }

    /**
     * {@inheritDoc}
     */
    protected function withModel()
    {
        return $this->getModel()->query()->with("user");
    }

    /**
     * {@inheritDoc}
     */
    protected function eventToUpdate($model, array $before, array $after): void
    {
        event(new UpdatePost($model, $before, $after));
    }

    /**
     * {@inheritDoc}
     */
    protected function eventToCreate($model): void
    {
    }

    /**
     * {@inheritDoc}
     */
    protected function extraData(): array
    {
        return ["user_id" => $this->request->user()->id];
    }
}
