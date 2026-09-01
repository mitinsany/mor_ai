<?php

declare(strict_types=1);

require __DIR__ . '/calculations.php';

/** @param mixed $actual @param mixed $expected */
function same(mixed $actual, mixed $expected, string $label): void
{
    if ($actual !== $expected) {
        throw new RuntimeException(
            $label . ': expected ' . var_export($expected, true)
            . ', got ' . var_export($actual, true),
        );
    }
}

/** @param callable(): void $operation */
function throws(callable $operation, string $exceptionClass, string $label): void
{
    try {
        $operation();
    } catch (Throwable $exception) {
        if ($exception instanceof $exceptionClass) {
            return;
        }

        throw new RuntimeException($label . ': unexpected ' . $exception::class);
    }

    throw new RuntimeException($label . ': expected ' . $exceptionClass);
}

/** @param list<string> $lines */
function containsLine(array $lines, string $needle, string $label): void
{
    foreach ($lines as $line) {
        if (str_contains($line, $needle)) {
            return;
        }
    }

    throw new RuntimeException($label . ': missing ' . $needle);
}

// These cases catch a missing reduction, sign normalization, or cent formatting bug.
same(fraction(200, 100), [2, 1], 'fraction reduction');
same(fraction(-6, -8), [3, 4], 'fraction sign normalization');
same(mulFraction(50, [4, 5]), [40, 1], 'fraction multiplication');
same(formatMoney(1999), '$19.99', 'minor-unit formatting');
same(formatMoney(-1999), '-$19.99', 'negative minor-unit formatting');
throws(static fn(): array => fraction(1, 0), InvalidArgumentException::class, 'zero denominator');

// A numerator exactly at the threshold needs one unit of reduction; one below needs none.
same(
    minimumNumeratorReductionBelowThreshold(100, 10_000, [1, 100]),
    1,
    'strict threshold reduction at equality',
);
same(
    minimumNumeratorReductionBelowThreshold(99, 10_000, [1, 100]),
    0,
    'strict threshold reduction below equality',
);
same(
    minimumNumeratorReductionBelowThreshold(4, 10, [1, 3]),
    1,
    'strict threshold reduction with a non-integral boundary',
);
same(
    minimumNumeratorReductionBelowThreshold(3, 10, [1, 3]),
    0,
    'strict threshold reduction already below a non-integral boundary',
);
throws(
    static fn(): int => minimumNumeratorReductionBelowThreshold(0, 10, [0, 1]),
    DomainException::class,
    'strictly below zero is impossible for a non-negative count',
);

$q1 = q1Model([
    'transactionCount' => 100_000,
    'aovCents' => 5_000,
    'disputeRateBps' => 200,
    'chargebackFeeCents' => 2_500,
    'representmentWinRate' => [1, 5],
    'alertFeeCents' => 2_900,
    'alertCount' => 1_000,
    'truePositiveRate' => [3, 5],
]);

same($q1['disputeCount'], [2_000, 1], 'Q1 monthly dispute count');
same($q1['noAlertCostPerTrueChargebackCents'], [6_500, 1], 'Q1 no-alert expected cost');
same($q1['alertRefundCostPerAlertCents'], [7_900, 1], 'Q1 alert and refund cost');
same($q1['cashBreakEvenTruePositiveRate'], [79, 65], 'Q1 cash break-even share');
same($q1['cashBreakEvenIsFeasible'], false, 'Q1 cash break-even feasibility');
same($q1['breakEvenChargebackFeeAtFullTruePositiveCents'], [3_900, 1], 'Q1 full-accuracy fee break-even');
same($q1['truePositiveAlertCount'], [600, 1], 'Q1 arbitrary true-positive alert count');
same($q1['noAlertCostForConfiguredAlertsCents'], [3_900_000, 1], 'Q1 arbitrary no-alert cash formula');
same($q1['alertRefundCostForConfiguredAlertsCents'], [7_900_000, 1], 'Q1 arbitrary alert cash formula');
same($q1['cashDifferenceForConfiguredAlertsCents'], [-4_000_000, 1], 'Q1 arbitrary alert cash difference');

