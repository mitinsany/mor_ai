<?php

declare(strict_types=1);

/**
 * Exact, dependency-free arithmetic for the MOR.AI payments take-home.
 * Money is integer cents and each rate/value fraction is array{0:int,1:int}
 * with a positive denominator. Inputs reject negative counts/money and zero
 * denominators; checked arithmetic rejects values that cannot remain integers.
 */

function gcd(int $a, int $b): int
{
    if ($a === PHP_INT_MIN || $b === PHP_INT_MIN) {
        throw new OutOfRangeException('gcd cannot normalize PHP_INT_MIN');
    }

    $a = abs($a);
    $b = abs($b);
    while ($b !== 0) {
        [$a, $b] = [$b, $a % $b];
    }

    return $a;
}

/** @return array{0:int,1:int} */
function fraction(int $numerator, int $denominator): array
{
    if ($denominator === 0) {
        throw new InvalidArgumentException('Zero denominator');
    }
    if ($numerator === PHP_INT_MIN || $denominator === PHP_INT_MIN) {
        throw new OutOfRangeException('Fraction is outside the supported integer range');
    }
    if ($numerator === 0) {
        return [0, 1];
    }

    $divisor = gcd($numerator, $denominator);
    $numerator = intdiv($numerator, $divisor);
    $denominator = intdiv($denominator, $divisor);

    if ($denominator < 0) {
        $numerator = -$numerator;
        $denominator = -$denominator;
    }

    return [$numerator, $denominator];
}

function checkedMultiply(int $a, int $b): int
{
    if ($a === 0 || $b === 0) {
        return 0;
    }
    if ($a === PHP_INT_MIN || $b === PHP_INT_MIN) {
        throw new OutOfRangeException('Integer multiplication would exceed the supported range');
    }
    if (abs($a) > intdiv(PHP_INT_MAX, abs($b))) {
        throw new OutOfRangeException('Integer multiplication overflow');
    }

    return $a * $b;
}

function checkedAdd(int $a, int $b): int
{
    if (($b > 0 && $a > PHP_INT_MAX - $b) || ($b < 0 && $a < PHP_INT_MIN - $b)) {
        throw new OutOfRangeException('Integer addition overflow');
    }

    return $a + $b;
}

/**
 * Multiplies an integer by a ratio without converting through float.
 *
 * @param array{0:int,1:int} $ratio
 * @return array{0:int,1:int}
 */
function mulFraction(int $value, array $ratio): array
{
    [$numerator, $denominator] = fraction($ratio[0] ?? 0, $ratio[1] ?? 0);
    $cancel = gcd($value, $denominator);

    return fraction(
        checkedMultiply(intdiv($value, $cancel), $numerator),
        intdiv($denominator, $cancel),
    );
}

/**
 * @param array{0:int,1:int} $left
 * @param array{0:int,1:int} $right
 * @return array{0:int,1:int}
 */
function multiplyFractions(array $left, array $right): array
{
    [$leftNumerator, $leftDenominator] = fraction($left[0] ?? 0, $left[1] ?? 0);
    [$rightNumerator, $rightDenominator] = fraction($right[0] ?? 0, $right[1] ?? 0);
    $firstCancel = gcd($leftNumerator, $rightDenominator);
    $secondCancel = gcd($rightNumerator, $leftDenominator);

    return fraction(
        checkedMultiply(
            intdiv($leftNumerator, $firstCancel),
            intdiv($rightNumerator, $secondCancel),
        ),
        checkedMultiply(
            intdiv($leftDenominator, $secondCancel),
            intdiv($rightDenominator, $firstCancel),
        ),
    );
}

/**
 * @param array{0:int,1:int} $left
 * @param array{0:int,1:int} $right
 * @return array{0:int,1:int}
 */
