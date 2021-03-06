<?php

namespace Modules\Base\Http\Controllers;

use Assets;
use Modules\Base\Commands\ClearLogCommand;
use Modules\Base\Http\Responses\BaseHttpResponse;
use Modules\Base\Supports\Helper;
use Modules\Base\Supports\MembershipAuthorization;
use Modules\Base\Supports\SystemManagement;
use Modules\Base\Tables\InfoTable;
use Modules\Table\TableBuilder;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Throwable;

class SystemController extends Controller
{

    /**
     * @return Factory|View
     *
     * @throws Throwable
     */
    public function getInfo(Request $request, TableBuilder $tableBuilder)
    {
        page_title()->setTitle(trans('Base::system.info.title'));

        Assets::addScriptsDirectly('vendor/core/js/system-info.js')
            ->addStylesDirectly(['vendor/core/css/system-info.css']);

        $composerArray = SystemManagement::getComposerArray();
        $packages = SystemManagement::getPackagesAndDependencies($composerArray['require']);

        $infoTable = $tableBuilder->create(InfoTable::class);

        if ($request->expectsJson()) {
            return $infoTable->renderTable();
        }

        $systemEnv = SystemManagement::getSystemEnv();
        $serverEnv = SystemManagement::getServerEnv();

        return view('Base::system.info', compact(
            'packages',
            'infoTable',
            'systemEnv',
            'serverEnv'
        ));
    }

    /**
     * @return Factory|View
     */
    public function getCacheManagement()
    {
        page_title()->setTitle(trans('Base::cache.cache_management'));

        Assets::addScriptsDirectly('vendor/core/js/cache.js');

        return view('Base::system.cache');
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param ClearLogCommand $clearLogCommand
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function postClearCache(Request $request, BaseHttpResponse $response, ClearLogCommand $clearLogCommand)
    {
        if (function_exists('proc_open')) {
            switch ($request->input('type')) {
                case 'clear_cms_cache':
                    Helper::executeCommand('cache:clear');
                    break;
                case 'refresh_compiled_views':
                    Helper::executeCommand('view:clear');
                    break;
                case 'clear_config_cache':
                    Helper::executeCommand('config:clear');
                    break;
                case 'clear_route_cache':
                    Helper::executeCommand('route:clear');
                    break;
                case 'clear_log':
                    Helper::executeCommand($clearLogCommand->getName());
                    break;
            }
        }

        return $response->setMessage(trans('Base::cache.commands.' . $request->input('type') . '.success_msg'));
    }

    /**
     * @param MembershipAuthorization $authorization
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function authorize(MembershipAuthorization $authorization, BaseHttpResponse $response)
    {
        $authorization->authorize();

        return $response;
    }
}