$q1Monitoring = q1Model([
    'transactionCount' => 100_000,
    'aovCents' => 5_000,
    'disputeRateBps' => 200,
    'chargebackFeeCents' => 2_500,
    'representmentWinRate' => [1, 5],
    'alertFeeCents' => 2_900,
    'alertCount' => 1_000,
    'truePositiveRate' => [3, 5],
    'monitoringThreshold' => [150, 10_000],
]);
same($q1Monitoring['monitoringScenario']['minimumDeflections'], 501, 'Q1 monitoring deflections');
same($q1Monitoring['monitoringScenario']['postDeflectionDisputeCount'], [1_499, 1], 'Q1 monitoring post-deflection count');
same($q1Monitoring['monitoringScenario']['postDeflectionRate'], [1_499, 100_000], 'Q1 monitoring strictly-below rate');
same($q1Monitoring['monitoringScenario']['conditionalIncrementalAlertCostCents'], [701_400, 1], 'Q1 monitoring conditional incremental cost');
same($q1Monitoring['monitoringScenario']['isStrictlyBelowThreshold'], true, 'Q1 monitoring threshold result');
$q1OpenAudit = q1OpenAuditScenario($q1Monitoring, 3, 2_500_000);
same($q1OpenAudit['alertCostCents'], [2_104_200, 1], 'Q1 three-month open-audit alert cost');
same($q1OpenAudit['assessmentCents'], [7_500_000, 1], 'Q1 three-month conditional assessment');
same($q1OpenAudit['assessmentMinusAlertCents'], [5_395_800, 1], 'Q1 three-month conditional differential');
throws(
    static fn(): array => q1Model([
        'transactionCount' => -1,
        'aovCents' => 5_000,
        'disputeRateBps' => 200,
        'chargebackFeeCents' => 2_500,
        'representmentWinRate' => [1, 5],
        'alertFeeCents' => 2_900,
        'alertCount' => 0,
        'truePositiveRate' => [1, 1],
    ]),
    InvalidArgumentException::class,
    'Q1 negative transaction count',
);
throws(
    static fn(): array => q1Model([
        'transactionCount' => 1,
        'aovCents' => -1,
        'disputeRateBps' => 0,
        'chargebackFeeCents' => 1,
        'representmentWinRate' => [0, 1],
        'alertFeeCents' => 0,
        'alertCount' => 0,
        'truePositiveRate' => [0, 1],
    ]),
    InvalidArgumentException::class,
    'Q1 negative money input',
);
throws(
    static fn(): array => q1Model([
        'transactionCount' => 1,
        'aovCents' => 1,
        'disputeRateBps' => 10_001,
        'chargebackFeeCents' => 1,
        'representmentWinRate' => [0, 1],
        'alertFeeCents' => 0,
        'alertCount' => 0,
        'truePositiveRate' => [0, 1],
    ]),
    InvalidArgumentException::class,
    'Q1 dispute rate above one',
);
throws(
    static fn(): array => q1Model([
        'transactionCount' => 1,
        'aovCents' => 1,
        'disputeRateBps' => 0,
        'chargebackFeeCents' => 1,
        'representmentWinRate' => [2, 1],
        'alertFeeCents' => 0,
        'alertCount' => 0,
        'truePositiveRate' => [0, 1],
    ]),
    InvalidArgumentException::class,
    'Q1 probability above one',
);

$q4 = q4Model([
    'monthlyVolumeCents' => 1_000_000_000,
    'citShare' => [7, 10],
    'citDisputeRate' => [2, 100],
    'mitShare' => [3, 10],
    'mitDisputeRate' => [7, 100],
]);

same($q4['citVolumeCents'], [700_000_000, 1], 'Q4 CIT dollar volume');
same($q4['mitVolumeCents'], [300_000_000, 1], 'Q4 MIT dollar volume');
same($q4['citDisputedVolumeCents'], [14_000_000, 1], 'Q4 CIT disputed dollars');
same($q4['mitDisputedVolumeCents'], [21_000_000, 1], 'Q4 MIT disputed dollars');
same($q4['totalDisputedVolumeCents'], [35_000_000, 1], 'Q4 total disputed dollars');
same($q4['blendedDollarDisputeRate'], [7, 200], 'Q4 blended dollar rate');
same($q4['isConditionalDollarInterpretation'], true, 'Q4 conditional dollar interpretation');

