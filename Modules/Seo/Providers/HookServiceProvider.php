<?php

namespace Modules\Seo\Providers;

use Assets;
use Illuminate\Support\ServiceProvider;
use SeoHelper;

class HookServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_action(BASE_ACTION_META_BOXES, [$this, 'addMetaBox'], 12, 2);
        add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, [$this, 'setSeoMeta'], 56, 2);
    }

    /**
     * @param $screen
     */
    public function addMetaBox($priority, $data)
    {
        if (!empty($data) && in_array(get_class($data), config('Seo.general.supported', []))) {
            Assets::addScriptsDirectly('vendor/core/packages/seo-helper/js/seo-helper.js');
            add_meta_box('seo_wrap', trans('Seo::seo-helper.meta_box_header'), [$this, 'seoMetaBox'],
                get_class($data), 'advanced', 'low');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seoMetaBox()
    {
        $meta = [
            'seo_title'       => null,
            'seo_description' => null,
        ];

        $args = func_get_args();
        if (!empty($args[0]) && $args[0]->id) {
            $metadata = get_meta_data($args[0], 'seo_meta', true);
        }

        if (!empty($metadata) && is_array($metadata)) {
            $meta = array_merge($meta, $metadata);
        }

        $object = $args[0];

        return view('Seo::meta_box', compact('meta', 'object'));
    }

    /**
     * @param $object
     */
    public function setSeoMeta($screen, $object)
    {
        $meta = get_meta_data($object, 'seo_meta', true);
        if (!empty($meta)) {
            if (!empty($meta['seo_title'])) {
                SeoHelper::setTitle($meta['seo_title']);
            }

            if (!empty($meta['seo_description'])) {
                SeoHelper::setDescription($meta['seo_description']);
            }
        }
    }
}
