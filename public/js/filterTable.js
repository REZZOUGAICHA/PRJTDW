function initializeTableFilters(tableContainerSelector, filterColumns) {
    // Création du conteneur de filtres
    const filterContainer = $('<div>').addClass('mb-4 p-4 bg-white rounded shadow');
    const filterWrapper = $('<div>').addClass('flex gap-4 flex-wrap');
    
    // Créer un filtre pour chaque colonne spécifiée
    filterColumns.forEach(column => {
        // Récupérer les valeurs uniques de la colonne
        const uniqueValues = new Set();
        $(`${tableContainerSelector} table tbody tr td:nth-child(${column.columnIndex})`).each(function() {
            const value = $(this).text().trim();
            if (value) uniqueValues.add(value);
        });
        
        // Groupe de filtres pour une colonne
        const filterGroup = $('<div>').addClass('flex-1');
        
        // Étiquette
        const label = $('<label>')
            .addClass('block text-sm font-medium text-gray-700 mb-1')
            .text(column.label);
        
        // Sélecteur
        const select = $('<select>')
            .addClass('w-full p-2 border rounded')
            .attr('data-column-index', column.columnIndex);
        
        // Ajouter une option par défaut
        select.append($('<option>').val('').text(`Tous les ${column.label.toLowerCase()}`));
        
        // Ajouter les options triées
        [...uniqueValues].sort().forEach(value => {
            select.append($('<option>').val(value).text(value));
        });
        
        filterGroup.append(label, select);
        filterWrapper.append(filterGroup);
    });
    
    // Ajouter un bouton de réinitialisation
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
    
    // Insérer les filtres avant la table
    $(tableContainerSelector).before(filterContainer);
    
    // Fonction pour appliquer les filtres
    function applyFilters() {
        const activeFilters = {};
        filterContainer.find('select').each(function() {
            const columnIndex = $(this).data('column-index');
            const value = $(this).val().toLowerCase();
            if (value) {
                activeFilters[columnIndex] = value;
            }
        });
        
        // Afficher/masquer les lignes en fonction des filtres actifs
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
    
    // Ajouter un événement `change` à tous les sélecteurs
    filterContainer.find('select').on('change', applyFilters);
}
