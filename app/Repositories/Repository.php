<?php

namespace App\Repositories;

abstract class Repository
{
    protected $pdoBindings = [];
    protected $whereStatements = '';
    protected $orderBy;
    protected $orderByDirection;

    /**
     * Prepares where statements and PDO bindings to be applied to the query later
     *
     * @param array $urlParams
     * @param array $whereParams
     * @return $this
     */
    public function prepareQueryFilters($urlParams = [], $whereParams = [])
    {
        // Reset
        $this->pdoBindings = [];
        $this->whereStatements = '';

        foreach ($urlParams as $param => $searchValue) {

            if (!isset($whereParams[$param])) {
                $whereParams[$param] = [];
            }

            $this->addWhereStatementAndBindingForUrlParam($searchValue, $whereParams[$param]);
        }

        return $this;
    }

    /**
     * Set the column and direction to order the query results by
     *
     * @param $orderByField
     * @param $direction
     * @param array $sortableFields
     * @return $this
     */
    public function orderBy($orderByField, $direction, array $sortableFields)
    {
        if (empty($orderByField)) {
            // Get the default order by field
            foreach ($sortableFields as $field) {
                if (isset($field['default']) and $field['default'] === true) {
                    $this->orderBy = $field['field'];
                    $this->orderByDirection = 'asc';

                    return $this;
                }
            }
        }

        $this->orderBy = $orderByField;
        $this->orderByDirection = (empty($direction) ? 'asc' : $direction);

        return $this;
    }

    /**
     * Create any where statements and PDO bindings from the URL
     * parameters, to be used to filter the search results
     *
     * @param $searchValue
     * @param array $whereParam
     */
    protected function addWhereStatementAndBindingForUrlParam($searchValue, array $whereParam)
    {
        // Ignore if no filter parameter was provided
        if (empty($searchValue) or empty($whereParam)) {
            return;
        }

        // Add 'where' filter
        $bindingName = $whereParam['urlParam'];
        $this->whereStatements .= $whereParam['whereStatement'];

        // Add PDO binding
        $this->pdoBindings[$bindingName] = $searchValue;
    }

}