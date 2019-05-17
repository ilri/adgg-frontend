<?php

namespace backend\modules\help\controllers;

use backend\controllers\BackendController;
use backend\modules\help\Help;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;

class DefaultController extends BackendController
{
    public function init()
    {
        $this->enableDefaultAcl = false;
        // cut off the main nav menu
        $this->hideNavMenu = true;
        parent::init();
    }

    /**
     * Load help module content via ajax
     * @param $id
     * @param $perm
     * @return mixed
     * @throws BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionModule($id, $perm = 0)
    {
        $data = Help::loadHelp($id)['data'];

        // load the one requested, based on permission
        switch ($perm) {
            case Help::DELETE:
                $response = $this->renderPartial('_ajaxContent', ['contents' => $data['delete']]);
                break;
            case Help::UPDATE:
                $response = $this->renderPartial('_ajaxContent', ['contents' => $data['update']]);
                break;
            case Help::CREATE:
                $response = $this->renderPartial('_ajaxContent', ['contents' => $data['create']]);
                break;
            case Help::VIEW:
                $response = $this->renderPartial('_ajaxContent', ['contents' => $data['view']]);
                break;
            default:
                throw new BadRequestHttpException("Invalid permission type");
        }
        return Json::encode($response);
    }

    /**
     * load modules, without content
     *
     * @param $module
     * @param $action
     * @return string
     * @throws BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionContent($module, $action = null)
    {
        // this are to be displayed on the sidebar
        $allModules = Help::loadAllHelpModules();

        // load help content for the incoming module
        $data = Help::loadHelp($module);
        $activeModule = $data['module'];
        $moduleContent = $data['data'];

        return $this->render('help-content', compact('allModules', 'activeModule', 'moduleContent'));

    }

    /**
     * Search for help
     * @param $q
     * @param bool $paginate
     * @return string
     */
    public function actionSearch($q, $paginate = false)
    {
        $contents = Help::searchForHelp($q, $paginate);

        $data = $this->renderPartial('_searchResults',
            !$paginate ? compact('contents') : ['contents' => $contents['results'], 'pager' => $contents['pager']]);

        return $data;
    }

}