function addFractions(array $left, array $right): array
{
    [$leftNumerator, $leftDenominator] = fraction($left[0] ?? 0, $left[1] ?? 0);
    [$rightNumerator, $rightDenominator] = fraction($right[0] ?? 0, $right[1] ?? 0);
    $commonDivisor = gcd($leftDenominator, $rightDenominator);
    $leftMultiplier = intdiv($rightDenominator, $commonDivisor);
    $rightMultiplier = intdiv($leftDenominator, $commonDivisor);

    return fraction(
        checkedAdd(
            checkedMultiply($leftNumerator, $leftMultiplier),
            checkedMultiply($rightNumerator, $rightMultiplier),
        ),
        checkedMultiply($leftDenominator, $leftMultiplier),
    );
}

/** @param array{0:int,1:int} $value @return array{0:int,1:int} */
function negateFraction(array $value): array
{
    [$numerator, $denominator] = fraction($value[0] ?? 0, $value[1] ?? 0);
    if ($numerator === PHP_INT_MIN) {
        throw new OutOfRangeException('Fraction is outside the supported integer range');
    }

    return [-$numerator, $denominator];
}

function formatMoney(int $minor): string
{
    if ($minor === PHP_INT_MIN) {
        throw new OutOfRangeException('Money is outside the supported integer range');
    }

    $sign = $minor < 0 ? '-' : '';
    $absolute = abs($minor);

    return sprintf('%s$%d.%02d', $sign, intdiv($absolute, 100), $absolute % 100);
}

/** @param array{0:int,1:int} $value */
function formatFractionPercent(array $value, int $decimalPlaces = 3): string
{
    [$numerator, $denominator] = fraction($value[0] ?? 0, $value[1] ?? 0);
    $percentage = checkedMultiply($numerator, 100);
    $sign = $percentage < 0 ? '-' : '';
    if ($percentage === PHP_INT_MIN) {
        throw new OutOfRangeException('Percentage is outside the supported integer range');
    }
    $absolute = abs($percentage);
    $whole = intdiv($absolute, $denominator);
    $remainder = $absolute % $denominator;
    $digits = '';

    for ($place = 0; $place < $decimalPlaces; $place++) {
        $remainder = checkedMultiply($remainder, 10);
        $digits .= (string) intdiv($remainder, $denominator);
        $remainder %= $denominator;
    }

    return $sign . $whole . '.' . $digits . '%';
}

/**
 * Returns the smallest non-negative integer reduction r for which
 * (currentNumerator - r) / fixedDenominator is strictly below threshold.
 *
 * @param array{0:int,1:int} $threshold
 */
function minimumNumeratorReductionBelowThreshold(
    int $currentNumerator,
    int $fixedDenominator,
    array $threshold,
): int {
    if ($currentNumerator < 0) {
        throw new InvalidArgumentException('Current numerator must be non-negative');
    }
    if ($fixedDenominator <= 0) {
        throw new InvalidArgumentException('Fixed denominator must be positive');
    }
    [$thresholdNumerator, $thresholdDenominator] = fraction($threshold[0] ?? 0, $threshold[1] ?? 0);
    if ($thresholdNumerator < 0) {
        throw new InvalidArgumentException('Threshold must be non-negative');
    }
    if ($thresholdNumerator === 0) {
        throw new DomainException('No non-negative count is strictly below zero');
    }

    $scaledThreshold = checkedMultiply($thresholdNumerator, $fixedDenominator);
    $ceiling = intdiv(
        checkedAdd($scaledThreshold, $thresholdDenominator - 1),
        $thresholdDenominator,
    );
    $largestStrictlyBelow = $ceiling - 1;

    return max(0, $currentNumerator - $largestStrictlyBelow);
}

/**
 * Calculates a supplied count-based threshold scenario. It intentionally
 * accepts counts rather than deriving them from dollar volume.
 *
 * @param array{0:int,1:int} $threshold
 * @return array{minimumReduction:int,postReductionCount:int,postReductionRate:array{0:int,1:int},isStrictlyBelowThreshold:bool}
 */
