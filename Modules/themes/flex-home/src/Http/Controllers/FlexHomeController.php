<?php

namespace Theme\FlexHome\Http\Controllers;

use Modules\Base\Http\Responses\BaseHttpResponse;
use Modules\Theme\Http\Controllers\PublicController;

class FlexHomeController extends PublicController
{
    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getIndex(BaseHttpResponse $response)
    {
        return parent::getIndex($response);
    }

    /**
     * @param BaseHttpResponse $response
     * @param null $key
     * @return BaseHttpResponse|\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getView(BaseHttpResponse $response, $key = null)
    {
        return parent::getView($response, $key);
    }

    /**
     * @return mixed
     */
    public function getSiteMap()
    {
        return parent::getSiteMap();
    }
}
