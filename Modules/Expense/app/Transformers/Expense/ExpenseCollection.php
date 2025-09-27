<?php

namespace Modules\Expense\Transformers\Expense;


use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function paginationInformation($request, $paginated, $default)
    {
        unset($default['links']);
        unset($default['meta']);
        return $default;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data'                => ExpenseResource::collection($this->collection)->resolve($request),
            'pagination'          => [
                "current_page"    => $this->currentPage(),
                "total_pages"     =>  $this->lastPage(),
                "per_page"        =>  $this->perPage(),
                "total_items"     =>  $this->total(),
            ],
        ];
    }
}
