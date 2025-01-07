<?php

class TableView {
    public function displayTable($data, $columns, $actions = [], $tableClasses = "min-w-full bg-white border-collapse shadow-sm") {
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
                                    <?php if (!empty($actions)): ?>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    <?php endif; ?>
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
                                                    } else {
                                                        echo htmlspecialchars($row[$column['field']]);
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        <?php endforeach; ?>
                                        <?php if (!empty($actions)): ?>
                                            <td class="px-4 py-4 text-sm text-gray-500">
                                                <?php foreach ($actions as $action): ?>
                                                    <?php echo $action($row); ?>
                                                <?php endforeach; ?>
                                            </td>
                                        <?php endif; ?>
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