<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationService
{
    /**
     * @var
     */
    protected $paginatedData;

    /**
     * @var
     */
    protected $paginatorHtml;

    /**
     * Creates paginated results from the provided data set
     *
     * @param $data
     * @param int $perPage
     * @param int $currentPage
     * @return $this
     */
    public function makePaginated($data, $perPage = 10, $currentPage = 1)
    {
        // If the current page contains no results, load the final page
        $count = count($data);
        if ($count <= ($perPage * $currentPage - $perPage)) {
            $page = (string) ceil($count / $perPage);
        } else {
            $page = $currentPage;
        }

        $offset = ($page * $perPage) - $perPage;

        $paginator = new LengthAwarePaginator(
            array_slice($data, $offset, $perPage, true),
            count($data),
            $perPage,
            $page,
            [
                'path' => \Request::url(),
                'query' => \Request::query()
            ]
        );

        $this->paginatedData = $paginator->getCollection()->toArray();
        $this->paginatorHtml = $paginator->render();

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaginatedData()
    {
        return $this->paginatedData;
    }

    /**
     * @return mixed
     */
    public function getPaginatorHtml()
    {
        return $this->paginatorHtml;
    }
}