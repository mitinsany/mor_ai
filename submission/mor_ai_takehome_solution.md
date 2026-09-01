# MOR.AI Payments Research Take-Home

## Executive summary

On the stated inputs, an alert/refund costs $79.00 against an expected $65.00 chargeback cost, so even perfect precision loses $28,000.00 per 2,000 alerts and cash break-even is an infeasible 79/65 (121.538%). The alerts can still be a tightly bounded monitoring-risk control under a conditional, secondary-evidence 2027 Mastercard sensitivity. The correct subscription anchor is the approved, properly coded initial recurring CIT—not a network token or a second $0 verification—and the $10m profile yields only a conditional dollar-loss estimate, not Visa/Mastercard monitoring status.

All numerical values below are reproduced verbatim by `php calculations.php`. Every bracketed claim marker links to an atomic ledger entry in [research_notes.md](research_notes.md).

## Question 1 — Do chargeback alerts pay?

### Direct answer

**(a) Cash model.** Monthly disputes are 2,000 (`100,000 × 2%`). The expected cost of a true chargeback is $65.00 (`$50 × (1 − 20%) + $25`); a resolved alert costs $79.00 (`$50 + $29`). If `p` is the share of bought alerts that would otherwise become chargebacks, incremental value is `p × $65.00 − $79.00`; break-even is `p = 79/65 = 121.538%`, so alerts lose money on the stated cash basis. At perfect precision, 2,000 alerts produce a $28,000.00 monthly loss; unsupplied representation labour and fee recovery are excluded. [Q1-C1a](research_notes.md#q1-c1a) [Q1-C1b](research_notes.md#q1-c1b) [Q1-C1c](research_notes.md#q1-c1c)

**(b) Fee break-even.** At 100% true-positive alerts, the chargeback fee must exceed $39.00: `$50 + $29 − (80% × $50)`. [Q1-C1d](research_notes.md#q1-c1d)

**(c) Monitoring rationale.** Reported, gated-bulletin 2027 GMAP material describes an ECM 1.500% threshold. In an explicitly all-Mastercard sensitivity with 100,000 prior-month sales and 2,000 chargebacks, 501 genuine deflections leave 1499 chargebacks and cost $7,014.00; that may prevent audit entry, but it is neither a forecast nor a confirmed merchant saving. [Q1-C3a](research_notes.md#q1-c3a) [Q1-C3b](research_notes.md#q1-c3b)

For an already-open audit, the reported three-month exit condition produces a $21,042.00 three-month alert sensitivity. Only if the reported $25,000.00 month-7-to-11 assessment applies in all three months and is fully passed through is the gross conditional assessment-minus-alert differential $53,958.00. [Q1-C3c](research_notes.md#q1-c3c) [Q1-C3d](research_notes.md#q1-c3d)

**(d) Metrics affected.** A timely, correctly resolved alert prevents the formal chargeback and can remove that would-be event from the chargeback-count numerator and the prompt’s chargeback fee. It does not erase the underlying fraud/dispute signal, prove removal of a separately reported fraud claim, or establish removal of the original sale from a prior-month denominator. [Q1-C2a](research_notes.md#q1-c2a) [Q1-C2b](research_notes.md#q1-c2b) [Q1-C4a](research_notes.md#q1-c4a)

### Sources and claim mapping

#### Primary

| Claim | Direct source and precise location |
|---|---|
| [Q1-C2a](research_notes.md#q1-c2a) | [Ethoca FAQ, undated, “What are Ethoca Alerts?”](https://www.ethoca.com/zh/node/153) |
| [Q1-C2b](research_notes.md#q1-c2b) | [Mastercard/Ethoca, *Ethoca Alerts*, 2019, “How it works”](https://www.mastercard.com/content/dam/public/mastercardcom/globalrisk/pdf/BROCHURE-Ethoca-Alerts-for-Issuers-2019.pdf) |
| [Q1-C4a](research_notes.md#q1-c4a) | [Mastercard Merchant Edition, 4 August 2026, §8.3.1, pp. 86–87](https://www.mastercard.com/content/dam/mccom/shared/business/support/rules-pdfs/SPME-Manual.pdf) |

#### Secondary

| Claim | Direct source and precise location |
|---|---|
| [Q1-C3a](research_notes.md#q1-c3a), [Q1-C3b](research_notes.md#q1-c3b) | [Justt, *Mastercard GMAP: A Guide*, updated 1 September 2026, ECM section](https://justt.ai/blog/mastercard-gmap/) |
| [Q1-C3c](research_notes.md#q1-c3c), [Q1-C3d](research_notes.md#q1-c3d) | [AltoPay, *GMAP \| 2026 Update*, threshold/assessment section](https://www.altopay.com/gmap/) and [Chargeback Gurus, 31 July 2026, programme section](https://www.chargebackgurus.com/blog/mastercard-global-merchant-audit-program-gmap?hs_amp=true) |

### Confidence

- [Q1-C1a](research_notes.md#q1-c1a)–[Q1-C1d](research_notes.md#q1-c1d): **100% conditional on prompt inputs and stated cash model**; actual fee recovery, billing and labour would change it.
- [Q1-C2a](research_notes.md#q1-c2a)/[Q1-C2b](research_notes.md#q1-c2b): **95%** for timely workflow; coverage, timing and issuer action can change a case.
- [Q1-C4a](research_notes.md#q1-c4a): **98%** for the public current formula; the reporting extract determines exceptions/refund treatment.
- [Q1-C3a](research_notes.md#q1-c3a)–[Q1-C3d](research_notes.md#q1-c3d): **65–70%** because the controlling 2027 bulletin/DIMP is gated.

### What I could not verify

I could not inspect the controlling 2027 Mastercard bulletin/DIMP, the exact GMAP data dictionary, regional applicability, alert-to-FLD treatment, denominator treatment after a refund, the merchant’s Mastercard share, or acquirer assessment pass-through. Those gaps prevent a programme-status or merchant-saving claim.

### Working and rejected evidence

Executed `php calculations.php`; its Q1 labelled output is used verbatim above. I modelled only supplied downstream cash flows and rejected generic chargeback-ratio pages and vendor marketing totals as evidence of this merchant’s economics.

## Question 2 — Authentication and stored credentials: the $0 anchor

### Direct answer

**(a) What breaks.** The prompt identifies `threeDSRequestorAuthenticationInd=01` as payment transaction; the cited EMVCo page independently specifies `02` for recurring agreement setup. Thus `01` loses recurring-agreement context but is not itself proof that successful SCA failed. Reusing the $49.99 CAVV on the later $0 verification creates Visa’s narrowly conditional reuse exposure; the $0 verification is a distinct non-purchase and should not replace the paid initial recurring CIT as the normal anchor. [Q2-C1a](research_notes.md#q2-c1a) [Q2-C1b](research_notes.md#q2-c1b) [Q2-C3a](research_notes.md#q2-c3a) [Q2-C2a](research_notes.md#q2-c2a)

An absent/wrong original reference impairs issuer retrieval of the recurring relationship and can increase decline/validation risk, but does not prove a deterministic decline. In the EU, this is principally a coding/evidence failure: initial remote CIT SCA and a genuine subsequent MIT remain distinct from a cardholder-triggered stored-credential use. [Q2-C4a](research_notes.md#q2-c4a) [Q2-C5a](research_notes.md#q2-c5a)

**(b) Correct flow.** Obtain and retain the recurring agreement. Authenticate the paid $49.99 CIT with 3DS `02` and the applicable recurring data, submit one correctly coded initial recurring authorization, preserve its response and 3DS evidence, then send linked recurring MITs without resending the consumed CAVV; use new 3RI only when a later authentication is required. [Q2-C1b](research_notes.md#q2-c1b) [Q2-C1c](research_notes.md#q2-c1c) [Q2-C6a](research_notes.md#q2-c6a)

| Network and message | Exact public coding | Relationship reference and caveat |
|---|---|---|
| **Visa — paid initial recurring CIT ($49.99)** | 3DS `02`; recurring amount/currency/frequency/expiry as applicable. `F126.13=R`; `F63.3` blank; **no** original-ID link in `F125`. `F22` is versioned: 2017 framework says `01` for first active entry; 2019/current stored-credential profiles describe/allow `10`. | Retain the initial response TID and separately the OTID/original link. Use the current acquirer’s certified VisaNet/API mapping; no universal initial-`10` claim is made. [Q2-C7a](research_notes.md#q2-c7a) [Q2-C7b](research_notes.md#q2-c7b) |
| **Visa — genuine $0 recurring setup (only when no payment is due)** | 3DS `02`, `purchaseAmount=0`, recurring data; zero-value recurring CIT/AV with `F126.13=R`, CAVV/ECI (TAVV if token), no `F34` exemption flag and no original-ID link in `F125`; `F22` follows certified profile. | Preserve setup TID and exact OTID/original-link handling for later MIT. This is not a second verification to replace a $49.99 anchor. [Q2-C6b](research_notes.md#q2-c6b) [Q2-C7b](research_notes.md#q2-c7b) |
| **Visa — subsequent subscription MIT** | Applicable stored-credential entry mode, publicly `F22=10`; `F126.13=R`; `F63.3` blank; original link in `F125`. | The source says the link may be accepted in `F62.2` or `F125 Usage 2 Dataset 03` and forwarded in `F125`; TID is not OTID. Certified acquirer/API transport controls. [Q2-C7c](research_notes.md#q2-c7c) [Q2-C9a](research_notes.md#q2-c9a) |
| **Mastercard — paid initial recurring CIT ($49.99)** | 3DS `02`; `C103` in `DE48.22.5`; online CNP `DE61.4=4`. | Retain approval Trace ID and response TLID `DE105.001`; remaining initial CNP POS data follow registered-switch/acquirer mapping. [Q2-C8a](research_notes.md#q2-c8a) |
| **Mastercard — genuine $0 setup (only when no payment is due)** | Properly coded account status inquiry (ASI) when the MIT agreement is established for zero amount; initial subscription identifier `C103` and online CNP `DE61.4=4`. | Retain ASI Trace ID/TLID. This is a separate zero-due flow, not the paid-signup replacement. [Q2-C8b](research_notes.md#q2-c8b) |
| **Mastercard — subsequent subscription MIT** | `M103` in `DE48.22.5`; online CNP `DE22.1=10` and `DE61.4=4`; Trace ID in `DE48.63`. | Send original CIT TLID `DE105.001` as economically related TLID in `DE105.002` where supported. [Q2-C8c](research_notes.md#q2-c8c) |

**(c) Anchor.** The anchor is issued by an approved authorization/ASI, not network-token provisioning. Here it is the approved $49.99 initial recurring CIT response; a genuine $0 setup anchors from its own correctly coded AV/ASI. Preserve Visa TID and OTID separately; preserve Mastercard Trace ID and TLID separately. [Q2-C9a](research_notes.md#q2-c9a) [Q2-C9b](research_notes.md#q2-c9b)

**(d) PSP migration.** Move the token and lifecycle metadata **plus** scheme, relationship identifiers, consent/agreement, initial CIT/ASI evidence, recurring flags, and `dsTransID`/`acsTransID`/ECI/result. Never move a CAVV as a reusable credential; without the scheme link, establish fresh consent and a new correctly coded initial CIT/ASI. [Q2-C10a](research_notes.md#q2-c10a)

### Sources and claim mapping

#### Primary

| Claim | Direct source and precise location |
|---|---|
| [Q2-C1b](research_notes.md#q2-c1b), [Q2-C1c](research_notes.md#q2-c1c), [Q2-C6a](research_notes.md#q2-c6a) | [EMVCo Technical Features, Table 3.1 and CIT/MIT flows](https://www.emvco.com/dynamic/emv-3-d-secure-whitepaper-v2/recurring-and-installment-transactions/technical-features/) |
| [Q2-C2a](research_notes.md#q2-c2a), [Q2-C3a](research_notes.md#q2-c3a) | [Visa Core Rules, 18 April 2026, §5.7.2.1 p. 415; Table 5-22 p. 463; Table 11-153 p. 797](https://usa.visa.com/content/dam/VCOM/download/about-visa/visa-rules-public.pdf) |
| [Q2-C4a](research_notes.md#q2-c4a), [Q2-C8a](research_notes.md#q2-c8a)–[Q2-C8c](research_notes.md#q2-c8c) | [Mastercard Transaction Processing Rules, 10 June 2025, §§2.10.1.1, 5.4, 5.9](https://www.mastercard.us/content/dam/public/mastercardcom/na/global-site/documents/transaction-processing-rules.pdf?trk=public_post_comment-text) |
| [Q2-C5a](research_notes.md#q2-c5a) | [EBA Q&A 2018_4031](https://www.eba.europa.eu/single-rule-book-qa/qna/view/publicId/2018_4031) |
| [Q2-C7a](research_notes.md#q2-c7a)–[Q2-C7c](research_notes.md#q2-c7c), [Q2-C9a](research_notes.md#q2-c9a) | [Visa Stored Credential Framework, May 2017, pp. 11–12](https://usa.visa.com/content/dam/VCOM/global/support-legal/documents/stored-credential-transaction-framework-vbs-10-may-17.pdf); [Visa PSD2 Guide, Table 15 pp. 47–48](https://www.visa.de/dam/VCOM/regional/ve/unitedkingdom/PDF/sca/Visa_PSD2_SCA_Implementation_Guide.pdf); [Visa Network MIT Service, 6 January 2022, pp. 1–2](https://usa.visa.com/content/dam/VCOM/regional/na/us/support-legal/documents/visa-us-merchant-business-news-digest-feb-2022.pdf) |

#### Secondary

None relied upon for material claims.

### Confidence

- [Q2-C1a](research_notes.md#q2-c1a) is **prompt-supplied**; [Q2-C1b](research_notes.md#q2-c1b)/[Q2-C1c](research_notes.md#q2-c1c) are **97–98%** for what EMVCo’s cited table states.
- Visa paid/zero/subsequent flags are **96–98%**, with the intentionally preserved certified-acquirer first-CIT/API caveat. Mastercard field claims are **99%**, subject to registered-switch mapping where stated.
- SCA, issuer, migration and dispute conclusions are **94–98%**; live AReq/ARes, issuer results, agreement UX and PSP mapping would determine a production case.

### What I could not verify

I could not inspect the live AReq/ARes, ECI, issuer response, acquirer certification, regional registered-switch profile or either PSP’s API mapping. The cited EMVCo page does not itself enumerate the prompt’s `01` wording; that is why `01` is identified as prompt-supplied rather than independently defined by that location.

### Working and rejected evidence

I separated 3DS, authorization, issuer decisioning, disputes and EU SCA, then compared current Visa Rules with the 2017 framework and 2019 PSD2 guide. PSP blogs, unofficial rule copies and confidential-spec mirrors were discovery-only and rejected for material claims.

## Question 3 — Twelve MIDs, three acquirers

### Direct answer

**(a) Validity in 2027.** The tactic is not proven automatically invalid merely because all MIDs share an owner. Secondary accounts of gated Mastercard bulletin GLB 14127.1 (“Revised Standards for ACMP, GMAP and QMAP”) report an effective date of 1 April 2027 and conditional use of PDS 208, subelement 2 (Submerchant ID) instead of parent MID DE 42 for ECM, HECM and EFM. [Q3-C2a](research_notes.md#q3-c2a) [Q3-C2b](research_notes.md#q3-c2b)

**(b) Condition and scope.** The reported attribution change bites only when PDS208.s2 is populated. No reviewed evidence establishes automatic grouping of separately boarded direct MIDs across three ICAs where that condition is absent. [Q3-C2c](research_notes.md#q3-c2c) [Q3-C3a](research_notes.md#q3-c3a)

**(c) Single acquirer question.** Ask each acquirer: **“For every Mastercard clearing record, do you populate PDS208.s2; if so, which value is assigned to this legal/submerchant and can the same value occur across any of our MIDs?”** This controls which entity Mastercard sees and whether the acquirer’s own fraud/chargeback/decline intervention threshold binds first. [Q3-C1a](research_notes.md#q3-c1a) [Q3-C4a](research_notes.md#q3-c4a)

**(d) Same-day merchant-level programmes.** Secondary reports describe HDM (at least 5 cleared transactions, $5,000 combined FLD plus non-fraud chargebacks, 5%) and EDM (at least 5 cleared, $10,000, 50%) from 1 April 2027. These terms remain conditional pending the gated bulletin; do not price or operationalize them as confirmed scheme terms. [Q3-C5a](research_notes.md#q3-c5a) [Q3-C5b](research_notes.md#q3-c5b)

### Sources and claim mapping

#### Primary

| Claim | Direct source and precise location |
|---|---|
| [Q3-C1a](research_notes.md#q3-c1a) | [Mastercard Merchant Edition, 4 August 2026, §8.3.1, pp. 86–87](https://www.mastercard.com/content/dam/mccom/shared/business/support/rules-pdfs/SPME-Manual.pdf) |
| [Q3-C4a](research_notes.md#q3-c4a) | [Mastercard Merchant Edition, 4 August 2026, §6.2.2.10, pp. 75–76](https://www.mastercard.com/content/dam/mccom/shared/business/support/rules-pdfs/SPME-Manual.pdf) |

#### Secondary

| Claim | Direct source and precise location |
|---|---|
| [Q3-C2a](research_notes.md#q3-c2a)–[Q3-C2c](research_notes.md#q3-c2c) | [Martin, 3 August 2026](https://www.linkedin.com/pulse/mastercard-gmap-arrives-april-2027-heres-what-actually-changes-ik6ec); [Nwabunze, 4 August 2026, ¶49](https://www.linkedin.com/pulse/mastercard-changing-what-counts-merchant-risk-chidubem-j-p-nwabunze-u6jke) |
| [Q3-C5a](research_notes.md#q3-c5a), [Q3-C5b](research_notes.md#q3-c5b) | [Justt, updated 1 September 2026, HDM/EDM tables](https://justt.ai/blog/mastercard-gmap/); [Chargebacks911, 12 August 2026, HDM section](https://chargebacks911.com/global-merchant-audit-program-gmap/) |

### Confidence

- [Q3-C1a](research_notes.md#q3-c1a): **98%**; a newer public manual or gated DIM edit could supersede it.
- [Q3-C2a](research_notes.md#q3-c2a)–[Q3-C2c](research_notes.md#q3-c2c): **80–85%**, pending GLB 14127.1 and clearing specification.
- [Q3-C3a](research_notes.md#q3-c3a): **70%**, a bounded inference that direct bulletin text could change.
- [Q3-C4a](research_notes.md#q3-c4a): **95%**; [Q3-C5a](research_notes.md#q3-c5a)/[Q3-C5b](research_notes.md#q3-c5b): **72–75%** secondary.

### What I could not verify

GLB 14127.1, April 2027 DIMP/Connect text, PDS208.s2 blank/fallback/normalization, duplicate-ID treatment across ICAs, regional exceptions and all three acquirers’ clearing maps were unavailable. Current exact ECP/EFM stages and assessments are likewise gated.

### Working and rejected evidence

I established the current public DE42 baseline first, then retained 2027 material as secondary corroboration only. Generic assertions that Mastercard will aggregate every MID under one legal owner were rejected because no reviewed source supported that broader rule.

## Question 4 — “You retry my declines — what does that do to my exposure?”

### Direct answer

**(a) Visa.** Public VAMP is count-based: `count(TC40 fraud + TC15 disputes) / count(TC05 settled transactions)` for applicable CNP VisaNet activity. The public 1 April 2026 Excessive Merchant threshold in AP, Canada, EU and US is at least 150 bps and 1,500 monthly fraud-plus-dispute events, subject to acquirer programme status. Dollar volume and segment rates do not establish the ratio because TC05 is a count, TC40 may be additional, and scope/region/scheme/extract data are missing. [Q4-C1a](research_notes.md#q4-c1a) [Q4-C1b](research_notes.md#q4-c1b)

**(b) Cost and reduction.** Only if 2% and 7% are dollar rates across the $10m are disputed dollars $140,000.00 CIT plus $210,000.00 MIT, or $350,000.00/month (3.5% dollar-weighted). This is not a VAMP count or scheme assessment; a count-rate interpretation needs segment AOVs. For verified integer numerator `N` and denominator `S`, planning reduction is `max(0, N − (ceil(0.015 × S) − 1))`, subject to the VAMP Guide’s rounding/remediation rules. [Q4-C2a](research_notes.md#q4-c2a) [Q4-C2b](research_notes.md#q4-c2b) [Q4-C2c](research_notes.md#q4-c2c)

**(c) Mastercard.** Current ECP is monthly Mastercard chargeback count divided by preceding-month Mastercard transaction count at DE42, not a dollar ratio. Current EFM separately needs e-commerce/fraud/3DS/region/history inputs; reported 2027 GMAP cannot be tested without FLD, fraud/non-fraud allocation, Mastercard share and its gated data dictionary. [Q4-C3a](research_notes.md#q4-c3a) [Q4-C4a](research_notes.md#q4-c4a) [Q4-C4b](research_notes.md#q4-c4b)

A retry programme can affect approval/decline rate, duplicate account/amount requests, velocity, **Mastercard** MAC `03`/`21`, enumeration/card-testing signals, later fraud/FLD/chargebacks and acquirer controls. A declined retry is not, by itself, an ECP/EFM numerator. [Q4-C5a](research_notes.md#q4-c5a) [Q4-C5b](research_notes.md#q4-c5b)

**(d) Customer-facing answer.** Your $10m and 70/30 mix can imply $350,000.00/month of disputed dollars only if those percentages are dollar rates, but it cannot tell us your Visa or Mastercard monitoring ratio or a scheme fine because Visa VAMP and current Mastercard ECP use transaction counts, Visa also adds TC40 fraud to TC15 disputes, and current Mastercard EFM separately needs e-commerce, fraud, 3DS and region data. We will retry only issuer-permitted soft declines, suppress **Mastercard** MAC `03/21` and repeated attempts, and monitor approval/decline velocity, duplicate account/amount attempts, TC40/TC15/FLD and enumeration signals by acquirer/MID/submerchant because retries can trigger authorization, fraud and card-testing controls well before a dispute programme. [Q4-C1a](research_notes.md#q4-c1a) [Q4-C3a](research_notes.md#q4-c3a) [Q4-C5a](research_notes.md#q4-c5a)

### Sources and claim mapping

#### Primary

| Claim | Direct source and precise location |
|---|---|
| [Q4-C1a](research_notes.md#q4-c1a), [Q4-C1b](research_notes.md#q4-c1b) | [Visa VAMP fact sheet 2025 v2, p. 1](https://corporate.visa.com/content/dam/VCOM/corporate/visa-perspectives/security-and-trust/documents/visa-acquirer-monitoring-program-fact-sheet-2025.pdf) |
| [Q4-C2b](research_notes.md#q4-c2b) | [Visa Core Rules, 18 April 2026, §10.4.3.1 p. 633](https://usa.visa.com/dam/VCOM/download/about-visa/visa-rules-public.pdf) |
| [Q4-C3a](research_notes.md#q4-c3a), [Q4-C5a](research_notes.md#q4-c5a) | [Mastercard Merchant Edition, 4 August 2026, §§6.2.2.2–.10 and 8.3.1–.4](https://www.mastercard.com/content/dam/mccom/shared/business/support/rules-pdfs/SPME-Manual.pdf) |

#### Secondary

| Claim | Direct source and precise location |
|---|---|
| [Q4-C4a](research_notes.md#q4-c4a) | [Stripe, EFM section](https://docs.stripe.com/disputes/monitoring-programs?locale=en-GB); [Moneris, March 2025, p. 4](https://www.moneris.com/-/media/Files/Downloadable_Guides/Visa-and-Mastercard-Fraud-and-Chargeback-Program-Thresholds.pdf) |
| [Q4-C4b](research_notes.md#q4-c4b) | [Justt, GMAP section, updated 1 September 2026](https://justt.ai/blog/mastercard-gmap/) |

### Confidence

- [Q4-C1a](research_notes.md#q4-c1a)/[Q4-C1b](research_notes.md#q4-c1b): **97%**; guide, region, acquirer status and extract govern application.
- [Q4-C2a](research_notes.md#q4-c2a): **100% conditional on dollar-rate interpretation**; [Q4-C2b](research_notes.md#q4-c2b): **95%**.
- [Q4-C3a](research_notes.md#q4-c3a): **98%**; [Q4-C4a](research_notes.md#q4-c4a)/[Q4-C4b](research_notes.md#q4-c4b): **65–80%** conditional/gated.
- [Q4-C5a](research_notes.md#q4-c5a)/[Q4-C5b](research_notes.md#q4-c5b): **95–98%** for monitoring duties, **0%** for a specific fine without contract/data.

### What I could not verify

Visa/Mastercard split, CNP eligibility, TC40/TC15/FLD events, settled/prior-month counts, AOV, EFM inputs/history, region, programme stage, acquirer status and contract fees are unavailable. The VAMP Guide, Mastercard DIM/Pricing materials and GLB 14127.1 are gated; no scheme fine or compliance determination is asserted.

### Working and rejected evidence

Executed `php calculations.php`; it prints $7,000,000.00/$140,000.00 CIT, $3,000,000.00/$210,000.00 MIT, $350,000.00 total and 3.5% only under the dollar-rate interpretation. I did not convert dollars into transaction counts or calculate a fine; obsolete VDMP/VFMP material and generic threshold pages were rejected.

## Global source index

**Primary:** [EMVCo recurring technical features](https://www.emvco.com/dynamic/emv-3-d-secure-whitepaper-v2/recurring-and-installment-transactions/technical-features/); [Visa Core Rules](https://usa.visa.com/content/dam/VCOM/download/about-visa/visa-rules-public.pdf); [Visa VAMP fact sheet](https://corporate.visa.com/content/dam/VCOM/corporate/visa-perspectives/security-and-trust/documents/visa-acquirer-monitoring-program-fact-sheet-2025.pdf); [Visa stored-credential framework](https://usa.visa.com/content/dam/VCOM/global/support-legal/documents/stored-credential-transaction-framework-vbs-10-may-17.pdf); [Mastercard Transaction Processing Rules](https://www.mastercard.us/content/dam/public/mastercardcom/na/global-site/documents/transaction-processing-rules.pdf?trk=public_post_comment-text); [Mastercard Merchant Edition](https://www.mastercard.com/content/dam/mccom/shared/business/support/rules-pdfs/SPME-Manual.pdf); [EBA Q&A 2018_4031](https://www.eba.europa.eu/single-rule-book-qa/qna/view/publicId/2018_4031).

**Secondary/conditional:** [Justt GMAP](https://justt.ai/blog/mastercard-gmap/); [AltoPay GMAP](https://www.altopay.com/gmap/); [Chargebacks911 GMAP](https://chargebacks911.com/global-merchant-audit-program-gmap/); [Stripe monitoring programmes](https://docs.stripe.com/disputes/monitoring-programs?locale=en-GB); [Moneris thresholds](https://www.moneris.com/-/media/Files/Downloadable_Guides/Visa-and-Mastercard-Fraud-and-Chargeback-Program-Thresholds.pdf). The complete atomic ledger, including source dates, locations and limitations, is [research_notes.md](research_notes.md).

> Your $10m and 70/30 mix can imply $350,000.00/month of disputed dollars only if those percentages are dollar rates, but it cannot tell us your Visa or Mastercard monitoring ratio or a scheme fine because Visa VAMP and current Mastercard ECP use transaction counts, Visa also adds TC40 fraud to TC15 disputes, and current Mastercard EFM separately needs e-commerce, fraud, 3DS and region data. We will retry only issuer-permitted soft declines, suppress **Mastercard** MAC `03/21` and repeated attempts, and monitor approval/decline velocity, duplicate account/amount attempts, TC40/TC15/FLD and enumeration signals by acquirer/MID/submerchant because retries can trigger authorization, fraud and card-testing controls well before a dispute programme.
