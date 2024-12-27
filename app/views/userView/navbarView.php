<?php

class MenuView {
    
    public function display($menuItems) {
        echo '<link href="../../public/css/output.css" rel="stylesheet">';
        echo '<nav class="bg-blue-600 p-4 shadow-md">';
        echo '<ul class="flex space-x-6 text-white">'; 

        foreach ($menuItems as $item) {
            // Item has sub-items 
            if (!empty($item['sub_items'])) {
                echo '<li class="relative group">';  
                echo '<a href="' . htmlspecialchars($item['link']) . '" class="hover:text-yellow-400 transition duration-300">' . htmlspecialchars($item['name']) . '</a>';
                
                // Dropdown menu
                echo '<ul class="absolute left-0 hidden mt-2 space-y-2 bg-white text-gray-800 shadow-lg group-hover:block">';
                foreach ($item['sub_items'] as $subItem) {
                    echo '<li><a href="' . htmlspecialchars($subItem['link']) . '" class="block px-4 py-2 hover:bg-gray-200 transition duration-200">' . htmlspecialchars($subItem['name']) . '</a></li>';
                }
                echo '</ul>';
                echo '</li>';
            } else {
                // Regular menu item
                echo '<li><a href="' . htmlspecialchars($item['link']) . '" class="hover:text-yellow-400 transition duration-300">' . htmlspecialchars($item['name']) . '</a></li>';
            }
        }

        echo '</ul>';
        echo '</nav>';
    }
}


?>
