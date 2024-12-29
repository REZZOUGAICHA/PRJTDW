$(document).ready(function() {
                function filterPartners() {
                    const searchQuery = $('#searchInput').val().toLowerCase();
                    const selectedCity = $('#cityFilter').val().toLowerCase();
                    
                    // Track if we have any visible cards
                    let hasAnyVisibleCards = false;
                    
                    // Filter each section
                    $('.mb-12').each(function() {
                        const section = $(this);
                        let hasVisibleCards = false;
                        
                        // Filter cards within the section
                        section.find('.element').each(function() {
                            const card = $(this);
                            const container = card.closest('.w-full');
                            const title = card.find('.element-name').text().toLowerCase();
                            const city = card.find('.text-gray-600').text().toLowerCase();
                            
                            // Check if card matches both filters
                            const matchesSearch = title.includes(searchQuery) || 
                                                city.includes(searchQuery);
                            const matchesCity = !selectedCity || 
                                              city.includes(selectedCity);
                            
                            if (matchesSearch && matchesCity) {
                                container.show();
                                hasVisibleCards = true;
                                hasAnyVisibleCards = true;
                            } else {
                                container.hide();
                            }
                        });
                        
                        // Show/hide section based on visible cards
                        section.toggle(hasVisibleCards);
                    });
                    
                    // Handle no results message
                    if (!hasAnyVisibleCards) {
                        if ($('#noResults').length === 0) {
                            $('<div id="noResults" class="text-center py-8 text-gray-600">' +
                              'Aucun résultat trouvé</div>').insertAfter('#cityFilter');
                        }
                    } else {
                        $('#noResults').remove();
                    }
                }
                
                // Add event listeners
                $('#searchInput').on('input', filterPartners);
                $('#cityFilter').on('change', filterPartners);

                function filteroffers() {
                    const searchQuery = $('#searchInput').val().toLowerCase();
                    const selectedCity = $('#cityFilter').val().toLowerCase();
                    
                    // Track if we have any visible cards
                    let hasAnyVisibleCards = false;
                    
                    // Filter each section
                    $('.mb-12').each(function() {
                        const section = $(this);
                        let hasVisibleCards = false;
                        
                        // Filter cards within the section
                        section.find('.element').each(function() {
                            const card = $(this);
                            const container = card.closest('.w-full');
                            const title = card.find('.element-name').text().toLowerCase();
                            const city = card.find('.text-gray-600').text().toLowerCase();
                            
                            // Check if card matches both filters
                            const matchesSearch = title.includes(searchQuery) || 
                                                city.includes(searchQuery);
                            const matchesCity = !selectedCity || 
                                              city.includes(selectedCity);
                            
                            if (matchesSearch && matchesCity) {
                                container.show();
                                hasVisibleCards = true;
                                hasAnyVisibleCards = true;
                            } else {
                                container.hide();
                            }
                        });
                        
                        // Show/hide section based on visible cards
                        section.toggle(hasVisibleCards);
                    });
                    
                    // Handle no results message
                    if (!hasAnyVisibleCards) {
                        if ($('#noResults').length === 0) {
                            $('<div id="noResults" class="text-center py-8 text-gray-600">' +
                              'Aucun résultat trouvé</div>').insertAfter('#cityFilter');
                        }
                    } else {
                        $('#noResults').remove();
                    }
                }

                $('#searchInput').on('input', filterOffers);
                $('#cityFilter').on('change', filterOffers);
            });