$q4Symbolic = q4Model([
    'monthlyVolumeCents' => 1_000_000_000,
    'citShare' => [7, 10],
    'citDisputeRate' => [2, 100],
    'mitShare' => [3, 10],
    'mitDisputeRate' => [7, 100],
    'monitoringCountThreshold' => [150, 10_000],
]);
same($q4Symbolic['countThresholdComparison']['threshold'], [3, 200], 'Q4 symbolic count threshold');
same($q4Symbolic['countThresholdComparison']['scenario'], null, 'Q4 does not invent count scenario');

$q4CountScenario = q4Model([
    'monthlyVolumeCents' => 1_000_000_000,
    'citShare' => [7, 10],
    'citDisputeRate' => [2, 100],
    'mitShare' => [3, 10],
    'mitDisputeRate' => [7, 100],
    'monitoringCountThreshold' => [150, 10_000],
    'monitoringCurrentDisputeCount' => 2_000,
    'monitoringTransactionCount' => 100_000,
]);
same($q4CountScenario['countThresholdComparison']['scenario']['minimumReduction'], 501, 'Q4 supplied-count reduction');
same($q4CountScenario['countThresholdComparison']['scenario']['postReductionRate'], [1_499, 100_000], 'Q4 supplied-count rate');
throws(
    static fn(): array => q4Model([
        'monthlyVolumeCents' => 1_000,
        'citShare' => [7, 10],
        'citDisputeRate' => [2, 100],
        'mitShare' => [2, 10],
        'mitDisputeRate' => [7, 100],
    ]),
    InvalidArgumentException::class,
    'Q4 shares must sum to one',
);
throws(
    static fn(): array => q4Model([
        'monthlyVolumeCents' => 0,
        'citShare' => [7, 10],
        'citDisputeRate' => [2, 100],
        'mitShare' => [3, 10],
        'mitDisputeRate' => [7, 100],
    ]),
    InvalidArgumentException::class,
    'Q4 zero monthly volume',
);
throws(
    static fn(): int => minimumNumeratorReductionBelowThreshold(1, 0, [1, 100]),
    InvalidArgumentException::class,
    'threshold helper zero denominator',
);
throws(
    static fn(): int => minimumNumeratorReductionBelowThreshold(1, 100, [-1, 100]),
    InvalidArgumentException::class,
    'threshold helper negative threshold',
);

$moduleCommand = escapeshellarg(PHP_BINARY) . ' -r ' . escapeshellarg(
    'require ' . var_export(__DIR__ . '/calculations.php', true) . ';',
);
$moduleOutput = [];
$moduleExit = 0;
exec($moduleCommand, $moduleOutput, $moduleExit);
same($moduleExit, 0, 'module require exit status');
same($moduleOutput, [], 'module require is silent');

$directOutput = [];
$directExit = 0;
exec(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__DIR__ . '/calculations.php'), $directOutput, $directExit);
same($directExit, 0, 'direct calculation exit status');
containsLine($directOutput, 'Q1 — Chargeback-alert cash economics', 'direct Q1 heading');
containsLine($directOutput, 'Deflections required: 501', 'direct Q1 monitoring scenario');
containsLine($directOutput, 'Conditional incremental cash cost: $7,014.00', 'direct Q1 monitoring cost');
containsLine($directOutput, 'Perfect-precision 2,000-alert monthly loss: $28,000', 'direct Q1 perfect-precision loss');
containsLine($directOutput, 'Three-month alert cost for open-audit exit: $21,042', 'direct Q1 three-month alert cost');
containsLine($directOutput, 'Conditional three-month assessment-minus-alert differential: $53,958', 'direct Q1 conditional audit differential');
containsLine($directOutput, 'Q4 — Conditional dollar interpretation', 'direct Q4 heading');
containsLine($directOutput, 'Count-threshold formula:', 'direct Q4 symbolic formula');
containsLine($directOutput, 'No count scenario is calculated', 'direct Q4 no-invented-count statement');

assertExpectedIdentities($q1Monitoring, $q4);
$invalidIdentityQ1 = $q1Monitoring;
$invalidIdentityQ1['disputeCount'] = [1, 1];
throws(
    static fn(): null => assertExpectedIdentities($invalidIdentityQ1, $q4),
    LogicException::class,
    'direct identity assertion catches Q1 regression',
);

echo "All calculation tests passed.\n";
