<?php
function calculateFunctionPoints($ei, $eo, $eq, $ilf, $eif, $complexityFactors) {
    // Step 1: Calculate Unadjusted Function Points (UFP)
    $UFP = ($ei * 3) + ($eo * 4) + ($eq * 3) + ($ilf * 7) + ($eif * 5);
    
    // Step 2: Calculate Value Adjustment Factor (VAF)
    $sumFactors = array_sum($complexityFactors); // Sum of all 14 complexity factors
    $VAF = 0.65 + (0.01 * $sumFactors);

    // Step 3: Compute Final Function Points
    $FP = $UFP * $VAF;
    
    return [
        "UFP" => $UFP,
        "VAF" => $VAF,
        "FP" => $FP
    ];
}

// Example Inputs for Order Placement System
$ei = 4;   // External Inputs (e.g., placing an order, registering)
$eo = 3;   // External Outputs (e.g., order confirmation, receipt)
$eq = 2;   // External Inquiries (e.g., viewing order status)
$ilf = 3;  // Internal Logical Files (e.g., customers, orders)
$eif = 2;  // External Interface Files (e.g., Payment gateway)

$complexityFactors = [
    3, 4, 2, 5, 3, 4, 3, 2, 3, 5, 4, 3, 2, 3 // Example ratings (0-5) for 14 complexity factors
];

// Calculate Function Points
$result = calculateFunctionPoints($ei, $eo, $eq, $ilf, $eif, $complexityFactors);

// Display Results
echo "Unadjusted Function Points (UFP): " . $result["UFP"] . "\n";
echo "Value Adjustment Factor (VAF): " . $result["VAF"] . "\n";
echo "Final Function Points (FP): " . $result["FP"] . "\n";
?>
