<?php

namespace Apps\Core_Blogs\Block;

use Phpfox;
use Phpfox_Component;
use Phpfox_Plugin;

defined('PHPFOX') or exit('NO DICE!');

/**
 * Class BlogNew
 * @package Apps\Core_Blogs\Block
 */
class BlogNew extends Phpfox_Component
{
    /**
     * Controller
     */
    public function process()
    {
        $this->template()->assign(array(
                'aBlogs' => Phpfox::getService('blog')->getNew()
            )
        );
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('blog.component_block_new_clean')) ? eval($sPlugin) : false);
    }
}
