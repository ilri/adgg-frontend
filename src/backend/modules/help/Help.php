<?php

namespace backend\modules\help;

use backend\modules\help\models\HelpContent;
use backend\modules\help\models\HelpModules;
use common\helpers\Url;
use Illuminate\Support\Str;
use Yii;
use yii\base\ViewContextInterface;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class Help
 * @package backend\modules\help
 */
class Help
{
    const CREATE = 1;
    const UPDATE = 2;
    const VIEW = 3;
    const DELETE = 4;

    // key references for default module, that only has general content
    const DEFAULT_MODULE = 'none';
    // this is needed in the db first
    const DEFAULT_SLUG = 'general-information';

    // acl stuff
    const ENFORCE_ACL = false;

    // default permissions available. This is unlikely to change
    public static $permissions = [
        Help::CREATE => 'create',
        Help::UPDATE => 'update',
        Help::VIEW => 'view',
        Help::DELETE => 'delete'
    ];

    public static $skippedModules = [
        'Help'
    ];

    /**
     * Get the relevant permission based on controller action name
     * @param $action
     * @return mixed
     */
    public static function getAction($action)
    {
        // do we need acl?
        if (!self::ENFORCE_ACL) {
            return ['0'];
        }
        return self::fetchActionInternal($action);
    }

    /**
     * load a single help module
     *
     * @param $module
     * @return \common\models\ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function loadSingleHelpModule($module)
    {
        // check if default
        if ($module === self::DEFAULT_MODULE) {
            return self::loadDefaultModule();
        }
        // find the module in the db
        $helpModule = HelpModules::loadModel(['name' => $module]);
        return $helpModule;
    }

    /**
     * Load the default module
     * @return \common\models\ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function loadDefaultModule()
    {
        return HelpModules::loadModel(['slug' => Help::DEFAULT_SLUG]);
    }

    /**
     * Check if a module is default. We can only have one for now
     * This one will be used for stuff like display general help
     *
     * @param $module
     * @return bool
     */
    public static function isDefault($module)
    {
        return $module->slug === static::DEFAULT_SLUG;
    }

    /**
     * Load all help modules
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function loadAllHelpModules()
    {
        return HelpModules::find()->all();
    }

    /**
     * Allow users to search for help
     *
     * @param $keywords
     * @param bool $paginate
     * @return array
     */
    public static function searchForHelp($keywords, $paginate = true)
    {
        if (empty($keywords)) {
            return [];
        }
        $keywords = Str::lower($keywords);
        $helpContents = HelpContent::find()
            ->andWhere(['like', 'help_content.name', $keywords])
            ->orWhere(['like', 'help_content.slug', $keywords])
            ->orWhere(['like', 'help_content.content', $keywords])
            ->orWhere(['tags' => [$keywords]])
            ->joinWith('module');

        if ($paginate) {
            // do pagination here
            $totalCount = $helpContents->count();
            //
            $pager = new Pagination(compact('totalCount'));
            $results = $helpContents->offset($pager->offset)
                ->limit($pager->limit)
                ->all();

            return compact('results', 'pager');
        }
        return $helpContents->all();

    }

    /**
     * Load all help available in the system, per module
     * @return array
     * @throws BadRequestHttpException
     */
    public static function loadAllHelp()
    {
        $helpModules = HelpModules::find()->all();
        $modules = [];
        foreach ($helpModules as $helpModule) {
            /** @var $helpModule HelpModules */
            $results = self::internalFetchHelp($helpModule);
            $modules[] = [
                'module' => $helpModule,
                'data' => $results
            ];
        }
        return $modules;
    }

    /**
     * Load help based on module context. For example, if am on the Campaigns page
     * help for the 'campaigns' module will only be loaded
     *
     * @param $module
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public static function loadHelp($module)
    {
        $helpModule = static::loadSingleHelpModule($module);
        /** @var $helpModule HelpModules $resource */

        $data = self::internalFetchHelp($helpModule);

