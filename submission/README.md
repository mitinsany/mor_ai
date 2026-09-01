# MOR.AI take-home submission

Requirements: PHP 8+ only. No packages or services are required.

## Files

- `mor_ai_takehome_solution.md` — the submission-ready answer to all four questions.
- `research_notes.md` — compact evidence ledger with atomic claim IDs, source grading, confidence and limitations.
- `calculations.php` — deterministic integer/rational calculation model and labelled Q1/Q4 output.
- `calculations_test.php` — PHP test suite for the calculation model.

## Run

From this directory, run:

```sh
php calculations_test.php
php calculations.php
```

The first command checks the arithmetic model; the second prints the exact numeric outputs used in the Markdown submission.
