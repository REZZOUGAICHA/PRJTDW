<?php

class diapoView {
    public function display($images){
    foreach ($images as $image) {
        echo '<img src="' . htmlspecialchars($image) . '" alt="Image">';
    }
    }
}
?>