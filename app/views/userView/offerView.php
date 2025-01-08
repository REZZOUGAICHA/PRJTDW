<?php
require_once  'cardView.php';
require_once __DIR__ . '/../../controllers/offerController.php';

class OfferView {
    
    public function displayoffer() {
        $offerController = new offerController();
        $offers = $offerController->getOffersByType();
        $cardView = new CardView();
        ?>
        <div class="w-full">
            <!-- foreach ($array as $key => $value) -->
            <!-- offers here has the data we got from controller devided by type -->
            <!-- wl offertype is wether regular or limited  -->
            <?php foreach ($offers as $offerType => $offerGroup): ?>
                <div class="mb-12">
                    <h1 class="text-4xl font-extrabold mb-6 text-blue-700 capitalize px-4">
                        <?php echo $offerType === 'regular' ? 'Offres' : 'Offre Limité'; ?>
                    </h1>
                    <?php 
                    $cardView->displaySection(
                        //for offer in card 
                        $offerGroup,
                        '',
                        [
                            'title' => 'name',
                            'description' => 'description',
                            'image' => null, // No image for offers
                            'link' => 'partner_link',
                            'extraFields' => [
                                'Card Type' => 'card_type_name',
                                'Start Date' => 'start_date',
                                'End Date' => 'end_date',
                                'Partner' => 'partner_name',
                            ]
                        ]
                    );
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
    public function displaydiscount(){
    $discountController = new DiscountController();
    $data = $discountController->getDiscountsData();

    // Separate the discounts by type (regular and special)
    $regularDiscounts = array_filter($data, function($discount) {
        return $discount['discount_type'] === 'regular';
    });

    $specialDiscounts = array_filter($data, function($discount) {
        return $discount['discount_type'] === 'special';
    });

    // for discount in table 
    $columns = [
        ['field' => 'partner_name', 'label' => 'Partenaire'],
        ['field' => 'category_name', 'label' => 'Categorie'],
        ['field' => 'card_name', 'label' => 'type carte'],
        ['field' => 'city', 'label' => 'ville'],
        ['field' => 'percentage', 'label' => 'pourcentage'],
        ['field' => 'link', 'label' => 'voir plus'],
    ];

    $tableView = new TableView();
//add action later for voir plus 

    // regular discounts
    echo '<h2 class="text-2xl font-bold mb-4 text-blue-700">Regular Discounts</h2>';
    $tableView->displayTable($regularDiscounts, $columns);

    // special discounts 
    echo '<h2 class="text-2xl font-bold mb-4 text-blue-700">Special Discounts</h2>';
    $tableView->displayTable($specialDiscounts, $columns);
}

}
?>
