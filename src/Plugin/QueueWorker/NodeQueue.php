<?php

namespace Drupal\file_attente\Plugin\QueueWorker;
use Drupal\Core\Queue\QueueWorkerBase;

/**
 * process tasks for node publishing.
 *
 * @QueueWorker(
 *   id = "node_queue",
 *   title = @Translation("Cron Node Publisher"),
 *   cron = {"time" = 60}
 * )
 */

class NodeQueue extends QueueWorkerBase {
    public function processItem($data){
        $node_storage=\Drupal::entityTypeManager()->getStorage('node');
        $node=$node_storage->load($data['nid']);
        if ($node){
            $node->setPublished($data['status']);
        }

        return $node->save();
    }

}