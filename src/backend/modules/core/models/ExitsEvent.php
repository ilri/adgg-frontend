<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2019-10-28
 * Time: 2:24 PM
 */

namespace backend\modules\core\models;


/**
 * Class ExitsEvent
 * @package backend\modules\core\models
 *
 *
 */
class ExitsEvent extends AnimalEvent implements AnimalEventInterface
{
    /**
     * @inheritDoc
     */
    public function getEventType(): int
    {
        return self::EVENT_TYPE_EXITS;
    }

    /**
     * @inheritDoc
     */
    public function reportBuilderAdditionalUnwantedFields(): array
    {
        return ['lactation_id', 'lactation_number'];
    }
}