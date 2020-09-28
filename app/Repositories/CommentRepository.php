<?php


namespace App\Repositories;


use App\Models\Comment;
use App\Models\Filters\CommentFilter;
use App\Models\Filters\Filters;
use App\Models\Pagination\CommentPager;
use App\Models\Pagination\Pagination;
use Illuminate\Database\Eloquent\Builder;

class CommentRepository extends Repository
{
    /**
     * @var string[]
     */
    protected $inputsStore = ["user_id", "post_id", "comment"];

    /**
     * @var array
     */
    protected $inputsUpdate = ["user_id", "post_id", "comment"];

    /**
     * @return mixed|void
     */
    protected function getModel()
    {
        if (!is_a($this->model, Comment::class)) {
            $this->model = app(Comment::class);
        }
        return $this->model;
    }

    /**
     * @return Filters
     */
    protected function getFilter(): Filters
    {
        if (!is_a($this->filter, CommentFilter::class)) {
            $this->filter = new CommentFilter($this->request);
        }

        return $this->filter;
    }

    /**
     * @return Pagination
     */
    protected function getPage(): Pagination
    {
        if (!is_a($this->page, CommentPager::class)) {
            $this->page = app(CommentPager::class);
        }

        return $this->page;
    }

    /**
     * @return Builder
     */
    protected function withModel()
    {
        return $this->getModel()->query()->with(["user", "post"]);
    }

    /**
     * @param $model
     */
    protected function eventToCreate($model): void
    {
        //
    }

    /**
     * @return array
     */
    protected function extraData(): array
    {
        return ["user_id" => $this->request->user()->id];
    }

    /**
     * @param $model
     * @param array $before
     * @param array $after
     */
    protected function eventToUpdate($model, array $before, array $after): void
    {
        //
    }
}