function countThresholdScenario(int $currentNumerator, int $fixedDenominator, array $threshold): array
{
    $reduction = minimumNumeratorReductionBelowThreshold(
        $currentNumerator,
        $fixedDenominator,
        $threshold,
    );
    $postReductionCount = $currentNumerator - $reduction;
    $postReductionRate = fraction($postReductionCount, $fixedDenominator);
    [$thresholdNumerator, $thresholdDenominator] = fraction($threshold[0] ?? 0, $threshold[1] ?? 0);

    return [
        'minimumReduction' => $reduction,
        'postReductionCount' => $postReductionCount,
        'postReductionRate' => $postReductionRate,
        'isStrictlyBelowThreshold' => checkedMultiply($postReductionCount, $thresholdDenominator)
            < checkedMultiply($thresholdNumerator, $fixedDenominator),
    ];
}

/** @param array{0:int,1:int} $ratio @return array{0:int,1:int} */
function probability(array $ratio, string $label): array
{
    [$numerator, $denominator] = fraction($ratio[0] ?? 0, $ratio[1] ?? 0);
    if ($numerator < 0 || $numerator > $denominator) {
        throw new InvalidArgumentException($label . ' must be between zero and one');
    }

    return [$numerator, $denominator];
}

function nonNegativeInt(mixed $value, string $label): int
{
    if (!is_int($value) || $value < 0) {
        throw new InvalidArgumentException($label . ' must be a non-negative integer');
    }

    return $value;
}

/**
 * Q1 model input shape:
 * array{transactionCount:int, aovCents:int, disputeRateBps:int,
 * chargebackFeeCents:int, representmentWinRate:array{0:int,1:int},
 * alertFeeCents:int, alertCount:int, truePositiveRate:array{0:int,1:int},
 * monitoringThreshold?:array{0:int,1:int}}.
 *
 * Returned monetary/count values are array{0:int,1:int} fractions. No-alert
 * cost is AOV × (1 - representment win rate) + chargeback fee: the fee is not
 * assumed recovered on a win, and no unsupplied representment operating cost is added.
 *
 * @return array{disputeCount:array{0:int,1:int},noAlertCostPerTrueChargebackCents:array{0:int,1:int},alertRefundCostPerAlertCents:array{0:int,1:int},cashBreakEvenTruePositiveRate:array{0:int,1:int},cashBreakEvenIsFeasible:bool,breakEvenChargebackFeeAtFullTruePositiveCents:array{0:int,1:int},truePositiveAlertCount:array{0:int,1:int},noAlertCostForConfiguredAlertsCents:array{0:int,1:int},alertRefundCostForConfiguredAlertsCents:array{0:int,1:int},cashDifferenceForConfiguredAlertsCents:array{0:int,1:int},monitoringScenario:null|array{threshold:array{0:int,1:int},minimumDeflections:int,postDeflectionDisputeCount:array{0:int,1:int},postDeflectionRate:array{0:int,1:int},conditionalIncrementalAlertCostCents:array{0:int,1:int},isStrictlyBelowThreshold:bool}}
 */
