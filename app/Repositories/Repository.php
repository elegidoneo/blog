<?php


namespace App\Repositories;


use App\Models\Filters\Filters;
use App\Models\Pagination\Pagination;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class Repository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Filters
     */
    protected $filter;

    /**
     * @var Pagination
     */
    protected $page;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $inputsStore = [];

    /**
     * @var array
     */
    protected $inputsUpdate = [];

    /**
     * Repository constructor.
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        $filter = $this->getFilter()->apply($this->withModel());
        $paginate = $this->getPage()
            ->setOrderBy($this->request->input('orderBy', "created_at"))
            ->setOrderDir($this->request->input("orderDir", "DESC"));
        return $this->request->has("pages") ?
            $paginate->setPages($this->request->input("pages"))->paged($filter) : $filter->get();
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $model = $this->getModel()->create(array_merge($this->request->only($this->inputsStore), $this->extraData()));
        $this->eventToCreate($model);
        return $model;
    }

    /**
     * @param $model
     * @return mixed
     */
    public function destroy($model)
    {
        $before = $model->toArray();
        $model->delete();
        return $before;
    }

    /**
     * @param $model
     * @return Model
     */
    public function update($model)
    {
        $before = $model->toArray();
        $model->update($this->request->only($this->inputsUpdate));
        $after = $model->toArray();
        $this->eventToUpdate($model, $before, $after);
        return $model;
    }

    /**
     * @return mixed
     */
    abstract protected function getModel();

    /**
     * @return Filters
     */
    abstract protected function getFilter(): Filters;

    /**
     * @return Pagination
     */
    abstract protected function getPage(): Pagination;

    /**
     * @return Builder
     */
    abstract protected function withModel();

    /**
     * @param $model
     */
    abstract protected function eventToCreate($model): void;

    /**
     * @return array
     */
    abstract protected function extraData(): array;

    /**
     * @param $model
     * @param array $before
     * @param array $after
     * @return void
     */
    abstract protected function eventToUpdate($model, array $before, array $after): void;
}
