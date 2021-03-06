<?php

namespace Modules\Menu\Repositories\Eloquent;

use Modules\Base\Enums\BaseStatusEnum;
use Modules\Menu\Repositories\Interfaces\MenuInterface;
use Modules\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Support\Str;

class MenuRepository extends RepositoriesAbstract implements MenuInterface
{
    /**
     * {@inheritdoc}
     */
    public function findBySlug($slug, $active, $selects = [])
    {
        $data = $this->model->where('menus.slug', '=', $slug);
        if ($active) {
            $data = $data->where('menus.status', '=', BaseStatusEnum::PUBLISHED)->select($selects);
        }
        $data = $this->applyBeforeExecuteQuery($data, true)->first();

        $this->resetModel();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function createSlug($name)
    {
        $slug = Str::slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->model->where('menus.slug', $slug)->count() > 0) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = time();
        }

        $this->resetModel();

        return $slug;
    }
}
