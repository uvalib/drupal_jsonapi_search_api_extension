<?php
/**
* @file
* A description of what your module does.
*/

namespace Drupal\json_search_api_uvalib\EventSubscriber;

use Drupal\jsonapi_search_api\Event\AddSearchMetaEvent;
use Drupal\jsonapi_search_api\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Adds Meta properties to the Json:Api Search Api Response.
 *
 * Stolen from https://www.drupal.org/project/jsonapi_search_api/issues/3166851#comment-13836418
 *
 * @package Drupal\json_api_search_uvalib\EventSubscriber
 */
class AddMetaExtras implements EventSubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
      return [ Events::ADD_SEARCH_META => 'includeExtraData' ];
    }

    /**
     * Includes Search api index processors extra data.
     *
     * @param \Drupal\jsonapi_search_api\Event\AddSearchMetaEvent $event
     *   Jsonapi Search api event.
     *
     * @throws \Drupal\search_api\SearchApiException
     */
    public function includeExtraData(AddSearchMetaEvent $event)
    {
        $results = $event->getResults();
        $extra_data = [];

        foreach ($results->getResultItems() as $resultItem) {
            $entity = $resultItem->getOriginalObject()->getValue();
            $entityId = $entity->id();
            $extra_data[$entityId] = $resultItem->getAllExtraData();
            $extra_data[$entityId]['excerpt'] = $resultItem->getExcerpt();
        }

        // Include here whatever you need to.
        $event->setMeta('extra_data', $extra_data);
    }

}