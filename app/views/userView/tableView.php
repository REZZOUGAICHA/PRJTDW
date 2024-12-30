<?php

class TableView {
    public function displayTable($data, $columns, $tableClasses = "min-w-full bg-white border-collapse shadow-sm") {
        if (empty($data) || empty($columns)) return;
        ?>
        <div class="w-full">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full">
                    <div class="overflow-hidden">
                        <table class="<?php echo $tableClasses; ?>">
                            <thead class="bg-gray-50">
                                <tr>
                                    <?php foreach ($columns as $column): ?>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo htmlspecialchars($column['label']); ?>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($data as $row): ?>
                                    <tr class="hover:bg-gray-50">
                                        <?php foreach ($columns as $column): ?>
                                            <td class="px-4 py-4 text-sm text-gray-500">
                                                <div class="break-words">
                                                    <?php 
                                                    if (isset($column['formatter'])) {
                                                        echo $column['formatter']($row[$column['field']]);
                                                    } else if ($column['field'] === 'link' && !empty($row[$column['field']])) {
                                                        echo '<a href="' . htmlspecialchars($row[$column['field']]) . '" 
                                                                class="text-blue-600 hover:text-blue-800 hover:underline" 
                                                                target="_blank">
                                                                Voir plus
                                                            </a>';
                                                    } else {
                                                        echo htmlspecialchars($row[$column['field']]);
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>