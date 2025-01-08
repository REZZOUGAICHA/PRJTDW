
function initializeTableFilters(tableContainerSelector, filterColumns) {
    // filter container
    const filterContainer = $('<div>').addClass('mb-4 p-4 bg-white rounded shadow');
    const filterWrapper = $('<div>').addClass('flex gap-4');
    
    //  filter dropdown for each  column
    filterColumns.forEach(column => {
        //  unique values from the specified column
        const uniqueValues = new Set();
        $(`${tableContainerSelector} table tbody tr td:nth-child(${column.columnIndex})`).each(function() {
            const value = $(this).text().trim();
            if (value) uniqueValues.add(value);
        });
        
        //  filter group
        const filterGroup = $('<div>').addClass('flex-1');
        
        // label
        const label = $('<label>')
            .addClass('block text-sm font-medium text-gray-700 mb-1')
            .text(column.label);
        
        //  select
        const select = $('<select>')
            .addClass('w-full p-2 border rounded')
            .attr('data-column-index', column.columnIndex);
            
        // default option (tt les options)
        select.append($('<option>').val('').text(`Tous les ${column.label.toLowerCase()}`));
        
        // Add sorted options
        [...uniqueValues].sort().forEach(value => {
            select.append($('<option>').val(value).text(value));
        });
        
        filterGroup.append(label, select);
        filterWrapper.append(filterGroup);
    });
    
    //  reset button
    const resetButton = $('<button>')
        .addClass('px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600')
        .text('Réinitialiser')
        .click(function() {
            filterContainer.find('select').val('');
            $(`${tableContainerSelector} table tbody tr`).show();
        });
    
    const buttonGroup = $('<div>').addClass('flex items-end');
    buttonGroup.append(resetButton);
    filterWrapper.append(buttonGroup);
    
    filterContainer.append(filterWrapper);
    
    // Insert filters before table
    $(tableContainerSelector).before(filterContainer);
    
    // Filter function
    function applyFilters() {
        const activeFilters = {};
        filterContainer.find('select').each(function() {
            const columnIndex = $(this).data('column-index');
            const value = $(this).val().toLowerCase();
            if (value) {
                activeFilters[columnIndex] = value;
            }
        });
        
        // Show/hide rows based on filters
        $(`${tableContainerSelector} table tbody tr`).each(function() {
            const row = $(this);
            let showRow = true;
            
            Object.entries(activeFilters).forEach(([columnIndex, filterValue]) => {
                const cellValue = row.find(`td:nth-child(${columnIndex})`).text().toLowerCase();
                if (!cellValue.includes(filterValue)) {
                    showRow = false;
                }
            });
            
            row.toggle(showRow);
        });
    }
    
    //change event to all filter dropdowns
    filterContainer.find('select').on('change', applyFilters);
}