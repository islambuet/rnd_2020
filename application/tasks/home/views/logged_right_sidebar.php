<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
$CI = & get_instance();

?>
<nav id="system_right_sidebar" class="sidebar d-print-none">
    <ul class="list-unstyled" style="margin-bottom: 70px">
        <li>
            <a href="#pageSubmenu11" data-toggle="collapse" aria-expanded="false">Pages<span class="fe-menu-arrow"></span></a>

            <ul class="collapse list-unstyled" id="pageSubmenu11">
                <li>
                    <a href="#">Page 1</a>
                </li>
                <li>
                    <a href="#">Page 2</a>
                </li>
                <li>
                    <a href="#">Page 3</a>
                </li>
                <li>
                    <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false">page 4<span class="fe-menu-arrow"></span></a>

                    <ul class="collapse list-unstyled" id="pageSubmenu2">
                        <li>
                            <a href="#">Home 1</a>
                        </li>
                        <li>
                            <a href="#">Home 2</a>
                        </li>
                        <li>
                            <a href="#">Home 3</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    </ul>
</nav>