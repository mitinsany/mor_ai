# Evidence ledger — MOR.AI Payments Research Take-Home

Each entry is atomic: one claim, one direct source (or one calculation), a precise location, source class, status, confidence, and the evidence that could change it. “Conditional” means a required primary rule or merchant data is unavailable.

## Q1

#### Q1-C1a
- Claim: Prompt inputs produce 2,000 monthly disputes and $65.00/$79.00 unit costs.
- Class/status: Primary task fact + calculated / Verified conditional
- Source: Supplied take-home Q1; `php calculations.php`, Q1 labelled output
- Location: Q1 inputs and CLI lines “Monthly disputes”, “No-alert”, “Alert/refund”
- Confidence/change evidence: 100%; prompt inputs, fee recovery, billing or labour change it.

#### Q1-C1b
- Claim: Cash break-even is `79/65 = 121.538%`, infeasible.
- Class/status: Calculated / Verified conditional
- Source: `php calculations.php`
- Location: Q1 CLI line “Cash break-even true-positive share”
- Confidence/change evidence: 100%; same model-input changes as Q1-C1a.

#### Q1-C1c
- Claim: 2,000 perfect-precision alerts produce a $28,000.00 monthly loss.
- Class/status: Calculated / Verified conditional
- Source: `php calculations.php`
- Location: Q1 CLI line “Perfect-precision 2,000-alert monthly loss”
- Confidence/change evidence: 100%; same model-input changes as Q1-C1a.

#### Q1-C1d
- Claim: Full-accuracy fee break-even is $39.00.
- Class/status: Calculated / Verified conditional
- Source: `php calculations.php`
- Location: Q1 CLI line “Break-even chargeback fee”
- Confidence/change evidence: 100%; same model-input changes as Q1-C1a.

