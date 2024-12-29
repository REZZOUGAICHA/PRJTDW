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
            <?php foreach ($offers as $offerType => $offerGroup): ?>
                <div class="mb-12">
                    <h1 class="text-4xl font-extrabold mb-6 text-blue-700 capitalize px-4">
                        <?php echo $offerType === 'regular' ? 'Offres' : 'Offre Limité'; ?>
                    </h1>
                    <?php 
                    $cardView->displaySection(
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
}
?>