function q1Model(array $input): array
{
    $transactionCount = nonNegativeInt($input['transactionCount'] ?? null, 'Transaction count');
    $aovCents = nonNegativeInt($input['aovCents'] ?? null, 'AOV cents');
    $disputeRateBps = nonNegativeInt($input['disputeRateBps'] ?? null, 'Dispute rate basis points');
    if ($disputeRateBps > 10_000) {
        throw new InvalidArgumentException('Dispute rate basis points must be at most 10000');
    }
    $chargebackFeeCents = nonNegativeInt($input['chargebackFeeCents'] ?? null, 'Chargeback fee cents');
    $alertFeeCents = nonNegativeInt($input['alertFeeCents'] ?? null, 'Alert fee cents');
    $alertCount = nonNegativeInt($input['alertCount'] ?? null, 'Alert count');
    $winRate = probability($input['representmentWinRate'] ?? [], 'Representment win rate');
    $truePositiveRate = probability($input['truePositiveRate'] ?? [], 'True-positive rate');

    $disputeCount = mulFraction($transactionCount, [$disputeRateBps, 10_000]);
    $lossRate = fraction($winRate[1] - $winRate[0], $winRate[1]);
    $expectedPrincipalLoss = mulFraction($aovCents, $lossRate);
    $noAlertCost = addFractions($expectedPrincipalLoss, [$chargebackFeeCents, 1]);
    if ($noAlertCost[0] <= 0) {
        throw new InvalidArgumentException('No-alert expected cost must be positive');
    }
    $alertRefundCost = [checkedAdd($aovCents, $alertFeeCents), 1];
    $cashBreakEven = fraction(
        checkedMultiply($alertRefundCost[0], $noAlertCost[1]),
        checkedMultiply($alertRefundCost[1], $noAlertCost[0]),
    );
    $feeAtFullAccuracy = addFractions($alertRefundCost, negateFraction($expectedPrincipalLoss));
    $truePositiveAlertCount = mulFraction($alertCount, $truePositiveRate);
    $noAlertConfiguredCost = multiplyFractions($truePositiveAlertCount, $noAlertCost);
    $alertConfiguredCost = mulFraction($alertCount, $alertRefundCost);
    $monitoringScenario = null;
    if (array_key_exists('monitoringThreshold', $input)) {
        $monitoringThreshold = probability($input['monitoringThreshold'], 'Monitoring threshold');
        if ($disputeCount[1] !== 1) {
            throw new DomainException('Monitoring scenario requires an integral dispute count');
        }
        $countScenario = countThresholdScenario($disputeCount[0], $transactionCount, $monitoringThreshold);
        $incrementalCostPerDeflection = addFractions($alertRefundCost, negateFraction($noAlertCost));
        $monitoringScenario = [
            'threshold' => $monitoringThreshold,
            'minimumDeflections' => $countScenario['minimumReduction'],
            'postDeflectionDisputeCount' => [$countScenario['postReductionCount'], 1],
            'postDeflectionRate' => $countScenario['postReductionRate'],
            'conditionalIncrementalAlertCostCents' => mulFraction(
                $countScenario['minimumReduction'],
                $incrementalCostPerDeflection,
            ),
            'isStrictlyBelowThreshold' => $countScenario['isStrictlyBelowThreshold'],
        ];
    }

    return [
        'disputeCount' => $disputeCount,
        'noAlertCostPerTrueChargebackCents' => $noAlertCost,
        'alertRefundCostPerAlertCents' => $alertRefundCost,
        'cashBreakEvenTruePositiveRate' => $cashBreakEven,
        'cashBreakEvenIsFeasible' => $cashBreakEven[0] <= $cashBreakEven[1],
        'breakEvenChargebackFeeAtFullTruePositiveCents' => $feeAtFullAccuracy,
        'truePositiveAlertCount' => $truePositiveAlertCount,
        'noAlertCostForConfiguredAlertsCents' => $noAlertConfiguredCost,
        'alertRefundCostForConfiguredAlertsCents' => $alertConfiguredCost,
        'cashDifferenceForConfiguredAlertsCents' => addFractions(
            $noAlertConfiguredCost,
            negateFraction($alertConfiguredCost),
        ),
        'monitoringScenario' => $monitoringScenario,
    ];
}

/**
 * Q4 model input shape:
 * array{monthlyVolumeCents:int, citShare:array{0:int,1:int},
 * citDisputeRate:array{0:int,1:int}, mitShare:array{0:int,1:int},
 * mitDisputeRate:array{0:int,1:int}, monitoringCountThreshold?:array{0:int,1:int},
 * monitoringCurrentDisputeCount?:int, monitoringTransactionCount?:int}.
 *
 * All outputs are dollar-volume calculations conditional on the supplied dispute
 * rates being dollar rates. They cannot establish Visa/Mastercard count-based
 * monitoring status because transaction counts, AOV, and scheme split are absent.
 *
 * @return array{citVolumeCents:array{0:int,1:int},mitVolumeCents:array{0:int,1:int},citDisputedVolumeCents:array{0:int,1:int},mitDisputedVolumeCents:array{0:int,1:int},totalDisputedVolumeCents:array{0:int,1:int},blendedDollarDisputeRate:array{0:int,1:int},isConditionalDollarInterpretation:bool,countThresholdComparison:array{threshold:null|array{0:int,1:int},formula:string,minimumReductionFormula:string,scenario:null|array{minimumReduction:int,postReductionCount:int,postReductionRate:array{0:int,1:int},isStrictlyBelowThreshold:bool}}}
 */
