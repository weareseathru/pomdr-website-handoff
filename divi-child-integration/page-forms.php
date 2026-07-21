<?php
/**
 * Template for the internal Forms and Documents hub (WP slug: forms).
 * Deliberately unlinked from any nav or footer: staff reach it by URL only
 * (mirrors the live formsPOMDR.html). Marked noindex so search engines skip
 * it. Documents currently link to the live site's downloads/ PDFs; migrate
 * them into the WP media library at launch and update the hrefs.
 */
add_action( 'wp_head', function () {
    echo '<meta name="robots" content="noindex, nofollow">' . "\n";
} );
get_header();
?>
<main id="main-content">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Forms and Documents</h1>
    <p class="page-narrative">The staff and volunteer <em>paperwork shelf</em>.</p>
    <p class="page-lead">Internal reference: contracts, handbooks, protocols, and how-tos. This page is not linked from the site; share the address only with staff and volunteers who need it.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width:900px">
    <section class="forms-group">
      <h2>Adoption</h2>
      <ul>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRadoptioncontract.pdf" target="_blank" rel="noopener">Adoption Contract</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/AdoptionEventProtocols.pdf" target="_blank" rel="noopener">Adoption Event Protocols</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRAdoptionQuestionnaire.pdf" target="_blank" rel="noopener">Adoption Questionnaire</a></li>
      </ul>
    </section>

    <section class="forms-group">
      <h2>Foster</h2>
      <ul>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRfosteragreement.pdf" target="_blank" rel="noopener">Foster Agreement</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRsettingupfosterhome.pdf" target="_blank" rel="noopener">Setting up a Foster Home</a></li>
      </ul>
    </section>

    <section class="forms-group">
      <h2>Volunteers</h2>
      <ul>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRoverviewvolunteers.pdf" target="_blank" rel="noopener">Overview - Volunteers</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRVolunteerApplication.pdf" target="_blank" rel="noopener">Volunteer Application and Release Form</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/VolunteerDressCode.pdf" target="_blank" rel="noopener">Volunteer Dress Code</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRVolunteerHandbook" target="_blank" rel="noopener">Volunteer Handbook</a></li>
      </ul>
    </section>

    <section class="forms-group">
      <h2>Helping Paw and community</h2>
      <ul>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/DogTrainerList.pdf" target="_blank" rel="noopener">Dog Trainer List</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRMontereyCountyFreeExamList.pdf" target="_blank" rel="noopener">Free Exam List - Monterey County</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRSantaCruzCountyFreeExamList.pdf" target="_blank" rel="noopener">Free Exam List - Santa Cruz County</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRHelpingPawReleaseForm.pdf" target="_blank" rel="noopener">Helping Paw Release Form</a></li>
      </ul>
    </section>

    <section class="forms-group">
      <h2>Shelterluv how-tos</h2>
      <ul>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRShelterluvDataEntryandHints.pdf" target="_blank" rel="noopener">Shelterluv: Data Entry and Hints</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRLookingUpDogInfoInShelterluv.pdf" target="_blank" rel="noopener">Shelterluv: Looking Up Dog Info</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRShelterluvInstructions.pdf" target="_blank" rel="noopener">Shelterluv: Master Instructions</a></li>
      </ul>
    </section>

    <section class="forms-group">
      <h2>Governance and HR</h2>
      <ul>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRBylaws.pdf" target="_blank" rel="noopener">Bylaws</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDREmployeeHandbook.pdf" target="_blank" rel="noopener">Employee Handbook</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRWorkplaceViolencePreventionPlan.pdf" target="_blank" rel="noopener">Workplace Violence Prevention Plan</a></li>
      </ul>
    </section>

    <section class="forms-group">
      <h2>General operations</h2>
      <ul>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRcontactlist.pdf" target="_blank" rel="noopener">Contact List</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRdonationboxbig.pdf" target="_blank" rel="noopener">Donation Box Big</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRdonationboxsmall.pdf" target="_blank" rel="noopener">Donation Box Small</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/holdharmlessliability.pdf" target="_blank" rel="noopener">Hold Harmless Liability Form</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRhomecheckform.pdf" target="_blank" rel="noopener">Home Check Form</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/IncidentReportForm.pdf" target="_blank" rel="noopener">Incident Report Form</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRProcessingFollowUpContactsInLGL.pdf" target="_blank" rel="noopener">LGL: Processing Follow-up Contacts</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRmailinglistsignup.pdf" target="_blank" rel="noopener">Mailing List Signup</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRorgchart.pdf" target="_blank" rel="noopener">Organization Chart</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRoverviewhalfsheet.pdf" target="_blank" rel="noopener">Overview 1/2 sheet</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRperpetualcarefaq.pdf" target="_blank" rel="noopener">Perpetual Care FAQ</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/petinsurance.pdf" target="_blank" rel="noopener">Pet Insurance</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/PetSitters.pdf" target="_blank" rel="noopener">Pet Sitters</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRpettrustexpensecklist.pdf" target="_blank" rel="noopener">Pet Trust Expense Checklist</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRpetprofileform.pdf" target="_blank" rel="noopener">Pet Trust Pet Profile Form</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRBauerBoandClinicProtocol.pdf" target="_blank" rel="noopener">Protocol Bauer Staff/Boand Clinic Staff</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/POMDRsalessheet.pdf" target="_blank" rel="noopener">Sales Sheet</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/transferofdogguardianship.pdf" target="_blank" rel="noopener">Transfer of Dog Guardianship Form</a></li>
        <li><a href="https://www.peaceofminddogrescue.org/downloads/whenadogmoves.pdf" target="_blank" rel="noopener">When a Dog Moves</a></li>
      </ul>
    </section>
    <section class="forms-group">
      <h2>Educational videos</h2>
      <ul>
        <li><a href="https://www.youtube.com/watch?v=ra6ob7pEgaA" target="_blank" rel="noopener">Dog Body Language (20 minutes)</a></li>
        <li><a href="https://www.youtube.com/watch?v=NHQm2N6wDZY" target="_blank" rel="noopener">Dog Body Language (60 minutes)</a></li>
        <li><a href="https://www.youtube.com/watch?v=0IhK0ytrr4E" target="_blank" rel="noopener">Foster Training</a></li>
        <li><a href="https://www.youtube.com/watch?v=spJoNCP6XrI" target="_blank" rel="noopener">The Importance of Management</a></li>
        <li><a href="https://www.youtube.com/watch?v=ydN8stWwYo0" target="_blank" rel="noopener">Vet Packs</a></li>
      </ul>
    </section>
  </div>
</section>

</main>
<style>
.forms-group { margin-bottom: 40px; }
.forms-group h2 { font-family: var(--font-serif); font-size: 28px; font-weight: 500; margin: 0 0 14px; padding-bottom: 8px; border-bottom: 1px solid var(--line); }
.forms-group ul { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 32px; list-style: none; margin: 0; padding: 0; }
@media (max-width: 640px) { .forms-group ul { grid-template-columns: 1fr; } }
.forms-group li a {
  display: inline-flex; align-items: center; gap: 8px; min-height: 40px;
  color: var(--blue-text); font-size: 17px; font-weight: 600; text-decoration: underline; text-underline-offset: 3px;
}
.forms-group li a:hover { color: var(--blue-900); }
</style>
<?php get_footer();
