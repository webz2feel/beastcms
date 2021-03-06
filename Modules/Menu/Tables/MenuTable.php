<?php

namespace Modules\Menu\Tables;

use Modules\Menu\Entities\Menu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Base\Enums\BaseStatusEnum;
use Modules\Menu\Repositories\Interfaces\MenuInterface;
use Modules\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Validation\Rule;
use Throwable;
use Yajra\DataTables\DataTables;

class MenuTable extends TableAbstract
{
    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * MenuTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param MenuInterface $menuRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, MenuInterface $menuRepository)
    {
        $this->repository = $menuRepository;
        $this->setOption('id', 'table-menus');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasAnyPermission(['menus.edit', 'menus.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * Display ajax response.
     *
     * @return JsonResponse
     *
     * @since 2.1
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('menus.edit')) {
                    return $item->name;
                }

                return anchor_link(route('menus.edit', $item->id), $item->name);
            })
            ->editColumn('checkbox', function ($item) {
                return table_checkbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return date_from_database($item->created_at, config('Base.general.date_format.date'));
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return table_actions('menus.edit', 'menus.destroy', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Get the query object to be processed by the table.
     *
     * @return \Illuminate\Database\Query\Builder|Builder
     *
     * @since 2.1
     */
    public function query()
    {
        $model = $this->repository->getModel();

        $query = $model
            ->select([
                'menus.id',
                'menus.name',
                'menus.created_at',
                'menus.status',
            ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model));
    }

    /**
     * @return array
     *
     * @since 2.1
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'menus.id',
                'title' => trans('Base::tables.id'),
                'width' => '20px',
            ],
            'name'       => [
                'name'  => 'menus.name',
                'title' => trans('Base::tables.name'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'menus.created_at',
                'title' => trans('Base::tables.created_at'),
                'width' => '100px',
            ],
            'status'     => [
                'name'  => 'menus.status',
                'title' => trans('Base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * @return array
     *
     * @throws Throwable
     * @since 2.1
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('menus.create'), 'menus.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Menu::class);
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('menus.deletes'), 'menus.destroy', parent::bulkActions());
    }

    /**
     * @return array
     */
    public function getBulkChanges(): array
    {
        return [
            'menus.name'       => [
                'title'    => trans('Base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'menus.status'     => [
                'title'    => trans('Base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(BaseStatusEnum::values()),
            ],
            'menus.created_at' => [
                'title' => trans('Base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