function q4Model(array $input): array
{
    $monthlyVolumeCents = nonNegativeInt($input['monthlyVolumeCents'] ?? null, 'Monthly volume cents');
    if ($monthlyVolumeCents === 0) {
        throw new InvalidArgumentException('Monthly volume cents must be positive');
    }
    $citShare = probability($input['citShare'] ?? [], 'CIT share');
    $mitShare = probability($input['mitShare'] ?? [], 'MIT share');
    if (addFractions($citShare, $mitShare) !== [1, 1]) {
        throw new InvalidArgumentException('CIT and MIT shares must sum to one');
    }
    $citRate = probability($input['citDisputeRate'] ?? [], 'CIT dispute rate');
    $mitRate = probability($input['mitDisputeRate'] ?? [], 'MIT dispute rate');
    $citVolume = mulFraction($monthlyVolumeCents, $citShare);
    $mitVolume = mulFraction($monthlyVolumeCents, $mitShare);
    $citDisputed = multiplyFractions($citVolume, $citRate);
    $mitDisputed = multiplyFractions($mitVolume, $mitRate);
    $hasCurrentCount = array_key_exists('monitoringCurrentDisputeCount', $input);
    $hasTransactionCount = array_key_exists('monitoringTransactionCount', $input);
    if ($hasCurrentCount !== $hasTransactionCount) {
        throw new InvalidArgumentException('Both monitoring count inputs are required together');
    }
    $monitoringThreshold = array_key_exists('monitoringCountThreshold', $input)
        ? probability($input['monitoringCountThreshold'], 'Monitoring count threshold')
        : null;
    if (($hasCurrentCount || $hasTransactionCount) && $monitoringThreshold === null) {
        throw new InvalidArgumentException('A monitoring count threshold is required with monitoring counts');
    }
    $countScenario = null;
    if ($hasCurrentCount) {
        $currentDisputeCount = nonNegativeInt($input['monitoringCurrentDisputeCount'], 'Monitoring current dispute count');
        $transactionCount = nonNegativeInt($input['monitoringTransactionCount'], 'Monitoring transaction count');
        if ($transactionCount === 0) {
            throw new InvalidArgumentException('Monitoring transaction count must be positive');
        }
        $countScenario = countThresholdScenario($currentDisputeCount, $transactionCount, $monitoringThreshold);
    }
    $thresholdLabel = $monitoringThreshold === null
        ? 'T'
        : $monitoringThreshold[0] . '/' . $monitoringThreshold[1];

    return [
        'citVolumeCents' => $citVolume,
        'mitVolumeCents' => $mitVolume,
        'citDisputedVolumeCents' => $citDisputed,
        'mitDisputedVolumeCents' => $mitDisputed,
        'totalDisputedVolumeCents' => addFractions($citDisputed, $mitDisputed),
        'blendedDollarDisputeRate' => addFractions(
            multiplyFractions($citShare, $citRate),
            multiplyFractions($mitShare, $mitRate),
        ),
        'isConditionalDollarInterpretation' => true,
        'countThresholdComparison' => [
            'threshold' => $monitoringThreshold,
            'formula' => 'D / N < ' . $thresholdLabel,
            'minimumReductionFormula' => 'max(0, D - (ceil(' . $thresholdLabel . ' × N) - 1))',
            'scenario' => $countScenario,
        ],
    ];
}

/**
 * Enforces the fixed identities printed by the direct-execution attachment.
 * @param array<string,mixed> $q1
 * @param array<string,mixed> $q4
 */
