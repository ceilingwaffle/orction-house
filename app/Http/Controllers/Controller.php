<?php

namespace App\Http\Controllers;

use App\Services\PaginationService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Input;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * @var PaginationService
     */
    private $paginator;

    /**
     * Returns an array containing the paginator HTML and page data
     *
     * @param $data
     * @param int $perPage
     * @param string $pageParamName
     * @return array
     */
    protected function preparePaginator($data, $perPage = 4, $pageParamName = 'page')
    {
        $this->paginator = new PaginationService();
        $loadPageNum = Input::get($pageParamName, 1);
        $paginator = $this->paginator->makePaginated($data, $perPage, $loadPageNum);
        $data = $paginator->getPaginatedData();
        $paginator = $paginator->getPaginatorHtml();

        return array($paginator, $data);
    }

}
