<?php

namespace Knash94\Seo\Store\Eloquent\Repositories;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Knash94\Seo\Contracts\HttpRedirectsContract;
use Knash94\Seo\Services\Pagination;
use Knash94\Seo\Store\Eloquent\Models\HttpRedirect;

class HttpRedirects implements HttpRedirectsContract
{

    /**
     * @var HttpRedirect
     */
    protected $model;

    /**
     * @var Pagination
     */
    protected $pagination;

    function __construct(HttpRedirect $model, Pagination $pagination)
    {
        $this->model = $model;
        $this->pagination = $pagination;
    }

    /**
     * Gets the latest HTTP redirects
     *
     * @param string $sort
     * @param string $direction
     * @param bool $paginate
     * @param int $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getRedirects($sort = 'hits', $direction = 'desc', $paginate = true, $perPage = 12)
    {
        $query = $this->model
            ->orderBy($sort, $direction);

        if ($paginate) {
            $this->pagination->setPageParameter('redirects');

            $results = $query->paginate($perPage)->setPageName('redirects');

            $this->pagination->resetPage();

            return $results;
        }

        return $query->get();
    }

    /**
     * Returns the HTTP Redirect of a given ID
     *
     * @param $id
     * @return HttpRedirect
     */
    public function getRedirect($id)
    {
        return $this->model->find($id);
    }

    /**
     * Updates the redirect
     *
     * @param $id
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model|int|null
     */
    public function updateRedirect($id, $data)
    {
        $model = $this->getRedirect($id);

        if (!$model) {
            return null;
        }

        return $model->update($this->refineRedirectData($data, $model));
    }

    /**
     * Deletes the redirect
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     * Refines the redirect data to update or create a error's redirect
     *
     * @param $data
     * @param $model
     * @return array
     */
    protected function refineRedirectData($data, $model)
    {
        return [
            'redirect_url' => $data['redirect_url'],
            'status_code' => $data['status_code'],
            'path' => $data['path']
        ];
    }
}