function assertExpectedIdentities(array $q1, array $q4): void
{
    $identities = [
        [$q1['disputeCount'] ?? null, [2_000, 1], 'Q1 dispute count'],
        [$q1['noAlertCostPerTrueChargebackCents'] ?? null, [6_500, 1], 'Q1 no-alert cost'],
        [$q1['alertRefundCostPerAlertCents'] ?? null, [7_900, 1], 'Q1 alert cost'],
        [$q1['cashBreakEvenTruePositiveRate'] ?? null, [79, 65], 'Q1 cash break-even'],
        [$q1['breakEvenChargebackFeeAtFullTruePositiveCents'] ?? null, [3_900, 1], 'Q1 fee break-even'],
        [$q1['monitoringScenario']['minimumDeflections'] ?? null, 501, 'Q1 monitoring deflections'],
        [$q1['monitoringScenario']['conditionalIncrementalAlertCostCents'] ?? null, [701_400, 1], 'Q1 monitoring incremental cost'],
        [$q4['citVolumeCents'] ?? null, [700_000_000, 1], 'Q4 CIT volume'],
        [$q4['mitVolumeCents'] ?? null, [300_000_000, 1], 'Q4 MIT volume'],
        [$q4['totalDisputedVolumeCents'] ?? null, [35_000_000, 1], 'Q4 total disputed volume'],
        [$q4['blendedDollarDisputeRate'] ?? null, [7, 200], 'Q4 blended rate'],
    ];

    foreach ($identities as [$actual, $expected, $label]) {
        if ($actual !== $expected) {
            throw new LogicException($label . ' identity failed');
        }
    }
}

/**
 * Calculates the explicitly conditional open-audit sensitivity used in Q1(c).
 *
 * @param array<string,mixed> $q1 Q1 output with a monitoring scenario
 * @return array{months:int,alertCostCents:array{0:int,1:int},assessmentCents:array{0:int,1:int},assessmentMinusAlertCents:array{0:int,1:int}}
 */
function q1OpenAuditScenario(array $q1, int $months, int $monthlyAssessmentCents): array
{
    if ($months <= 0 || $monthlyAssessmentCents < 0) {
        throw new InvalidArgumentException('Audit months and assessment must be non-negative, with months positive');
    }
    $monthlyAlertCost = $q1['monitoringScenario']['conditionalIncrementalAlertCostCents'] ?? null;
    if (!is_array($monthlyAlertCost) || count($monthlyAlertCost) !== 2) {
        throw new InvalidArgumentException('Q1 monitoring scenario is required for an open-audit scenario');
    }

    $alertCost = mulFraction($months, $monthlyAlertCost);
    $assessment = [checkedMultiply($months, $monthlyAssessmentCents), 1];

    return [
        'months' => $months,
        'alertCostCents' => $alertCost,
        'assessmentCents' => $assessment,
        'assessmentMinusAlertCents' => addFractions($assessment, negateFraction($alertCost)),
    ];
}

/** @param array{0:int,1:int} $value */
function formatExactMoney(array $value): string
{
    [$numerator, $denominator] = fraction($value[0] ?? 0, $value[1] ?? 0);
    if ($denominator === 1) {
        return formatMoney($numerator);
    }

    return $numerator . '/' . $denominator . ' cents';
}

