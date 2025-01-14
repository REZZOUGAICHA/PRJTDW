<div class="card-container mt-6 flex justify-center">
    <div class="relative bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden w-96 transform hover:scale-[1.05] transition-transform duration-300">
        <!-- Accent Bar -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

        <!-- Header -->
        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">
                    <?php echo htmlspecialchars($cardDetails['asso_name']); ?>
                </h2>
                <img src="<?php echo htmlspecialchars($cardDetails['logo_link']); ?>" 
                     alt="Logo" 
                     class="h-10 object-contain">
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 space-y-4">
            <!-- Member ID -->
            <div class="bg-blue-50 rounded-md p-2">
                <p class="text-xs text-blue-600">ID Membre</p>
                <p class="text-sm font-bold text-blue-800">#<?php echo htmlspecialchars($cardDetails['member_id']); ?></p>
            </div>

            <!-- Name -->
            <div class="bg-gray-50 rounded-md p-2">
                <p class="text-xs text-gray-600">Nom</p>
                <p class="text-sm font-bold text-gray-800">
                    <?php echo htmlspecialchars($cardDetails['first_name'] . ' ' . $cardDetails['last_name']); ?>
                </p>
            </div>

            <!-- Card Number and Expiration -->
            <div class="flex justify-between items-center">
                <div class="bg-gray-50 rounded-md p-2">
                    <p class="text-xs text-gray-600">Numéro de carte</p>
                    <p class="text-sm font-mono text-gray-800">
                        <?php echo chunk_split(htmlspecialchars($cardDetails['card_number']), 4, ' '); ?>
                    </p>
                </div>
                <div class="bg-gray-50 rounded-md p-2">
                    <p class="text-xs text-gray-600">Expiration</p>
                    <p class="text-sm text-gray-800">
                        <?php echo date('d/m/Y', strtotime($cardDetails['expiration_date'])); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- QR Code -->
       
<div class="p-4 bg-gray-50 text-center">
    <?php 

        
        if (isset($cardDetails['QR_LINK']) && $cardDetails['QR_LINK']): 
            $finalPath = '/TDW/public' . $cardDetails['QR_LINK'];
            
    ?>
        <img src="<?php echo $finalPath; ?>" 
             alt="QR Code" 
             class="w-16 h-16 mx-auto rounded-md shadow-md"
             onerror="console.log('Failed to load image:', this.src)">
   
        <!-- Remove this fallback as it's not needed -->
    <?php endif; ?>
</div>