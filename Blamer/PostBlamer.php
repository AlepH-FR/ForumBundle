<?php

namespace Bundle\ForumBundle\Blamer;

class PostBlamer extends AbstractSecurityBlamer implements BlamerInterface
{
    public function blame($post)
    {
        // Here, use $this->security to give the post a user or username
    }
}