/** @param array{0:int,1:int} $value */
function formatGroupedExactMoney(array $value): string
{
    [$numerator, $denominator] = fraction($value[0] ?? 0, $value[1] ?? 0);
    if ($denominator !== 1) {
        return $numerator . '/' . $denominator . ' cents';
    }

    $sign = $numerator < 0 ? '-' : '';
    $absolute = abs($numerator);
    $whole = intdiv($absolute, 100);
    $minor = $absolute % 100;

    return $sign . '$' . number_format($whole) . '.' . str_pad((string) $minor, 2, '0', STR_PAD_LEFT);
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === realpath(__FILE__)) {
    $q1 = q1Model([
        'transactionCount' => 100_000,
        'aovCents' => 5_000,
        'disputeRateBps' => 200,
        'chargebackFeeCents' => 2_500,
        'representmentWinRate' => [1, 5],
        'alertFeeCents' => 2_900,
        'alertCount' => 2_000,
        'truePositiveRate' => [1, 1],
        'monitoringThreshold' => [150, 10_000],
    ]);
    $q4 = q4Model([
        'monthlyVolumeCents' => 1_000_000_000,
        'citShare' => [7, 10],
        'citDisputeRate' => [2, 100],
        'mitShare' => [3, 10],
        'mitDisputeRate' => [7, 100],
        'monitoringCountThreshold' => [150, 10_000],
    ]);
    assertExpectedIdentities($q1, $q4);
    $q1Monitoring = $q1['monitoringScenario'];
    $q1OpenAudit = q1OpenAuditScenario($q1, 3, 2_500_000);
    $q4Comparison = $q4['countThresholdComparison'];

    echo "Q1 — Chargeback-alert cash economics\n";
    echo 'Monthly disputes: ' . number_format($q1['disputeCount'][0]) . "\n";
    echo 'No-alert expected cost per true chargeback: ' . formatGroupedExactMoney($q1['noAlertCostPerTrueChargebackCents']) . "\n";
    echo 'Alert/refund cost per alert: ' . formatGroupedExactMoney($q1['alertRefundCostPerAlertCents']) . "\n";
    echo 'Cash break-even true-positive share: ' . $q1['cashBreakEvenTruePositiveRate'][0] . '/' . $q1['cashBreakEvenTruePositiveRate'][1]
        . ' (' . formatFractionPercent($q1['cashBreakEvenTruePositiveRate']) . ") — infeasible\n";
    echo 'Break-even chargeback fee at 100% true-positive alerts: '
        . formatGroupedExactMoney($q1['breakEvenChargebackFeeAtFullTruePositiveCents']) . "\n";
    echo 'Perfect-precision 2,000-alert monthly loss: '
        . formatGroupedExactMoney(negateFraction($q1['cashDifferenceForConfiguredAlertsCents'])) . "\n";
    echo 'Monitoring threshold: ' . formatFractionPercent($q1Monitoring['threshold']) . " (strictly below)\n";
    echo 'Deflections required: ' . $q1Monitoring['minimumDeflections'] . '; post-deflection disputes: '
        . $q1Monitoring['postDeflectionDisputeCount'][0] . "\n";
    echo 'Conditional incremental cash cost: '
        . formatGroupedExactMoney($q1Monitoring['conditionalIncrementalAlertCostCents'])
        . " (if every deflection is a true-positive alert/refund)\n";
    echo 'Three-month alert cost for open-audit exit: ' . formatGroupedExactMoney($q1OpenAudit['alertCostCents']) . "\n";
    echo 'Conditional three-month assessment-minus-alert differential: '
        . formatGroupedExactMoney($q1OpenAudit['assessmentMinusAlertCents']) . "\n";
    echo "\nQ4 — Conditional dollar interpretation\n";
    echo 'CIT volume: ' . formatGroupedExactMoney($q4['citVolumeCents']) . '; disputed: ' . formatGroupedExactMoney($q4['citDisputedVolumeCents']) . "\n";
    echo 'MIT volume: ' . formatGroupedExactMoney($q4['mitVolumeCents']) . '; disputed: ' . formatGroupedExactMoney($q4['mitDisputedVolumeCents']) . "\n";
    echo 'Total disputed: ' . formatGroupedExactMoney($q4['totalDisputedVolumeCents'])
        . '; dollar-weighted rate: 3.5%' . "\n";
    echo "These are dollar results only if the stated 2%/7% rates are dollar rates.\n";
    echo 'Count-threshold formula: ' . $q4Comparison['formula'] . ";\n";
    echo 'Minimum-reduction formula: ' . $q4Comparison['minimumReductionFormula'] . ".\n";
    echo "No count scenario is calculated: dollar-only inputs do not supply D or N.\n";
    echo "Visa/Mastercard monitoring status cannot be determined: current public ratios are count-based, and counts, AOV, and scheme split are absent.\n";
}