#### Q1-C2a
- Claim: An alert is a possible chargeback and timely merchant action/refund can avoid it.
- Class/status: Primary — product provider / Verified for workflow
- Source: [Ethoca, *FAQ \| How To Fight Chargebacks*, undated](https://www.ethoca.com/zh/node/153)
- Location: “What are Ethoca Alerts?” and action/outcome FAQ
- Confidence/change evidence: 95%; timing, coverage, service terms or issuer action change a case.

#### Q1-C2b
- Claim: The issuer-notice → merchant-action → avoided-chargeback flow is Mastercard/Ethoca product documentation.
- Class/status: Primary — Mastercard-owned product material / Verified for workflow
- Source: [Mastercard/Ethoca, *Ethoca Alerts — The Proven Value of Card Issuer & Merchant Collaboration*, 2019](https://www.mastercard.com/content/dam/public/mastercardcom/globalrisk/pdf/BROCHURE-Ethoca-Alerts-for-Issuers-2019.pdf)
- Location: “How it works”
- Confidence/change evidence: 95%; merchant configuration and timing change a case.

#### Q1-C3a
- Claim: Secondary GMAP reporting describes ECM 1.5% identification from 1 April 2027.
- Class/status: Secondary / Conditional — controlling bulletin/DIMP gated
- Source: [Justt, *Mastercard GMAP: A Guide*, updated 1 September 2026](https://justt.ai/blog/mastercard-gmap/)
- Location: “ECM” / “Core Changes” sections
- Confidence/change evidence: 70%; GLB 14127.1, DIMP and regional scope control.

#### Q1-C3b
- Claim: The all-Mastercard 501-deflection/$7,014.00 sensitivity is arithmetic, not a merchant forecast.
- Class/status: Calculated + conditional secondary premise / Conditional
- Source: `php calculations.php`; [Justt GMAP](https://justt.ai/blog/mastercard-gmap/)
- Location: Q1 CLI “Deflections required” / “Conditional incremental cash cost”; Justt ECM section
- Confidence/change evidence: 100% arithmetic; primary GMAP rule, card share and coverage change applicability.

#### Q1-C3c
- Claim: Reported GMAP exit needs three consecutive compliant months.
- Class/status: Secondary / Conditional — controlling bulletin/DIMP gated
- Source: [AltoPay, *Mastercard Global Merchant Audit Program (GMAP) \| 2026 Update*](https://www.altopay.com/gmap/)
- Location: exit/remediation section
- Confidence/change evidence: 65%; GLB 14127.1/DIMP changes it.

#### Q1-C3d
- Claim: The reported $25,000.00 month-7-to-11 assessment yields a $53,958.00 three-month conditional differential only with full pass-through.
- Class/status: Secondary + calculated / Conditional
- Source: [Chargeback Gurus, *The New Mastercard Global Merchant Audit Program*, 31 July 2026](https://www.chargebackgurus.com/blog/mastercard-global-merchant-audit-program-gmap?hs_amp=true); `php calculations.php`
- Location: assessment table; Q1 CLI “Conditional three-month assessment-minus-alert differential”
- Confidence/change evidence: 65% assessment premise/100% arithmetic; bulletin, audit stage and contract change it.

#### Q1-C4a
- Claim: Current ECP basis points are current-month chargeback count over preceding-month acquired Mastercard transaction count.
- Class/status: Primary — scheme rules / Verified as published
- Source: [Mastercard, *Security Rules and Procedures — Merchant Edition*, version 4 August 2026](https://www.mastercard.com/content/dam/mccom/shared/business/support/rules-pdfs/SPME-Manual.pdf)
- Location: §8.3.1, pp. 86–87, “Basis Points”
- Confidence/change evidence: 98%; current DIM/acquirer extract can define exceptions/refund treatment.

## Q2

#### Q2-C1a
- Claim: `01` means payment transaction in this fact pattern.
- Class/status: Primary task fact / Verified as prompt-supplied
- Source: Supplied take-home Q2
- Location: first paragraph, parenthetical after indicator `01`
- Confidence/change evidence: 100%; only a changed assignment changes this prompt fact.

#### Q2-C1b
- Claim: EMVCo Table 3.1 specifies `02` for recurring-agreement setup and recurring data.
- Class/status: Primary — EMVCo / Verified
- Source: [EMVCo, *Recurring and Instalment Transactions: Technical Features*](https://www.emvco.com/dynamic/emv-3-d-secure-whitepaper-v2/recurring-and-installment-transactions/technical-features/)
- Location: Table 3.1, cardholder-initiated flow
- Confidence/change evidence: 98%; superseding EMVCo bulletin/profile changes it.

#### Q2-C1c
- Claim: EMVCo describes the later 3RI MIT flow with Device Channel `03` and `threeRIInd=01`.
- Class/status: Primary — EMVCo / Verified
- Source: [EMVCo Technical Features](https://www.emvco.com/dynamic/emv-3-d-secure-whitepaper-v2/recurring-and-installment-transactions/technical-features/)
- Location: Table 3.1, merchant-initiated flow
- Confidence/change evidence: 97%; profile support/current specification changes it.

#### Q2-C2a
- Claim: Visa distinguishes authorization-for-amount from AV when payment is not required.
- Class/status: Primary — scheme rules / Verified
- Source: [Visa, *Core Rules and Visa Product and Service Rules*, 18 April 2026](https://usa.visa.com/content/dam/VCOM/download/about-visa/visa-rules-public.pdf)
- Location: §5.7.2.1 p. 415; Table 5-22 p. 463
- Confidence/change evidence: 96%; applicable regional/acquirer rule changes implementation.

#### Q2-C3a
- Claim: Visa’s CAVV/TAVV reuse exposure requires the stated ECI/data/fraud-assertion conditions.
- Class/status: Primary — scheme rules / Verified conditional
- Source: [Visa Core Rules, 18 April 2026](https://usa.visa.com/content/dam/VCOM/download/about-visa/visa-rules-public.pdf)
- Location: Table 11-153 p. 797, ID#0030228
- Confidence/change evidence: 99% for stated conditions; live ECI/auth/dispute facts decide applicability.

#### Q2-C4a
- Claim: Mastercard requires later recurring authorization to carry initial Trace ID and supports issuer validation of original relationship/SCA.
- Class/status: Primary — scheme rules / Verified
- Source: [Mastercard, *Transaction Processing Rules*, 10 June 2025](https://www.mastercard.us/content/dam/public/mastercardcom/na/global-site/documents/transaction-processing-rules.pdf?trk=public_post_comment-text)
- Location: §5.4 pp. 213–214; §5.9 p. 219
- Confidence/change evidence: 97%; issuer and acquirer trace change outcome.

#### Q2-C5a
- Claim: Initial remote CIT and genuine merchant-initiated subsequent payment are distinct in the EU SCA analysis.
- Class/status: Primary — regulator / Verified bounded by agreement UX
- Source: [EBA Q&A 2018_4031](https://www.eba.europa.eu/single-rule-book-qa/qna/view/publicId/2018_4031)
- Location: answer text
- Confidence/change evidence: 94%; actual trigger/consent evidence changes classification.

#### Q2-C6a
- Claim: EMVCo’s recurring flow supports a properly authenticated paid recurring setup.
- Class/status: Primary — EMVCo / Verified
- Source: [EMVCo Technical Features](https://www.emvco.com/dynamic/emv-3-d-secure-whitepaper-v2/recurring-and-installment-transactions/technical-features/)
- Location: Table 3.1, cardholder-initiated flow
- Confidence/change evidence: 98%; profile/AReq data changes implementation.

#### Q2-C6b
- Claim: EMVCo Use Case 2 is the genuine zero-amount recurring setup, not a paid-signup replacement.
- Class/status: Primary — EMVCo / Verified
- Source: [EMVCo, *Recurring and Instalment Transactions — Use Cases*](https://www.emvco.com/dynamic/emv-3-d-secure-whitepaper-v2/recurring-and-installment-transactions/use-cases/)
- Location: Use Case 2
- Confidence/change evidence: 97%; acquirer zero-value transport profile changes fields.

#### Q2-C7a
- Claim: Visa’s May 2017 framework uses `01` for first active credential entry and `10` for subsequent stored credential.
- Class/status: Primary — scheme framework / Verified as that version
- Source: [Visa, *Stored Credential Transaction Framework*, May 2017](https://usa.visa.com/content/dam/VCOM/global/support-legal/documents/stored-credential-transaction-framework-vbs-10-may-17.pdf)
- Location: card-absent table, pp. 11–12
- Confidence/change evidence: 98%; current certified profile governs production.

#### Q2-C7b
- Claim: Visa PSD2 Table 15 gives `F126.13=R`, blank `F63.3`, no initial `F125` original link, and profile-dependent initial `F22`.
- Class/status: Primary — scheme implementation guide / Verified as that version
- Source: [Visa, *PSD2 SCA Implementation Guide* v1.1, 11 March 2019](https://www.visa.de/dam/VCOM/regional/ve/unitedkingdom/PDF/sca/Visa_PSD2_SCA_Implementation_Guide.pdf)
- Location: Table 15 pp. 47–48; §4.6.2.1.3 pp. 88–90
- Confidence/change evidence: 98%; current acquirer certification/API mapping controls production.

#### Q2-C7c
- Claim: Visa subsequent recurring MIT publicly uses `F22=10`, `F126.13=R`, blank `F63.3`, and original link in F125, accepted from F62.2 or F125 Usage 2 Dataset 03.
- Class/status: Primary — scheme implementation guide / Verified as that version
- Source: [Visa PSD2 SCA Guide v1.1](https://www.visa.de/dam/VCOM/regional/ve/unitedkingdom/PDF/sca/Visa_PSD2_SCA_Implementation_Guide.pdf)
- Location: Table 15 pp. 47–48 and note
- Confidence/change evidence: 98%; certified acquirer/API transport controls production.

#### Q2-C8a
- Claim: Mastercard paid online recurring CIT uses `C103` in `DE48.22.5`, `DE61.4=4`, and retains Trace ID/TLID.
- Class/status: Primary — scheme rules / Verified
- Source: [Mastercard Transaction Processing Rules, 10 June 2025](https://www.mastercard.us/content/dam/public/mastercardcom/na/global-site/documents/transaction-processing-rules.pdf?trk=public_post_comment-text)
- Location: §5.4 pp. 184–185; §5.9 pp. 218–219
- Confidence/change evidence: 99%; registered-switch/acquirer mapping controls remaining CNP data.

#### Q2-C8b
- Claim: Mastercard zero-amount MIT-agreement setup uses ASI and retains setup Trace ID/TLID.
- Class/status: Primary — scheme rules / Verified
- Source: [Mastercard Transaction Processing Rules, 10 June 2025](https://www.mastercard.us/content/dam/public/mastercardcom/na/global-site/documents/transaction-processing-rules.pdf?trk=public_post_comment-text)
- Location: §5.9 pp. 218–219
- Confidence/change evidence: 99%; acquirer switch mapping controls message transport.

#### Q2-C8c
- Claim: Mastercard subsequent subscription MIT uses `M103`, `DE22.1=10`, `DE61.4=4`, `DE48.63`, and economically related TLID `DE105.002` where supported.
- Class/status: Primary — scheme rules / Verified
- Source: [Mastercard Transaction Processing Rules, 10 June 2025](https://www.mastercard.us/content/dam/public/mastercardcom/na/global-site/documents/transaction-processing-rules.pdf?trk=public_post_comment-text)
- Location: §5.9 p. 219
- Confidence/change evidence: 99%; support/profile controls `DE105.002` transport.

#### Q2-C9a
- Claim: Visa TID and OTID/original transaction link are distinct; later MIT receives a new TID.
- Class/status: Primary — scheme bulletin / Verified
- Source: [Visa, *Introduction of the Visa Network MIT Service*, 6 January 2022](https://usa.visa.com/content/dam/VCOM/regional/na/us/support-legal/documents/visa-us-merchant-business-news-digest-feb-2022.pdf)
- Location: pp. 1–2
- Confidence/change evidence: 98%; acquirer API names/mapping change transport, not distinction.

#### Q2-C9b
- Claim: Mastercard Trace ID is `DE63.1` + `DE63.2` + `DE15`; original TLID is `DE105.001`.
- Class/status: Primary — scheme rules / Verified
- Source: [Mastercard Transaction Processing Rules, 10 June 2025](https://www.mastercard.us/content/dam/public/mastercardcom/na/global-site/documents/transaction-processing-rules.pdf?trk=public_post_comment-text)
- Location: §2.10.1.1 p. 49; §5.9 p. 219
- Confidence/change evidence: 99%; response/API implementation changes extraction.

#### Q2-C10a
- Claim: A migration without scheme relationship link requires fresh correctly coded setup; a consumed CAVV is not a reusable credential.
- Class/status: Primary-supported operational inference / Verified conditional
- Source: [Visa Network MIT Service, 6 January 2022](https://usa.visa.com/content/dam/VCOM/regional/na/us/support-legal/documents/visa-us-merchant-business-news-digest-feb-2022.pdf); [Mastercard Transaction Processing Rules, §5.9 p. 219](https://www.mastercard.us/content/dam/public/mastercardcom/na/global-site/documents/transaction-processing-rules.pdf?trk=public_post_comment-text)
- Location: Visa pp. 1–2; Mastercard §5.9 p. 219
- Confidence/change evidence: 98%; supported migration and issuer acceptance testing change recovery path.

## Q3

#### Q3-C1a
- Claim: Current Mastercard merchant identity is DE42 and ECP is count-based.
- Class/status: Primary — scheme rules / Verified
- Source: [Mastercard Merchant Edition, 4 August 2026](https://www.mastercard.com/content/dam/mccom/shared/business/support/rules-pdfs/SPME-Manual.pdf)
- Location: §8.3.1, pp. 86–87
- Confidence/change evidence: 98%; newer manual/DIM edit changes it.

#### Q3-C2a
- Claim: Secondary reports identify gated GLB 14127.1 and reported effective date 1 April 2027.
- Class/status: Secondary / Conditional
- Source: [Martin, *Mastercard GMAP Arrives April 2027*, 3 August 2026](https://www.linkedin.com/pulse/mastercard-gmap-arrives-april-2027-heres-what-actually-changes-ik6ec)
- Location: source-note and effective-date section
- Confidence/change evidence: 85%; actual GLB 14127.1 controls.

#### Q3-C2b
- Claim: Secondary reports say ECM/HECM/EFM use populated PDS208.s2 rather than parent DE42.
- Class/status: Secondary / Conditional
- Source: [Martin, 3 August 2026](https://www.linkedin.com/pulse/mastercard-gmap-arrives-april-2027-heres-what-actually-changes-ik6ec)
- Location: “PayFacs can no longer rely on the parent MID”
- Confidence/change evidence: 80%; bulletin/clearing spec controls.

#### Q3-C2c
- Claim: The reported attribution change applies only when PDS208.s2 is populated.
- Class/status: Secondary / Conditional
- Source: [Nwabunze, *Mastercard Is Changing What Counts as Merchant Risk*, 4 August 2026](https://www.linkedin.com/pulse/mastercard-changing-what-counts-merchant-risk-chidubem-j-p-nwabunze-u6jke)
- Location: ¶49
- Confidence/change evidence: 80%; bulletin blank/fallback logic controls.

#### Q3-C3a
- Claim: No reviewed evidence establishes automatic grouping of separately boarded direct MIDs across ICAs when PDS208.s2 is absent.
- Class/status: Bounded inference / Conditional
- Source: [Mastercard Merchant Edition, §8.3.1](https://www.mastercard.com/content/dam/mccom/shared/business/support/rules-pdfs/SPME-Manual.pdf); [Nwabunze, ¶49](https://www.linkedin.com/pulse/mastercard-changing-what-counts-merchant-risk-chidubem-j-p-nwabunze-u6jke)
- Location: current DE42 baseline; conditional PDS statement
- Confidence/change evidence: 70%; GLB 14127.1/acquirer map could establish broader aggregation.

#### Q3-C4a
- Claim: Acquirers must investigate merchants above internal fraud, chargeback or decline thresholds.
- Class/status: Primary — scheme rules / Verified
- Source: [Mastercard Merchant Edition, 4 August 2026](https://www.mastercard.com/content/dam/mccom/shared/business/support/rules-pdfs/SPME-Manual.pdf)
- Location: §6.2.2.10, pp. 75–76
- Confidence/change evidence: 95%; contract sets exact action.

#### Q3-C5a
- Claim: Reported HDM gate is 5 cleared transactions, $5,000 combined event amount and 5%.
- Class/status: Secondary / Conditional
- Source: [Justt GMAP, updated 1 September 2026](https://justt.ai/blog/mastercard-gmap/)
- Location: HDM table
- Confidence/change evidence: 75%; GLB 14127.1 controls denominator/timing/region.

#### Q3-C5b
- Claim: Reported EDM gate is 5 cleared transactions, $10,000 combined event amount and 50%.
- Class/status: Secondary / Conditional
- Source: [Chargebacks911 GMAP, 12 August 2026](https://chargebacks911.com/global-merchant-audit-program-gmap/)
- Location: EDM section
- Confidence/change evidence: 72%; GLB 14127.1 controls.

## Q4

#### Q4-C1a
- Claim: VAMP is count(TC40 + TC15) / count(TC05) for applicable CNP VisaNet activity.
- Class/status: Primary — scheme fact sheet / Verified
- Source: [Visa, *VAMP external fact sheet 2025 v2*](https://corporate.visa.com/content/dam/VCOM/corporate/visa-perspectives/security-and-trust/documents/visa-acquirer-monitoring-program-fact-sheet-2025.pdf)
- Location: p. 1, “program metrics”
- Confidence/change evidence: 97%; guide/region/acquirer status and extract control application.

#### Q4-C1b
- Claim: The public 1 April 2026 AP/Canada/EU/US threshold is at least 150 bps and 1,500 events, subject to acquirer programme status.
- Class/status: Primary — scheme fact sheet / Verified
- Source: [Visa VAMP fact sheet 2025 v2](https://corporate.visa.com/content/dam/VCOM/corporate/visa-perspectives/security-and-trust/documents/visa-acquirer-monitoring-program-fact-sheet-2025.pdf)
- Location: p. 1, threshold footnote and merchant/acquirer criteria
- Confidence/change evidence: 97%; guide/region/acquirer status controls application.

#### Q4-C2a
- Claim: $140,000.00 CIT + $210,000.00 MIT = $350,000.00 and 3.5% only if supplied rates are dollar rates.
- Class/status: Calculated / Verified conditional
- Source: Supplied take-home Q4; `php calculations.php`
- Location: Q4 CLI output
- Confidence/change evidence: 100%; rate-unit meaning/scope changes it.

#### Q4-C2b
- Claim: VAMP implementation/remediation detail is governed by the VAMP Guide.
- Class/status: Primary — scheme rules / Verified conditional
- Source: [Visa Core Rules, 18 April 2026](https://usa.visa.com/dam/VCOM/download/about-visa/visa-rules-public.pdf)
- Location: §10.4.3.1 p. 633
- Confidence/change evidence: 95%; guide rounding/remediation and region control.

#### Q4-C2c
- Claim: `max(0, N − (ceil(0.015 × S) − 1))` is the exact strict-below-150-bps integer planning formula for fixed count denominator S.
- Class/status: Calculated / Verified conditional
- Source: `php calculations.php`
- Location: Q4 CLI line “Minimum-reduction formula”
- Confidence/change evidence: 100% arithmetic; the VAMP Guide’s rounding/remediation rules control operational use.

#### Q4-C3a
- Claim: Current ECP is chargeback count / preceding-month Mastercard transaction count at DE42; thresholds/assessments are gated.
- Class/status: Primary — scheme rules / Verified
- Source: [Mastercard Merchant Edition, 4 August 2026](https://www.mastercard.com/content/dam/mccom/shared/business/support/rules-pdfs/SPME-Manual.pdf)
- Location: §§8.3.1–8.3.4, pp. 86–88
- Confidence/change evidence: 98%; DIM/Pricing and merchant extract control operative status.

#### Q4-C4a
- Claim: Secondary EFM descriptions require distinct e-commerce, fraud, 3DS and regional inputs.
- Class/status: Secondary / Conditional
- Source: [Stripe, *Dispute and fraud card monitoring programs*](https://docs.stripe.com/disputes/monitoring-programs?locale=en-GB)
- Location: “EFM: Mastercard Excessive Fraud Merchant Compliance Program”
- Confidence/change evidence: 75%; DIM/Pricing and merchant history control.

#### Q4-C4b
- Claim: Reported 2027 GMAP combined fraud/non-fraud-dispute mechanics are gated-bulletin secondary evidence.
- Class/status: Secondary / Conditional
- Source: [Justt GMAP, updated 1 September 2026](https://justt.ai/blog/mastercard-gmap/)
- Location: “Core Changes” / HDM and EDM sections
- Confidence/change evidence: 72%; GLB 14127.1 data dictionary controls.

#### Q4-C5a
- Claim: Mastercard requires monitoring of repeated/velocity authorizations and recommends ceasing resends after MAC `03` or `21`.
- Class/status: Primary — scheme rules / Verified
- Source: [Mastercard Merchant Edition, 4 August 2026](https://www.mastercard.com/content/dam/mccom/shared/business/support/rules-pdfs/SPME-Manual.pdf)
- Location: §§6.2.2.2 and 6.2.2.6, pp. 68–69 and 72
- Confidence/change evidence: 96–98%; message data/acquirer policy determines case classification.

#### Q4-C5b
- Claim: A declined retry is not itself an ECP/EFM numerator, though later fraud/chargebacks and acquirer controls can be affected.
- Class/status: Primary-supported operational inference / Conditional
- Source: [Mastercard Merchant Edition, 4 August 2026](https://www.mastercard.com/content/dam/mccom/shared/business/support/rules-pdfs/SPME-Manual.pdf)
- Location: §§6.2.2.2, 6.2.2.10 and 8.3.1, pp. 68–76 and 86–87
- Confidence/change evidence: 95%; transaction/dispute linkage and gated EFM detail control.
