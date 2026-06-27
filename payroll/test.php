<?php
$arr = array('one', 'two', 'three', 'four', 'escape', 'five');
while (list(, $val) = each($arr)) {
    if ($val == 'escape' || $val == 'two') {
        continue;    /* You could also write 'break 1;' here. */
    }
    echo "$val<br />\n";
}
?>