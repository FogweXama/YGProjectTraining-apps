<?php 
//to sanitize data important in putting in data.....needs to be improved upon from unline. this removes spaces by escapes
?>
<?php
function escape($string)
{
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}
?>