<?php

namespace Bundle\ForumBundle\Remover;

use Bundle\ForumBundle\Model\Post;
use Bundle\ForumBundle\Updater\TopicUpdater;
use Bundle\ForumBundle\Updater\CategoryUpdater;
use LogicException;

class PostRemover
{
    protected $objectManager;
    protected $topicUpdater;
    protected $categoryUpdater;

    public function __construct($objectManager, TopicUpdater $topicUpdater, CategoryUpdater $categoryUpdater)
    {
        $this->objectManager   = $objectManager;
        $this->topicUpdater    = $topicUpdater;
        $this->categoryUpdater = $categoryUpdater;
    }

    public function remove(Post $post)
    {
        $topic = $post->getTopic();

        if(1 === $post->getNumber()) {
            throw new LogicException('You shall not remove the first topic post. Remove the topic instead');
        }

		// dumping foreign keys for SQL based databases
		$topic->removeLastPost();
		$topic->getCategory()->removeLastPost();
		
        $this->objectManager->remove($post);

        // Must flush because the topic updater will fetch posts from DB
        $this->objectManager->flush();

        $this->topicUpdater->update($topic);

        // Must flush because the category updater will fetch topics from DB
        $this->objectManager->flush();

        $this->categoryUpdater->update($topic->getCategory());
    }
}
