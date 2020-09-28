<?php


namespace App\Repositories;


use App\Events\UpdateUser;
use App\Models\Filters\Filters;
use App\Models\Filters\UserFilter;
use App\Models\Pagination\Pagination;
use App\Models\Pagination\UserPager;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class UserRepository extends Repository
{

    /**
     * @var string[]
     */
    protected $inputsStore = [
        "name", "email", "password",
    ];

    /**
     * @var array
     */
    protected $inputsUpdate = ["name",];

    /**
     * {@inheritDoc}
     */
    protected function getModel()
    {
        if (!is_a($this->model, User::class)) {
            $this->model = app(User::class);
        }
        return $this->model;
    }

    /**
     * {@inheritDoc}
     */
    protected function getFilter(): Filters
    {
        if (!is_a($this->filter, UserFilter::class)) {
            $this->filter = new UserFilter($this->request);
        }

        return $this->filter;
    }

    /**
     * {@inheritDoc}
     */
    protected function getPage(): Pagination
    {
        if (!is_a($this->page, UserPager::class)) {
            $this->page = app(UserPager::class);
        }

        return $this->page;
    }

    /**
     * {@inheritDoc}
     */
    protected function withModel()
    {
        return $this->getModel()->query();
    }

    /**
     * {@inheritDoc}
     */
    protected function eventToUpdate($model, array $before, array $after): void
    {
        event(new UpdateUser($model));
    }

    /**
     * {@inheritDoc}
     */
    protected function eventToCreate($model): void
    {
        event(new Registered($model));
    }

    /**
     * {@inheritDoc}
     */
    protected function extraData(): array
    {
        return [];
    }
}
