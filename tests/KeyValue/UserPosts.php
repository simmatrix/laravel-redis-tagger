<?php
namespace KeyValue;

use Models\User;
use Chalcedonyt\RedisTagger\Tagger;
/**
 *
 */
class UserPosts extends BaseUserTagger
{

    public function __construct(){
        parent::__construct();
        $this -> tags[] = 'posts';
    }
}


?>
