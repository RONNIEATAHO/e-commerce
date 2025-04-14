<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Function Point Calculation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f2f2f2;
        }
        .result-box {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        p {
            font-size: 18px;
        }
        .highlight {
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="result-box">
        <h1>Function Point Analysis</h1>
        <?php
            function calculateFunctionPoints($ei, $eo, $eq, $ilf, $eif, $complexityFactors) {
                $UFP = ($ei * 3) + ($eo * 4) + ($eq * 3) + ($ilf * 7) + ($eif * 5);
                $sumFactors = array_sum($complexityFactors);
                $VAF = 0.65 + (0.01 * $sumFactors);
                $FP = $UFP * $VAF;
                return ["UFP" => $UFP, "VAF" => $VAF, "FP" => $FP];
            }

            $ei = 4; $eo = 3; $eq = 2; $ilf = 3; $eif = 2;
            $complexityFactors = [3, 4, 2, 5, 3, 4, 3, 2, 3, 5, 4, 3, 2, 3];

            $result = calculateFunctionPoints($ei, $eo, $eq, $ilf, $eif, $complexityFactors);
            
            echo "<p>Unadjusted Function Points (UFP): <span class='highlight'>{$result['UFP']}</span></p>";
            echo "<p>Value Adjustment Factor (VAF): <span class='highlight'>" . number_format($result['VAF'], 2) . "</span></p>";
            echo "<p>Final Function Points (FP): <span class='highlight'>" . number_format($result['FP'], 2) . "</span></p>";
        ?>
    </div>
</body>
</html>