        return [
            'module' => $helpModule,
            'data' => $data
        ];

    }

    /**
     * Get the url to display on the page
     *
     * @param ViewContextInterface $context
     * @param array $params
     * @return string
     */
    public static function getContentUrl($context, $params = [])
    {
        $path = '/help/default/content';
        if (!$context instanceof ViewContextInterface) {
            if (empty($params)) {
                throw new \yii\base\InvalidArgumentException("Params has to be specified if context is unavailable");
            }
            $module = ArrayHelper::remove($params, 'module');
            $action = static::getAction(ArrayHelper::remove($params, 'action'));
        } else {
            $module = empty($context->helpModuleName) ? self::DEFAULT_MODULE : $context->helpModuleName;
            if(in_array($module, self::$skippedModules)){
                $module = Help::DEFAULT_MODULE;
            }
            $action = static::getAction(!empty($context->actionType)
                ? $context->actionType : $context->action->id)[0];
        }

        if (self::ENFORCE_ACL) {
            return Url::to([$path, 'module' => $module, 'action' => $action]);
        } else {
            return Url::to([$path, 'module' => $module]);
        }
    }

    /**
     * Get help based on user access if necessary
     *
     * @param $helpModule
     * @return array
     * @throws BadRequestHttpException
     */
    private static function internalFetchHelp(HelpModules $helpModule)
    {
        $resource = $helpModule->resource_name;
        $contents = $helpModule->helpContents;

        $create = [];
        $delete = [];
        $view = [];
        $update = [];
        // for permissions non-compliance
        $passThroughContents = [];
        foreach ($contents as $content) {
            $perms = $content->permissions;
            if (self::ENFORCE_ACL) {
                // loop over each assigned permission and check if the user has access specified
                // if they have the permissions, we simply add the content to the respective array
                foreach ((array)$perms as $perm) {
                    switch ((int)$perm) {
                        case self::CREATE:
                            if (Yii::$app->user->canCreate($resource)) {
                                $create[$content->id] = $content;
                            }
                            break;
                        case self::UPDATE:
                            if (Yii::$app->user->canUpdate($resource)) {
                                $update[$content->id] = $content;
                            }
                            break;
                        case self::VIEW:
                            if (Yii::$app->user->canView($resource)) {
                                $view[$content->id] = $content;
                            }
                            break;
                        case self::DELETE:
                            if (Yii::$app->user->canDelete($resource)) {
                                $delete[$content->id] = $content;
                            }
                            break;
                        default:
                            throw new BadRequestHttpException("Invalid Operation. This is an error");
                    }
                }
            } else {
                // push content, not really minding user permissions
                $passThroughContents[$content->id] = $content;
            }
        }
        return self::ENFORCE_ACL ? compact('create', 'delete', 'view', 'update') : $passThroughContents;
    }

    /**
     * Handle integer based action types. Like the ones specified for custom controller actions
     * @param $action
     * @return array
     */
    private static function extractForIntegerActionType($action)
    {
        switch ($action) {
            case Help::VIEW:
                return [Help::VIEW, 'view'];
            case Help::UPDATE:
                return [Help::UPDATE, 'view'];
            case Help::DELETE:
                return [Help::DELETE, 'view'];
            case Help::CREATE:
                return [Help::CREATE, 'create'];
            default:
                // default to viewing only
                return [Help::VIEW, 'view'];
        }
    }

    /**
     * fetch action. Only invoked if acl is required on help
     * @param $action
     * @return array
     */
    private static function fetchActionInternal($action)
    {
        // handle int defined actions. like the specialized ones, e.g import.
        // this can be assigned an action type at the controller
        if (is_int($action)) {
            return self::extractForIntegerActionType($action);
        }
        switch ($action) {
            case 'index':
                return [Help::VIEW, 'view'];
            case 'view':
                return [Help::VIEW, 'view'];
            case 'update':
                return [Help::UPDATE, 'view'];
            case 'delete':
                return [Help::DELETE, 'view'];
            case 'create':
                return [Help::CREATE, 'create'];
            default:
                // default to viewing only
                return [Help::VIEW, 'view'];
        }
    }
}