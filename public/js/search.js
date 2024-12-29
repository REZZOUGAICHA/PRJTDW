// $(document).ready(function() {
    
//     $('#searchInput').on('input', function() {
//         var searchQuery = $(this).val().toLowerCase(); 
//         $('.element').each(function() {
//             var title = $(this).find('.element-name').text().toLowerCase(); 
//             var description = $(this).find('.text-gray-600').text().toLowerCase(); 
            
            
//             if (title.includes(searchQuery) || description.includes(searchQuery)) {
//                 $(this).show(); 
//             } else {
//                 $(this).hide(); 
//             }

//         });
//         $('.partner-grid').masonry('layout');
//     });
// }); layout not working here 


$(document).ready(function() {
    $('#searchInput').on('input', function() {
        var searchQuery = $(this).val().toLowerCase();
        
        // card exists in a section? ( to show only sections with cards ) 
        $('.mb-12').each(function() {
            var section = $(this);
            var hasVisibleCards = false;
            
            // Check cards in sections
            section.find('.element').each(function() {
                var card = $(this);
                var cardContainer = card.closest('.w-full');
                var title = card.find('.element-name').text().toLowerCase();
                var description = card.find('.text-gray-600').text().toLowerCase();
                
                // search match check 
                if (title.includes(searchQuery) || description.includes(searchQuery)) {
                    cardContainer.show();
                    hasVisibleCards = true;
                } else {
                    cardContainer.hide();
                }
            });
            
            
            if (hasVisibleCards) {
                section.show();
            } else {
                section.hide();
            }
        });
        
        // no result
        var allSectionsHidden = $('.mb-12:visible').length === 0;
        if (allSectionsHidden) {
            if ($('#noResults').length === 0) {
                $('<div id="noResults" class="text-center py-8 text-gray-600">' +
                'Aucun résultat trouvé</div>').insertAfter('#searchInput');
            }
        } else {
            $('#noResults').remove();
        }
    });
});