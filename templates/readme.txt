If you want to modify the template you can do so in your theme

copy the template file to yourtheme/buddypress/groups/featured/ directory and modify it there.

Template Usage/Loading:-
Widget:-
    view:->list checks for
        - current_theme/(buddypress/)groups/featured/widget/groups-loop-list.php
        - current_theme/(buddypress/)groups/featured/groups-loop-list.php

        - plugins/bp-featured-groups/template/groups-loop-list.php

  view:->slider checks for
        - current_theme/(buddypress/)groups/featured/widget/groups-loop-slider.php
        - current_theme/(buddypress/)groups/featured/groups-loop-slider.php

        - plugins/bp-featured-groups/template/groups-loop-slider.php

Shortcode:-
    view:->list checks for
        - current_theme/(buddypress/)groups/featured/shortcode/groups-loop-list.php
        - current_theme/(buddypress/)groups/featured/groups-loop-list.php

        - plugins/bp-featured-groups/template/groups-loop-list.php

  view:->slider checks for
        - current_theme/(buddypress/)groups/featured/shortcode/groups-loop-slider.php
        - current_theme/(buddypress/)groups/featured/groups-loop-slider.php

        - plugins/bp-featured-groups/template/groups-loop-slider.php

 Have fun!